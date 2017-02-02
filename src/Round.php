<?php


namespace ITaikai;

class Round extends Model {

    public function matches(){
        return $this->hasMany(Match::class);
    }

    public function updateRound(){
        $matches = $this->matches()->pluck('status','id');
        if(empty($matches)) return;

        $this->saveField('matches_count',count($matches));
        $done = $i= 0;
        $match_ids = array();
        foreach($matches as $id => $status){
            if($status == 4) $done++;
            $match_ids[$i] = $id;
            $i++;
        }

        if(isset($match_ids[$done])){
            $this->saveField('current_match',$match_ids[$done]);
        }
        $this->saveField('matches_done',$done);
    }
}