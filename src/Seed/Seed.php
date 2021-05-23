<?php


namespace ITaikai\Seed;

use Exception;
use ITaikai\Competition;
use ITaikai\Configure;
use ITaikai\Group;
use ITaikai\IndividualMatch;
use ITaikai\Round;

abstract class Seed {

    const EMPTY_PLACE = -1;

    protected $depth = 0;
    protected $maxDepth = null;
    protected $countMatches = 0;

    protected $depthNames    = [0 => 'F', 1 => 'S', '2' => 'Q'];
    protected $depthCounts   = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0];

    abstract function getSeedingList($type);

    abstract function createRandomMatch();

    abstract function createPlayoffMatches($seeds, $depth);

    abstract function createPoolMatches($group, $seedingSchema);

    public function setup($type)
    {

        if (!in_array($type, array_keys(Configure::read('App.rules.modes')))) {
            throw new Exception('Unknown mode for round');
        }
        $current = Competition::first();

        $group_size = $current['group_size'];
        $team_size = $current['team_size'];
        IndividualMatch::truncate();

        $seeds = array_keys($this->getSeedingList(false));
        $round = Round::create([
            'title'       => $type,
            'type'        => $type,
            'seeds_count' => count($seeds)
        ]);

        switch ($type) {
            case 'random':
                $this->createRandomMatch($team_size);
                break;
            case 'elimination': // Eliminiation Round
                $this->depth = $this->calcTreeDepth(count($seeds));
                $round->update(['depth' => $this->depth, 'extra_brackets' => $this->calcExtraBrackets($seeds)]);
                $this->seedPlayoffMatches($seeds, $this->depth - 1);
                break;

            case 'pool':        // Pool Round
                Group::adjust($group_size, count($seeds));
                $this->seedPoolMatches($group_size);
                break;
            case 'both':         // Pool ROund and Elimination
                Group::adjust($group_size, count($seeds));
                $this->depth = $this->calcTreeDepth($group_size);
                //$this->seedPlayoffMatches($seeds, $depth);
                $this->seedPoolMatches($group_size);
                break;
            case 'round-robin': // Jeder gegen Jeden Liga system (Eine Gruppe)
                Group::add(count($seeds));
                $this->seedPoolMatches(count($seeds));
                break;
            case 'last_man_standing': // Last Man Standing (Kachinukisen)
                break;
        }
        $round->updateRound();
    }

    public function getDepth(){
        return $this->depth;
    }

    private function calcTreeDepth($num)
    {
        if ($num < 0) {
            return false;
        }
        return ceil(log($num, 2)); // Logarithmus $num zur Basis 2
    }

    protected function recursivePlayoffMatchTree($depth = 0, $count = 0, $parentId = null)
    {
        if ($depth < 0) {
            IndividualMatch::fixTree();
            return;
        }
        if ($this->maxDepth == null) {
            $this->maxDepth = $depth;
        }

        $nextMatchId = ($this->depthCounts[$depth] % 2 == 0) ? 1 : 2;
        $match       = IndividualMatch::addEmpty($this->generateMatchName($depth, $count), $parentId, $count, $nextMatchId);

        $this->countMatches++;
        $this->depthCounts[$depth]++;
        $this->recursivePlayoffMatchTree($depth - 1, $count + 1, $match->id);
        $this->recursivePlayoffMatchTree($depth - 1, $count + 1, $match->id);
    }

    protected function seedToMatches($participants, $matches)
    {
        $match_ids   = array_keys($matches);
        $match_seeds = [];
        $match       = 0;

        foreach ($participants as $i => $s) {
            $match_id                 = $match_ids[$match];
            $match_seeds[$match_id][] = $s;
            if ($i % 2) {
                $match++;
            }
        }
        return $match_seeds;
    }

    protected function seedPlayoffMatches($seeds, $depth = 0)
    {
        $this->createPlayoffMatches($seeds, $depth);
    }

    protected function seedPoolMatches($group_size)
    {
        $groups = Group::orderBy('id')->get();

        $a         = 0;
        $i         = 1;
        $parent_id = null;
        foreach ($groups as $group_pos => $group) {
            $schema = $this->getSeedingMatrix($group_size);

            // Gruppen Matches erstellen
            $this->createPoolMatches($group, $schema);
            if ($i % 2 == 0) {
                $a++;
            }
            $i++;
        }
        return true;
    }

    private function getSeedingMatrix($size)
    {
        $m      = matrix($size, $size);
        $result = [];
        $b      = $size - 1;
        $j      = 1;
        for ($i = 0; $i < $size; $i++) {
            for ($r = 0; $r < $b; $r++) {
                $m[$r][$r + $i + 1] = $j;
                $m[$r + $i + 1][$r] = $j;
                $result[$j]         = [0 => $r + 1, 1 => $r + $i + 2];
                $j++;
            }
            $b--;
        }
        return $result;
    }

    private function generateMatchName($depth, $count)
    {
        $matchId = isset($this->depthCounts[$depth]) ? $this->depthCounts[$depth] + 1 : 1;
        $name    = isset($this->depthNames[$count]) ? $this->depthNames[$count] . "-" . $matchId : "Round " . ($depth + 1) . "-$matchId";
        return $name;
    }

    /**
     * @param $seeds
     * @return int
     */
    protected function calcExtraBrackets($seeds)
    {
        return count($seeds) - pow(2, $this->depth);
    }

}