<?php


use ITaikai\Competition;
use ITaikai\Group;
use ITaikai\Participant;
use ITaikai\Team;

class TeamTest extends TestCase {
    use Shared;

    public function setUp():void
    {
        parent::setUp();
        Competition::clear();
    }

    /** @test */
    public function it_should_assign_participants_to_team(){
        $this->given_competitors(3);
        /** @var Team $team */
        $team = factory(Team::class);
        $team->assignParticipants(Participant::get());
        $this->assertCount(3, $team->getTeamMembers());
    }

    /** @test */
    public function it_should_assign_team_to_group(){
        $this->given_competitors(3);
        factory(Group::class);
        /** @var Team $team */
        $team = factory(Team::class);
        $team->assignToGroup(1,1);
        $this->assertCount(1, $team->group()->get());
    }
}