<?php
namespace ITaikai;


class Participant extends Model {

    protected $guarded = [];
    public $timestamps = false;

    public static function getNameList(){
        return self::with('competitor')->has('competitor')->get()->map(function($participant){
           return ['id' => $participant->id, 'name' => $participant->competitor->name];
        });
    }

    public function competitor(){
        return $this->belongsTo(Competitor::class, 'competitor_id');
    }

    public static function autoassignToTeams(){
        $participants = Participant::inRandomOrder()->get();
        Team::get()->each(function(Team $team) use (&$participants){
            $team->assignParticipants($participants->take(5));
        });
    }
}