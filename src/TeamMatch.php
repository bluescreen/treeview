<?php


namespace ITaikai;


use ITaikai\Behavior\NestedSet;

class TeamMatch extends Model {

    use NestedSet;

    public static function addEmpty($string, $parentId = 0, $depth = 0, $nextMatchId = 0)
    {
        /** @var TeamMatch $teamMatch */
        $teamMatch = TeamMatch::create([
            'title'    => $string,
            'team_id1' => IndividualMatch::EMPTY_PLACE,
            'team_id2' => IndividualMatch::EMPTY_PLACE,
            'depth'    => $depth,
            'next_pos' => $nextMatchId
        ]);
        if ($parentId == null) {
            $teamMatch->makeRoot();
        } else {
            $teamMatch->setParentId($parentId);
        }
        $teamMatch->createSubMatches();

        return $teamMatch;
    }

    public function matches()
    {
        return $this->hasMany(IndividualMatch::class, 'team_matches_id');
    }

    public function red()
    {
        return $this->belongsTo(Team::class, 'team_id1');
    }

    public function white()
    {
        return $this->belongsTo(Team::class, 'team_id2');
    }

    public function createSubMatches($teamSize = 5)
    {
        $positions = Configure::read('App.team_positions');

        $this->matches()->delete();
        $team1 = $this->red()->with('participants')->first();
        $team2 = $this->white()->with('participants')->first();

        for ($i = 0; $i < $teamSize; $i++) {
            $competitor1 = isset($team1->participants[$i]) ? $team1->participants[$i]->pivot->participant_id : -1;
            $competitor2 = isset($team2->participants[$i]) ? $team2->participants[$i]->pivot->participant_id : -1;
            $title       = $this->title . " " . $positions[$i];

            $match = IndividualMatch::createTeamSubMatch($competitor1, $competitor2, $this->id, $title, $i + 1);
            if ($i == 0) {
                $first_match = $match->id;
            }
        }
        if (isset($first_match)) {
            $this->saveField('current_match', $first_match);
        }
        return true;
    }
}