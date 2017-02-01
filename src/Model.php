<?php


namespace ITaikai;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel {

    protected $guarded = [];
    public $timestamps = false;

    protected function saveField($name, $value){
        $this->{$name} = $value;
        $this->save();
    }
}