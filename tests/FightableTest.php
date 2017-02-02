<?php


use ITaikai\Match;

class FightableTest extends TestCase {

    const WHITE = 1;
    const RED   = 2;

    use DatabaseTransactions;

    /** @test */
    public function it_should_calculate_penalties()
    {
        $match = $this->makeMatch(['red_id' => self::RED, 'white_id' => -1]);
        $match->hit('penalty', 'red');
        $match->hit('penalty', 'red');
        $match->updateFight();
        $this->assertEquals(1, $match->getScoreRed()['hansoku']);
    }

    /** @test */
    public function it_should_check_red_wins_by_byes()
    {
        $match = $this->makeMatch(['red_id' => self::RED, 'white_id' => -1]);
        $this->assertEquals(self::RED, $match->checkWinner());
    }

    /** @test */
    public function it_should_check_white_wins_by_byes()
    {
        $match = $this->makeMatch(['red_id' => -1, 'white_id' => self::WHITE]);
        $this->assertEquals(self::WHITE, $match->checkWinner());
    }

    /** @test */
    public function it_should_check_red_wins_by_points()
    {
        $match = $this->makeMatch(['points_white' => 0, 'points_red' => 2]);
        $this->assertEquals(self::RED, $match->checkWinner());
    }

    /** @test */
    public function it_should_check_white_wins_by_points()
    {
        $match = $this->makeMatch(['points_white' => 2, 'points_red' => 0]);
        $this->assertEquals(self::WHITE, $match->checkWinner());
    }

    /** @test */
    public function it_should_check_red_wins_by_time()
    {
        $match = $this->makeMatch(['points_white' => 0, 'points_red' => 1]);
        $this->assertEquals(self::RED, $match->checkWinner(180));
    }

    /** @test */
    public function it_should_check_white_wins_by_time()
    {
        $match = $this->makeMatch(['points_white' => 1, 'points_red' => 0]);
        $this->assertEquals(self::WHITE, $match->checkWinner(180));
    }

    /** @test */
    public function it_should_check_match_running()
    {
        $match = $this->makeMatch(['points_white' => 1, 'points_red' => 0]);
        $this->assertEquals(false, $match->checkWinner(10));
        $this->assertEquals(Match::RUNNING, $match->status);
    }

    /** @test */
    public function it_should_simulate_match()
    {
        $match = $this->makeMatch();
        $match->simulate();
        $this->assertNotEmpty($match->history);
        $this->assertNotFalse($match->winner_id);
    }

    /** @test */
    public function it_should_score_a_point()
    {
        $match = $this->makeMatch();
        $match->hit('kote', 'red', 0);

        $this->assertEquals(1, $match->getScoreRed()['kote']);
        $this->assertNotEmpty($match->history);

    }

    /** @test */
    public function it_should_score_a_penalty()
    {
        $match = $this->makeMatch();
        $match->hit('penalty', 'red', 0);

        $this->assertEquals(1, $match->getScoreRed()['penalty']);
        $this->assertNotEmpty($match->history);
    }

    /** @test */
    public function it_should_score_two_penalty()
    {
        $match = $this->makeMatch();
        $match->hit('penalty', 'red', 0);
        $match->hit('penalty', 'red', 0);

        //$this->assertEquals(1, $match->getScoreRed()['second-penalty']);
        $this->assertNotEmpty($match->history);
    }

    /**
     * @param array $overides
     * @return Match
     */
    private function makeMatch(array $overides = [])
    {
        /** @var Match $match */
        $match = factory(Match::class, $overides);
        return $match;
    }
}