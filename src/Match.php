<?php
namespace ITaikai;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Kalnoy\Nestedset\NodeTrait;

class Match extends Model {

    use NodeTrait;

    protected $guarded    = [];
    public    $timestamps = false;

    protected $casts = [
        'score_white',
        'score_red'
    ];

    public function red()
    {
        return $this->belongsTo(Competitor::class, 'red_id');
    }

    public function white()
    {
        return $this->belongsTo(Competitor::class, 'white_id');
    }

    public static function getLeafs()
    {
        return self::whereRaw("rght = lft + 1")->lists('title', 'id')->all();
    }

    public function getWhiteName()
    {
        return ($this->white) ? $this->white->name : 'BYE';
    }

    public function getRedName()
    {
        return ($this->red) ? $this->red->name : 'BYE';
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
        $data    = self::orderBy('lft')->lists('id', 'id')->all();
        $matches = array_mirror(array_keys($data));
        $paths   = self::query("SELECT winner_id,group_concat(id) as path FROM `matches` where winner_id  > 0 group by winner_id");
        $result  = [];
        foreach ($paths as $row) {
            $winner_id = $row['matches']['winner_id'];
            foreach (explode(',', $row[0]['path']) as $match_id) {
                $result[$winner_id][] = $matches[$match_id];
            }
        }
        return $result;
    }

    public static function add($competitor1, $competitor2, $group_id = null, $name = null, $parent_id = null, $depth = null, $next_pos = null)
    {

        $score = ['men' => 0, 'kote' => 0, 'do' => 0, 'tsuki' => 0, 'penalty' => 0, 'hansoku' => 0];

        /** @var Match $match */
        $match = self::create([
            'white_id'    => $competitor1,
            'red_id'      => $competitor2,
            'score_white' => json_encode($score),
            'score_red'   => json_encode($score),
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

    public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rght';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

}