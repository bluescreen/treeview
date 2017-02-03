<?php
namespace ITaikai;


class Competitor extends Model {

    public function participations()
    {
        return $this->hasMany(Participant::class, 'competitor_id');
    }

    public function getFullnameAttribute(){
        return $this->first_name." ".$this->name;
    }

    public function participate($tournamentId)
    {
        Participant::create(['competitor_id' => $this->id, 'tournament_id' => $tournamentId]);
    }

    public static function participateAll()
    {
        foreach (Competitor::all() as $competitor) {
            $competitor->participate(1);
        }
    }
}