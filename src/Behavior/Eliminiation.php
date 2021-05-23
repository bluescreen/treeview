<?php


namespace ITaikai\Behavior;


use ITaikai\IndividualMatch;

trait Eliminiation {

    private $positions = array(1=> 'white_id',2 => 'red_id');

    public function updateMatch(){
        if($this->parent_id > 0){
            if($this->winner_id > 0){
                $this->moveToNextMatch($this->winner_id,$this->parent_id,$this->next_pos);
            }
            else
                $this->removeFromMatch($this->parent_id,$this->next_pos);
        }
    }

    private function removeFromMatch($match_id,$pos){
        IndividualMatch::find($match_id)->saveField($this->positions[$pos],0);
    }

    private function moveToNextMatch($competitor_id,$match_id,$pos){
        IndividualMatch::find($match_id)->saveField($this->positions[$pos],$competitor_id);
    }
}