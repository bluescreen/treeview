<?php
namespace ITaikai;

use Illuminate\Support\Collection;

class Match extends Model {

    use NestedSet;

    const WAITING       = 0;
    const RUNNING       = 1;
    const PAUSED        = 2;
    const FINISHED      = 3;
    const NEED_DECISION = 4;
    const INVALID       = 5;

    const EMPTY_PLACE = -1;


    protected $guarded = [];

    protected $casts = [
        'score_white' => 'array',
        'score_red'   => 'array'
    ];


    public function referees()
    {
        return $this->belongsToMany(Referee::class, 'referee_matches');
    }

    public function red()
    {
        return $this->belongsTo(Competitor::class, 'red_id');
    }

    public function white()
    {
        return $this->belongsTo(Competitor::class, 'white_id');
    }

    public function getWhiteName()
    {
        return ($this->white) ? $this->white->name : 'BYE';
    }

    public function getRedName()
    {
        return ($this->red) ? $this->red->name : 'BYE';
    }

    public function assignCompetitors($white, $red)
    {
        $this->update([
            'white_id' => ($white > 0) ? $white : -1,
            'red_id'   => ($red > 0) ? $red : -1
        ]);
    }

    public function assignToArea($number, $area_id)
    {
        $this->update([
            'order_number' => $number,
            'area_id'      => $area_id + 1,
        ]);
    }

    public static function getMatchPartcipants()
    {
        $matches = Match::with('red', 'white')->whereRaw('rght = lft + 1')->get();

        $result = [];
        $pos    = 0;
        /** @var Match $match */
        foreach ($matches as $match) {
            $result[] = ['id' => $match->white_id, 'name' => $match->getWhiteName(), 'pos' => $pos];
            $pos++;
            $result[] = ['id' => $match->red_id, 'name' => $match->getRedName(), 'pos' => $pos];
            $pos++;
        }
        return $result;
    }

    public static function getTreeMatches()
    {
        /** @var Collection $matches */
        $matches = Match::orderBy('lft')->get();
        return $matches->map(function (Match $match) {
            return [
                'title'        => $match->title,
                'white_id'     => (int)$match->white_id,
                'red_id'       => (int)$match->red_id,
                'winner_id'    => (int)$match->winner_id,
                'points_white' => (int)$match->points_white,
                'points_red'   => (int)$match->points_red,
                'score_white'  => $match->score_white,
                'score_red'    => $match->score_red,
                'name'         => "WINNER NAME",
                'id'           => $match->id
            ];
        });
    }

    public static function getTreePaths()
    {
        //$data = $this->find("list", ['order' => 'lft', 'fields' => 'Match.id,Match.id']);
        //$data    = self::orderBy('lft')->pluck('id', 'id')->all();
        $paths  = Match::selectRaw("winner_id,group_concat(id) as path")
            ->where('winner_id', '>', 0)->groupBy('winner_id')->get();
        $result = [];
        foreach ($paths as $row) {
            foreach (explode(',', $row->path) as $match_id) {
                $result[$row->winner_id][] = $match_id;
            }
        }
        return $result;
    }

    public static function addEmpty($name, $parrent_id = null, $depth = null, $nextPos = null)
    {
        return self::add(self::EMPTY_PLACE, self::EMPTY_PLACE, null, $name, $parrent_id, $depth, $nextPos);
    }

    public static function add($competitor1, $competitor2, $group_id = null, $name = null, $parent_id = null, $depth = null, $next_pos = null)
    {
        $score = ['men' => 0, 'kote' => 0, 'do' => 0, 'tsuki' => 0, 'penalty' => 0, 'hansoku' => 0];

        /** @var Match $match */
        $match = self::create([
            'white_id'    => $competitor1,
            'red_id'      => $competitor2,
            'score_white' => $score,
            'score_red'   => $score,
            'group_id'    => $group_id,
            'title'       => $name,
            'status'      => 0,
            'depth'       => $depth,
            'next_pos'    => $next_pos,
        ]);
        if ($parent_id == null) {
            $match->makeRoot();
        } else {
            $match->setParentId($parent_id);
        }
        $match->save();
        return $match;
    }

    public static function createTeamSubMatch($competitor1, $competitor2, $team_match_id = null, $title = '', $pos = null, $max_points = 2)
    {
        $score = json_encode(['men' => 0, 'kote' => 0, 'do' => 0, 'tsuki' => 0, 'penalty' => 0, 'hansoku' => 0]);
        return self::create([
            'white_id'        => $competitor1,
            'red_id'          => $competitor2,
            //'tournament_id'	=> $this->tournament_id,
            'score_white'     => $score,
            'score_red'       => $score,
            'team_matches_id' => $team_match_id,
            'title'           => $title,
            'status'          => 0,
            'max_points'      => $max_points,
            'pos'             => $pos
        ]);
    }

    public function assignReferees($referees)
    {
        $this->referees()->detach();
        foreach ($referees as $position => $referee) {
            $this->referees()->attach($referee, [
                'position' => $position
            ]);

        }
    }

}