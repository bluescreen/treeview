<?php
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class TestCase extends PHPUnit_Framework_TestCase {


    public function setUp():void
    {
        parent::setUp();
        if(method_exists($this, 'setupDB')){
            $this->setupDB();
        }
    }

    public function tearDown():void
    {
        parent::tearDown();
        if(method_exists($this, 'teardownDB')){
            $this->teardownDB();
        }

    }

}