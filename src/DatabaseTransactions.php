<?php


namespace ITaikai;

use Illuminate\Database\Capsule\Manager as Capsule;


trait DatabaseTransactions {

    public function setupDB()
    {
        Capsule::connection()->beginTransaction();
    }

    public function teardownDB(){
        Capsule::connection()->rollBack();
    }
}