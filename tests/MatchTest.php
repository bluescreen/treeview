<?php


use ITaikai\Competitor;
use ITaikai\Match;
use ITaikai\Referee;

class MatchTest extends TestCase {

    use DatabaseTransactions, Shared;

    /** @test */
    public function it_should_get_competitor_names()
    {
        factory(Competitor::class, ['id' => 1, 'name' => 'Red']);
        factory(Competitor::class, ['id' => 2, 'name' => 'White']);
        Competitor::participateAll();
        /** @var Match $match */
        $match = factory(Match::class, ['red_id' => 1, 'white_id' => 2]);

        $this->assertEquals('Red', $match->getRedName());
        $this->assertEquals('White', $match->getWhiteName());
    }

    /** @test */
    public function it_should_assign_referees()
    {
        factory(Referee::class, [], 3);
        /** @var Match $match */
        $match = factory(Match::class);
        $match->assignReferees(Referee::limit(3)->get());
        $this->assertCount(3, $match->referees);
    }

    /** @test */
    public function it_should_create_match()
    {
        $match = Match::add($white = 3, $red = 4, $group_id = 6, "TestMatch", 1, $depth = 2, $nextPos = 4);
        $this->assertEquals(3, $match->white_id);
        $this->assertEquals(4, $match->red_id);
        $this->assertEquals(6, $match->group_id);
        $this->assertEquals(1, $match->parent_id);
        $this->assertEquals(2, $match->depth);
        $this->assertEquals(4, $match->next_pos);
        $this->assertEquals("TestMatch", $match->title);
        $this->assertEquals(['men' => 0, 'kote' => 0, 'do' => 0, 'tsuki' => 0, 'penalty' => 0, 'hansoku' => 0], $match->score_white);
        $this->assertEquals(['men' => 0, 'kote' => 0, 'do' => 0, 'tsuki' => 0, 'penalty' => 0, 'hansoku' => 0], $match->score_red);
    }

    /** @test */
    public function it_should_create_team_sub_match()
    {
        $match = Match::createTeamSubMatch(3, 4, 1);
        $this->assertEquals(3, $match->white_id);
        $this->assertEquals(4, $match->red_id);
        $this->assertEquals(1, $match->team_matches_id);
    }

    /** @test */
    public function it_should_assign_competitors_to_empty_match()
    {
        $this->given_competitors(2);

        $match = Match::addEmpty("TestMatch");
        $match->assignCompetitors(1, 2);

        $this->assertInstanceOf(Competitor::class, $match->red()->first());
        $this->assertInstanceOf(Competitor::class, $match->white()->first());
    }

    /** @test */
    public function it_should_find_all_tree_matches()
    {
        factory(Match::class, [], 3);
        $matches = Match::getTreeMatches();
        $this->assertCount(3, $matches);
    }

    /** @test */
    public function it_should_find_only_leaf_matches()
    {
        $rootMatch = factory(Match::class)->makeRoot();
        factory(Match::class)->setParentId($rootMatch->id)->save();
        factory(Match::class)->setParentId($rootMatch->id)->save();
        Match::fixTree();
        $this->assertCount(2, Match::getLeafs());
    }

    /** @test */
    public function it_should_find_winner_paths()
    {
        $this->given_competitors(4);
        $rootMatch = factory(Match::class, ['id' => 1, 'winner_id' => 1])->makeRoot();
        factory(Match::class, ['id' => 2, 'winner_id' => 3])->setParentId($rootMatch->id)->save();
        factory(Match::class, ['id' => 3, 'winner_id' => 1])->setParentId($rootMatch->id)->save();
        Match::fixTree();

        $path = Match::getTreePaths();
        $this->assertEquals([0, 2], $path[1]);
        $this->assertEquals([1], $path[3]);
    }
}