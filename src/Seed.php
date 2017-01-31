<?php
namespace ITaikai;

class Seed {

    const EMPTY_PLACE = -1;

    protected $depthNames    = [0 => 'Finals', 1 => 'Semi', '2' => 'Quarter', 3 => 'Eight'];
    protected $depthCounts   = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0];
    protected $maxDepth      = null;
    protected $countMatches  = 0;
    protected $neededMatches = 0;

    public function createPlayoffMatches($seeds, $depth)
    {
        $this->recursivePlayoffMatchTree($depth);
        /*$leafs = Match::getLeafs('Match.id');

        $match_seeds = $this->seedToMatches($leafs, $seeds);
        foreach ($match_seeds as $match_id => $c) {
            @list($white, $red) = $c;
            $this->Match->id = $match_id;
            $this->Match->assignCompetitors($white, $red);
        }*/
        //$this->orderMatches(6);
    }

    protected function recursivePlayoffMatchTree($depth = 0, $count = 0, $parent_id = null)
    {
        if ($depth < 0) {
            return;
        }
        if ($this->maxDepth == null) {
            $this->maxDepth = $depth;
        }

        $x        = isset($this->depthCounts[$depth]) ? $this->depthCounts[$depth] + 1 : 1;
        $next_pos = ($this->depthCounts[$depth] % 2 == 0) ? 1 : 2;
        $name     = isset($this->depthNames[$count]) ? $this->depthNames[$count] . "-" . $x : "Runde " . ($depth + 1) . "-$x";

        $match = Match::add(self::EMPTY_PLACE, self::EMPTY_PLACE, null, $name, $parent_id, $count, $next_pos);

        $this->countMatches++;
        $this->depthCounts[$depth]++;
        $this->recursivePlayoffMatchTree($depth - 1, $count + 1, $match->id);
        $this->recursivePlayoffMatchTree($depth - 1, $count + 1, $match->id);
    }

    public function createPoolMatches($group, $schema)
    {

        $participants = $group['Participant'];
        $group_id     = $group['Group']['id'];
        $letter       = sprintf("%c", $group['Group']['group_pos'] + 64);

        foreach ($schema as $i => $row) {
            list($w, $r) = $row;
            $c1 = isset($participants[$w - 1]['competitor_id']) ? $participants[$w - 1]['competitor_id'] : -1;
            $c2 = isset($participants[$r - 1]['competitor_id']) ? $participants[$r - 1]['competitor_id'] : -1;

            $match = Match::add($c1, $c2, $group_id, __("Poolmatch %s-%d", $letter, $i), null);
        }
        return true;
    }

    // Seeds verteilen

    protected function seedToMatches($matches, $seeds)
    {
        $match_ids   = array_keys($matches);
        $match_seeds = [];
        $match       = 0;

        foreach ($seeds as $i => $s) {
            $match_id                 = $match_ids[$match];
            $match_seeds[$match_id][] = $s;
            if ($i % 2) {
                $match++;
            }
        }
        return $match_seeds;
    }

    private function orderMatches($area_count = 2)
    {
        $matches = $this->Match->find('all', [
            'order'      => 'Match.depth DESC,Match.id ASC',
            'conditions' => ['Match.round_id' => $this->id],
            'fields'     => "Match.depth,count(Match.id) as MatchCount,group_concat(DISTINCT Match.id ORDER BY Match.id ASC) as Matches",
            'group'      => "Match.depth"
        ]);

        foreach ($matches as $i => $m) {
            $depth                 = $m['Match']['depth'];
            $match_count           = $m[0]['MatchCount'];
            $area_split            = ($match_count < $area_count) ? 2 : $area_count;
            $sortedMatches[$depth] = array_split_new(explode(",", $m['0']['Matches']), $area_split);
        }

        $number = 1;
        foreach ($sortedMatches as $depth => $areas) {
            foreach ($areas as $area_id => $matches) {
                foreach ($matches as $i => $match_id) {
                    $this->Match->id = $match_id;
                    $this->Match->save([
                        'order_number' => $number,
                        'area_id'      => $area_id + 1,
                    ]);
                    $number++;
                }
            }
        }
    }


}