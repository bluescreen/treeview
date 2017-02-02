<?php


namespace ITaikai\Seed;


use ITaikai\Group;
use ITaikai\Team;
use ITaikai\TeamMatch;

class TeamSeed extends Seed {

    protected $teamSize = 3;

    public function setTeamSize($teamSize)
    {
        $this->teamSize = $teamSize;
    }

    public function createRandomMatch()
    {
        $teams = Team::inRandomOrder()->limit(2)->pluck('id');
        if ($teams->count() < 2) {
            throw new \Exception("Not enough teams");
        }

        /** @var TeamMatch $teamMatch */
        $teamMatch = TeamMatch::create([
            'title'    => 'Random Team Match',
            'team_id1' => $teams[0],
            'team_id2' => $teams[1],
        ]);
        $teamMatch->createSubMatches($this->teamSize);
    }

    public function createPlayoffMatches($seeds, $depth)
    {
        $this->recursivePlayoffMatchTree($depth);
        $matches     = TeamMatch::getLeafs('TeamMatch.id');
        $match_seeds = $this->seedToMatches($matches, $seeds);
        foreach ($match_seeds as $match_id => $teams) {
            TeamMatch::findOrFail($match_id)->update([
                'team_id1' => $teams[0], 'team_id2' => $teams[1]
            ]);
        }
    }

    public function createPoolMatches($group, $seedingSchema)
    {
        $team_groups = $group['TeamGroup'];
        $group_id    = $group['Group']['id'];
        $letter      = sprintf("%c", $group['Group']['group_pos'] + 64);

        foreach ($seedingSchema as $i => $row) {
            list($w, $r) = $row;
            $team1 = isset($team_groups[$w - 1]['team_id']) ? $team_groups[$w - 1]['team_id'] : -1;
            $team2 = isset($team_groups[$r - 1]['team_id']) ? $team_groups[$r - 1]['team_id'] : -1;

            $this->TeamMatch->add($team1, $team2, $group_id, __("Poolmatch %s-%d", $letter, $i));
            $this->TeamMatch->saveField('round_id', $this->round_id);
            $this->TeamMatch->saveField('type', 'GroupMatch');
        }
        return true;
    }

    protected function recursivePlayoffMatchTree($depth = 0, $count = 0, $parentId = null)
    {
        if ($depth < 0) {
            TeamMatch::fixTree();
            return;
        }
        if ($this->maxDepth == null) {
            $this->maxDepth = $depth;
        }

        $nextMatchId = ($this->depthCounts[$depth] % 2 == 0) ? 1 : 2;
        $match = TeamMatch::addEmpty("", $parentId, $count, $nextMatchId);

        $this->countMatches++;
        $this->depthCounts[$depth]++;
        $this->recursivePlayoffMatchTree($depth - 1, $count + 1, $match->id);
        $this->recursivePlayoffMatchTree($depth - 1, $count + 1, $match->id);
    }

    public function simulate($id)
    {
        $this->TeamMatch->id = $id;
        $this->TeamMatch->simulate();
    }

    public function reset($id)
    {
        $this->TeamMatch->id = $id;
        $this->TeamMatch->reset();
    }

    public function score()
    {
        if ($this->request->isPost()) {
            if (!empty($this->data)) {
                list($id, $player, $hit) = $this->data;
                $this->Match->id = $id;
                if ($action == "remove") {
                    $this->Match->removePoint($player, $hit);
                } else {
                    $this->Match->addPoint($player, $hit);
                }
            }
            $this->TeamMatch->update($id);
        }
        $scores = $this->TeamMatch->getScores($id);
        die(json_encode($scores));
    }


    public function getSeedingList($seed_group)
    {
        if ($seed_group) {
            return Group::pluck('id')->all();
        } else {
            return Team::pluck('id')->all();
        }
    }
}