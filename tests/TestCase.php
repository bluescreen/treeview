<?php


class TestCase extends PHPUnit_Framework_TestCase {


    public function setUp()
    {
        parent::setUp();
        if(method_exists($this, 'setupDB')){
            $this->setupDB();
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        if(method_exists($this, 'teardownDB')){
            $this->teardownDB();
        }

    }

}