<?php


use ITaikai\Competition;
use ITaikai\Group;
use ITaikai\IndividualMatch;
use ITaikai\Referee;
use ITaikai\Round;
use ITaikai\Seed\IndividualSeed;

class IndividualSeedingTest extends TestCase {

    use DatabaseTransactions, Shared;

    public function setUp():void
    {
        parent::setUp();
        Competition::clear();
    }

    /** @test */
    public function it_should_seed_individual_random(){
        $this->given_competitors(4);
        factory(Referee::class, [], 3);

        $seed  = new IndividualSeed();
        $seed->setup('random');

        $match = IndividualMatch::first();
        $this->assertNotNull($match);
        $this->assertCount(3, $match->referees);
        $this->assertEquals('RandomMatch', $match->type);
    }

    /** @test */
    public function it_should_seed_individual_elimination(){
        $this->given_competitors(8);


        $seed  = new IndividualSeed();
        $seed->setup('elimination');

        $round = Round::first();
        $this->assertEquals('elimination', $round->type);
        $this->assertEquals(8, $round->seeds_count);
        $this->assertEquals(3, $round->depth);
        $this->assertEquals(7, IndividualMatch::count());
        $this->assertEquals(4, IndividualMatch::getLeafs()->count());
    }

    /** @test */
    public function it_should_seed_individual_pool(){
        factory(Competition::class, ['group_size' => 3]);
        $this->given_competitors(12);


        $seed  = new IndividualSeed();
        $seed->setup('pool');

        $round = Round::first();
        $this->assertEquals('pool', $round->type);
        $this->assertEquals(12, $round->seeds_count);
        $this->assertEquals(4, Group::count());
        $this->assertEquals(12, IndividualMatch::count());
    }

    /** @test */
    public function it_should_seed_individual_both(){
        factory(Competition::class, ['group_size' => 3]);

        $this->given_competitors(24);

        $seed  = new IndividualSeed();
        $seed->setup('both');

        $round = Round::first();
        $this->assertEquals('both', $round->type);
        $this->assertEquals(24, $round->seeds_count);
        $this->assertEquals(8, Group::count());
        $this->assertEquals(24, IndividualMatch::count());
    }

    /** @test */
    public function it_should_seed_individual_round_robin(){
        $this->given_competitors(12);

        $seed  = new IndividualSeed();
        $seed->setup('round-robin');

        $this->assertEquals(1, Group::count());
        $this->assertEquals(12, Group::first()->group_size);
        $this->assertEquals(66, IndividualMatch::count()); // Pool Jeder gegen jeden
    }


}