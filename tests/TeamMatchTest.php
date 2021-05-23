<?php


use ITaikai\IndividualMatch;
use ITaikai\Participant;
use ITaikai\Team;
use ITaikai\TeamMatch;

class TeamMatchTest extends TestCase {

    use Shared;

    /** @test */
    public function it_should_create_team_match_with_submatches(){
        $this->given_competitors(10);
        factory(Team::class, [], 2);
        Participant::autoassignToTeams();

        $teamMatch = TeamMatch::addEmpty("TestMatch", 1,1,1);

        $this->assertEquals(1, TeamMatch::count());
        $this->assertEquals(5, IndividualMatch::count());
        $this->assertEquals(1, $teamMatch->parent_id);
        $this->assertEquals(1, $teamMatch->depth);
        $this->assertEquals(1, $teamMatch->next_pos);
    }
}