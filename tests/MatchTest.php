<?php


use ITaikai\Competitor;
use ITaikai\Match;

class MatchTest extends TestCase {

    use DatabaseTransactions,Shared;

    /** @test */
    public function it_should_get_competitor_names(){
        factory(Competitor::class, ['id' => 1,'name' => 'Red']);
        factory(Competitor::class, ['id' => 2,'name' => 'White']);
        Competitor::participateAll();
        /** @var Match $match */
        $match = factory(Match::class, ['red_id' => 1, 'white_id' => 2]);

        $this->assertEquals('Red' , $match->getRedName());
        $this->assertEquals('White' , $match->getWhiteName());
    }

    /** @test */
    public function it_should_assign_competitors_to_empty_match(){
        $this->given_competitors(2);

        $match = Match::addEmpty("TestMatch");
        $match->assignCompetitors(1,2);

        $this->assertInstanceOf(Competitor::class, $match->red()->first());
        $this->assertInstanceOf(Competitor::class, $match->white()->first());
    }

    /** @test */
    public function it_should_find_all_tree_matches(){
        factory(Match::class, [], 3);
        $matches = Match::getTreeMatches();
        $this->assertCount(3, $matches);
    }

    /** @test */
    public function it_should_find_only_leaf_matches(){
        $rootMatch = factory(Match::class)->makeRoot();
        factory(Match::class)->setParentId($rootMatch->id)->save();
        factory(Match::class)->setParentId($rootMatch->id)->save();
        Match::fixTree();
        $this->assertCount(2, Match::getLeafs());
    }

    /** @test */
    public function it_should_find_winner_paths(){
        $this->given_competitors(4);
        $rootMatch = factory(Match::class, ['id' => 1, 'winner_id' => 1])->makeRoot();
        factory(Match::class, ['id' => 2, 'winner_id' => 3])->setParentId($rootMatch->id)->save();
        factory(Match::class, ['id' => 3, 'winner_id' => 1])->setParentId($rootMatch->id)->save();
        Match::fixTree();

        $path = Match::getTreePaths();
        $this->assertEquals(["1","3"], $path[1]);
        $this->assertEquals(["2"], $path[3]);
    }
}