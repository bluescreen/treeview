<?php


namespace ITaikai\Behavior;


class Eliminiation {

    public function updateMatch(&$model){

        if($m['parent_id'] > 0){
            if($m['winner_id'] > 0){
                $this->moveToNextMatch($m['winner_id'],$m['parent_id'],$m['next_pos']);
            }
            else
                $this->removeFromMatch($m['parent_id'],$m['next_pos']);
        }
    }

    public function removeFromMatch(&$model,$match_id,$pos){
        $model->id = $match_id;
        if(!$model->exists()) throw new Exception('Cannot remove from next Match');
        $model->saveField($this->positions[$pos],0);
    }

    public function moveToNextMatch(&$model, $competitor_id,$match_id,$pos){
        $model->id = $match_id;
        if($match_id == null) return;
        if(!$model->exists()) 						throw new Exception("Cannot find next Match '$match_id'");
        if($competitor_id < 0 || $match_id == null) throw new Exception('Cannot move to next match');
        return $model->saveField($this->positions[$pos],$competitor_id);
    }

    // Ermittle die benötigte Tiefe des Baumes und die benötigten Extra Brackets und Freilose
    public static function getTreeDepth(&$model, $num){
        if($num < 0) return false;
        return ceil(log($num,2)); // Logarithmus $num zur Basis 2
    }
}