<?php
namespace ITaikai;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model {

    protected $guarded = [];
    public $timestamps = false;

    public static function getNameList(){
        return self::with('competitor')->get()->map(function($participant){
           return ['id' => $participant->id, 'name' => $participant->competitor->name];
        });
    }

    public function competitor(){
        return $this->belongsTo(Competitor::class, 'competitor_id');
    }
}