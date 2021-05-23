<?php
namespace ITaikai\Seed;

use ITaikai\IndividualMatch;
use ITaikai\Participant;
use ITaikai\Referee;

class IndividualSeed extends Seed {

    const AREAS = 2;

    public function createRandomMatch()
    {
        $referess = Referee::inRandomOrder()->limit(3)->get();
        $p        = Participant::inRandomOrder()->limit(2)->get();
        /** @var IndividualMatch $match */
        $match = IndividualMatch::create([
            'title'    => 'Random Match',
            'red_id'   => $p[0]->competitor_id,
            'white_id' => $p[1]->competitor_id,
            'type'     => 'RandomMatch',
            'area_id'  => rand(1, 4)
        ]);
        $match->assignReferees($referess);
    }

    public function createPlayoffMatches($participantIds, $depth)
    {
        $this->recursivePlayoffMatchTree($depth);
        $leafMatches = IndividualMatch::getLeafs('Match.id')->all();
        $matchSeeds  = $this->seedToMatches($participantIds, $leafMatches);
        collect($matchSeeds)->each(function ($seed, $matchId) {
            @list($white, $red) = $seed;
            IndividualMatch::find($matchId)->assignCompetitors($white, $red);
        });
        $this->orderMatches(self::AREAS);
    }

    public function createPoolMatches($group, $seedingSchema)
    {
        $participants = Participant::pluck('competitor_id');
        $letter       = sprintf("%c", $group->group_pos + 64);

        foreach ($seedingSchema as $i => $row) {
            list($w, $r) = $row;
            $c1 = isset($participants[$w - 1]) ? $participants[$w - 1] : -1;
            $c2 = isset($participants[$r - 1]) ? $participants[$r - 1] : -1;

            IndividualMatch::add($c1, $c2, $group->id, sprintf("Poolmatch %s-%d", $letter, $i), null);
        }
    }

    public function getSeedingList($type)
    {
        return Participant::inRandomOrder()->pluck('competitor_id', 'competitor_id')->all();
    }



    private function orderMatches($area_count = 2)
    {
        $matches = IndividualMatch::selectRaw('depth, count(matches.id) as count')
            ->orderBy('depth', 'DESC')
            ->orderBy('id', 'ASC')
            ->groupBy('depth')
            ->get();


        $matches = $matches->map(function($record){
            $record->matchIds = IndividualMatch::where('depth', $record->depth)->orderBy('id')->pluck('id')->implode(',');
            return $record;
        });

        $sortedMatches = [];
        foreach ($matches as $i => $match) {
            $depth                 = $match->depth;
            $match_count           = $match->count;
            $area_split            = ($match_count < $area_count) ? 2 : $area_count;
            $sortedMatches[$depth] = $this->splitMatchIds(explode(",", $match->matchIds), $area_split);
        }
        $this->assignMatchesToAreas($sortedMatches);
    }

    private function splitMatchIds($array, $pieces = 2)
    {
        if ($pieces < 2) {
            return [$array];
        }
        $newCount = ceil(count($array) / $pieces);
        $a        = array_slice($array, 0, $newCount, true);
        $b        = $this->splitMatchIds(array_slice($array, $newCount, null, true), $pieces - 1);
        return array_merge([$a], $b);
    }

    private function assignMatchesToAreas($sortedMatches)
    {
        $number = 1;
        foreach ($sortedMatches as $depth => $areas) {
            foreach ($areas as $area_id => $matches) {
                foreach ($matches as $i => $match_id) {
                    IndividualMatch::find($match_id)->assignToArea($number, $area_id);
                    $number++;
                }
            }
        }
    }
}