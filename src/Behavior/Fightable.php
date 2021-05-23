<?php


namespace ITaikai\Behavior;


use Exception;
use ITaikai\Configure;
use ITaikai\IndividualMatch;

/**
 * Class Fightable
 * @package ITaikai
 *
 * @mixin IndividualMatch
 */
trait Fightable {
    protected $history = [];
    protected $chances = [];
    protected $hits    = [];
    protected $model   = null;

    private $score_fields = ['white' => 'score_white', 'red' => 'score_red'];

    public function updateFight($time = 0)
    {
        if ($this->status == self::RUNNING) {
            $this->checkPlayers();                  // Kämpfer beide anwesend?
            $this->calcPoints();                    // Punkte zusammenrechnen
            $this->calcPenalty();                   // Strafpunkte berechnen
            $this->checkWinner($time);              // Gewinner bestimmen
        }
        $this->time = $time;
        $this->save();
        $this->log("Time: " . $time . " Winner: " . $this->winner_id . " Red: " . $this->points_red . " White: " . $this->points_white);
    }

    public function reset()
    {
        $score = ['men' => 0, 'kote' => 0, 'do' => 0, 'tsuki' => 0, 'penalty' => 0, 'hansoku' => 0];
        return $this->update([
            'score_white'  => $score,
            'score_red'    => $score,
            'points_white' => 0,
            'points_red'   => 0,
            'status'       => 0,
            'winner_id'    => 0,
            'history'      => '',
            'time'         => 0
        ]);
    }


    /** Prüf Funktionen **/

    // Anwesenheit prüfen
    private function checkPlayers()
    {
        if ($this->white_id <= 0 && $this->red_id <= 0) { // Keiner da 	 -> Kampf abbrechen
            throw new Exception(__('Cannot start Match because no competitors'));
        } elseif ($this->red_id <= 0) {                    // Rot nicht da  -> Weiß gewinnt durch Freilos
            $this->points_white = 2;
            $this->winner_id    = $this->white_id;
            $this->status       = self::FINISHED;
        } elseif ($this->white_id <= 0) {                    // Weiß nicht da -> Rot gewinnt durch Freilos
            $this->points_red = 2;
            $this->winner_id  = $this->red_id;
            $this->status     = self::FINISHED;
        }
    }

    // Gewinner bestimmmen
    public function checkWinner($time = 0, $overtime = false)
    {
        if ($this->winner_id > 0) {
            return $this->winner_id;
        }
        $winner_id = false;

        // bei Freilosen direkt weiter
        if ($this->white_id == -1) {
            $winner_id = $this->red_id;
        }
        if ($this->red_id == -1) {
            $winner_id = $this->white_id;
        }

        // Zeitabgelaufen? Dann gewinnt der mit der meisten Treffern
        if ($time >= $this->max_time) {
            if (!$overtime) {
                if ($this->points_white > $this->points_red) {
                    $winner_id = $this->white_id;
                } // Weiß gewinnt
                elseif ($this->points_red > $this->points_white) {
                    $winner_id = $this->red_id;
                }    // Rot gewinnt
                elseif ($this->points_white == $this->points_red) {
                    $winner_id = false;
                }  // Draw
            }
        } // Ansonsten gewinnt der der als erstes 2 punkte erreicht
        else {
            if ($this->points_white >= $this->max_points) {
                $winner_id = $this->white_id;
            }
            if ($this->points_red >= $this->max_points) {
                $winner_id = $this->red_id;
            }
        }

        if ($winner_id != false) {
            $this->winner_id = $winner_id;
            $this->status    = self::FINISHED;
        } else {
            $this->status = self::RUNNING;
        }
        $this->save();
        return $winner_id;
    }

    public function isFinished()
    {
        return $this->status == self::FINISHED || $this->winner_id > 0;
    }

    // Die Summe berechnen
    private function getScoreSum($score)
    {
        if (!is_array($score)) {
            return 0;
        }
        // don't count the penalty points
        if (isset($score['penalty'])) {
            unset($score['penalty']);
        }
        return array_sum($score);
    }

    // Punkte zusammenrechnen
    private function calcPoints()
    {
        $score_white = $this->getScoreWhite();
        $score_red   = $this->getScoreRed();
        // Punktsumme bilden, falls freilos punkt
        if ($this->getScoreSum($score_white) > 0) {
            $this->points_white = $this->getScoreSum($score_white);
        }
        if ($this->getScoreSum($score_red) > 0) {
            $this->points_red = $this->getScoreSum($score_red);
        }
    }

    // Strafpunkte berechnen
    // Kendo Regeln -> bei 2 Strafpunkten kriegt der Gegner einen Punkt
    public function calcPenalty()
    {
        $score_white = $this->getScoreWhite();
        $score_red   = $this->getScoreRed();

        if (isset($score_white['penalty'])) {
            if ($score_white['penalty'] < 2) {
                $score_red['hansoku'] = 0;
            } elseif ($score_white['penalty'] % 2 == 0) {
                $score_red['hansoku'] = (int)($score_white['penalty'] / 2);
            }
        }
        if (isset($score_red['penalty'])) {
            if ($score_red['penalty'] < 2) {
                $score_white['hansoku'] = 0;
            } elseif ($score_red['penalty'] % 2 == 0) {
                $score_white['hansoku'] = (int)($score_red['penalty'] / 2);
            }
        }
        $this->score_white = json_encode($score_white);
        $this->score_red   = json_encode($score_red);
        $this->save();
    }

    public function start()
    {
        $this->status = self::RUNNING;
        $this->save();
        //$this->log("\n-> $name1 vs $name2");
    }

    public function stop()
    {
        $this->status = self::FINISHED;
        $this->save();
        //$this->model->log("\nResult: $this->points_white : $this->points_red | $this->time -> $this->winner_id");
    }

    public function simulate()
    {
        if ($this->white_id <= -1 && $this->red_id <= -1) {
            $this->stop();
            return;
        }

        $this->reset();
        $time           = 0;
        $hit_chance     = Configure::read('App.rules.chance_hit');
        $penalty_chance = Configure::read('App.rules.chance_penalty');
        $chances        = $this->getHitChances();

        $players = ['red' => 1, 'white' => 2];
        $this->start();
        $this->logHistory(__('Start simulation %d', $this->id), $time);
        do {
            if ($this->isFinished()) {
                break;
            }
            $player = array_rand($players);
            if ($this->randPercent($penalty_chance)) { // 10% penalty
                $this->penalty($player, $time);
            } elseif ($this->randPercent($hit_chance)) {
                $hit = $this->getRandomHit($chances);
                $this->addPoint($player, $hit, $time);
            }
            $this->updateFight($time);

            $time += 5;
        } while ($time < 180);
        $this->stop();
        $this->updateMatch();
        return true;
    }

    public function addPoint($player, $hit, $time = null)
    {
        if (in_array($this->status, [self::FINISHED, IndividualMatch::PAUSED])) {
            return;
        }
        $score_field          = $this->score_fields[$player];
        $new_score            = ($player == 'white') ? $this->getScoreWhite() : $this->getScoreRed();
        $new_score[$hit]      = isset($new_score[$hit]) ? $new_score[$hit] + 1 : 0;
        $this->{$score_field} = json_encode($new_score);
        $this->save();

        $this->logHistory($hit, $time, $player);
    }

    public function getScoreWhite()
    {
        return is_string($this->score_white) ? json_decode($this->score_white, 1) : $this->score_white;
    }

    public function getScoreRed()
    {
        return is_string($this->score_red) ? json_decode($this->score_red, 1) : $this->score_red;
    }

    protected function getTime()
    {
        return isset($_POST['time']) ? 180 - $_POST['time'] : null;
    }


    protected function getRandomHit($chances)
    {
        do {
            foreach ($chances as $hit => $chance) {
                if ($this->randPercent($chance)) {
                    return $hit;
                }
            }
        } while (1);
    }

    protected function logHistory($msg, $time = 0, $player = null)
    {
        $messages = Configure::read('hits');

        $history   = $this->history;
        $text      = isset($messages[$msg]) ? $messages[$msg]['message'] : $msg;
        $history[] = ['time' => $time, 'msg' => sprintf($text, $player)];
        $this->update(['history' => $history]);
        $this->log(sprintf($text, $player));
    }

    public function removePoint($player, $hit, $time = null)
    {
        if (in_array($this->status, [self::FINISHED, IndividualMatch::PAUSED])) {
            return false;
        }
        $score_field = $this->score_fields[$player];
        $new_score   = $this->getScoreOfPlayer($player);
        if ($new_score[$hit] > 0) {
            $new_score[$hit]--;
        }
        $this->saveField($score_field, $new_score);
    }

    private function randPercent($chance = 50)
    {
        return (rand(1, 100) <= $chance);
    }


    public function hit($hit, $player, $time = 0)
    {
        if ($hit == "penalty") {
            if ($this->getScoreOfPlayer($player)['penalty'] % 2) {
                $hit = 'hansoku';
            }
        }
        $this->logHistory($hit, $time, $player);
        $this->addPoint($player, $hit, $time);
    }

    private function penalty($player, $time)
    {
        $this->logHistory('penalty', $time, $player);
        $this->addPoint($player, 'penalty', $time);
    }

    /**
     * @param $player
     */
    private function getScoreOfPlayer($player)
    {
        return ($player == 'white') ? $this->getScoreWhite() : $this->getScoreRed();
    }

    /**
     * @return static
     */
    private function getHitChances()
    {
        $chances = collect(Configure::read('hits'))->map(function ($hit) {
            return $hit['percent'];
        });
        unset($chances['penalty']);
        unset($chances['ahnsokue']);
        return $chances;
    }

    private function log($msg)
    {
        //fwrite(STDOUT, $msg . "\n");
    }
}
