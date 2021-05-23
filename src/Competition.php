<?php
 namespace ITaikai;

class Competition extends Model {

    protected $guarded = [];
    public $timestamps = false;

    public static function clear(){
        Competition::truncate();
        IndividualMatch::truncate();
        Participant::truncate();
        IndividualMatch::truncate();
        Competitor::truncate();
        Team::truncate();
        TeamMatch::truncate();
        TeamParticipant::truncate();
        Round::truncate();
        Group::truncate();
    }
}