<?php


use ITaikai\Competition;
use ITaikai\DatabaseTransactions;
use ITaikai\Participant;
use ITaikai\Referee;
use ITaikai\Seed\TeamSeed;
use ITaikai\Team;
use ITaikai\TeamMatch;

class TeamSeedTest extends TestCase {

    use DatabaseTransactions, Shared;

    public function setUp()
    {
        parent::setUp();
        Competition::clear();
    }

    /** @test */
    public function it_should_seed_team_random(){
        $this->given_competitors(10);
        factory(Referee::class, [], 3);
        factory(Team::class, [], 2);
        Participant::autoassignToTeams();

        $seed  = new TeamSeed();
        $seed->setTeamSize(5);
        $seed->setup('random');

        /** @var TeamMatch $match */
        $match = TeamMatch::first();
        $this->assertNotNull($match);
        $this->assertCount(5,$match->matches);
    }


}