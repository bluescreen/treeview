<?php


namespace ITaikai;


use Exception;

/**
 * Class Fightable
 * @package ITaikai
 *
 * @mixin Match
 */
trait Fightable {
    protected $messages = [];
    protected $history  = [];
    protected $chances  = [];
    protected $hits     = [];
    protected $model    = null;

    private $score_fields = ['white' => 'score_white', 'red' => 'score_red'];

    public function updateFight($time){
        $this->time = $time;
        $this->save();
    }

    public function start($m)
    {
        $name1 = $m['White']['alias'];
        $name2 = $m['Red']['alias'];

        $this->saveField('status', RUNNING);
        $this->log("\n-> $name1 vs $name2");
    }

    public function stop(&$model)
    {
        $this->model->log("\nResult: $this->points_white : $this->points_red | $this->time -> $this->winner_id");
    }

    public function simulate()
    {
        if ($this->white_id <= -1 && $m->red_id <= -1) {
            throw new Exception('Cannot simulate Match without Competitors');
        }

        $this->reset();

        $timeup         = false;
        $starttime      = time();
        $time           = 0;
        $hit_chance     = Configure::read('App.rules.chance_hit');
        $penalty_chance = Configure::read('App.rules.chance_penalty');
        $players        = ['red' => 1, 'white' => 2];
        $current_status = RUNNING;

        $this->history(__('Start simulation %d', $this->id), $time);
        $this->start($data);

        while ($time < 180) {
            $model->updateFight($time);
            $status         = $model->read("winner_id,status");
            $current_status = $status[$model->alias]['status'];
            $winner_id      = $status[$model->alias]['winner_id'];
            if ($winner_id > 0 || $current_status == Match::FINISHED) {
                break;
            }

            $player = array_rand($players);
            if ($this->randPercent($penalty_chance)) { // 10% penalty
                $this->penalty($player, $time);
            } elseif ($this->randPercent($hit_chance)) {
                $hit = $this->getRandomHit();
                $this->addPoint($player, $hit, $time);
            } else {
                //	$model->log("$time: ... ");
            }
            $this->updateFight($time);
            $time += 5;
        }

        $this->stop($model);
        $this->history('stop simulation', $time);
        $model->update();

        return true;
    }

    public function addPoint($player, $hit, $time = null)
    {
        $data = $model->read('status,score_red,score_white');

        if (in_array($data[$model->alias]['status'], [Match::FINISHED, Match::PAUSED])) {
            return false;
        }
        $score_field = $this->score_fields[$player];
        $new_score   = $data[$model->alias][$score_field];


        $new_score[$hit] = isset($new_score[$hit]) ? $new_score[$hit] + 1 : 0;
        return $this->save([$score_field => $new_score]);
    }

    public function removePoint(&$model, $player, $hit, $time = null)
    {
        if (!$model->exists()) {
            throw new Exception('Cannot update the score');
        }
        $data = $model->read('status,score_red,score_white');

        if (in_array($data[$model->alias]['status'], [Match::FINISHED, Match::PAUSED])) {
            return false;
        }
        $score_field = $this->score_fields[$player];
        $new_score   = $data[$model->alias][$score_field];
        if ($new_score[$hit] > 0) {
            $new_score[$hit]--;
        }
        $model->save([$score_field => $new_score]);
    }


    protected function getTime()
    {
        return isset($_POST['time']) ? 180 - $_POST['time'] : null;
    }

    protected function getRandomHit()
    {
        do {
            foreach ($this->chances as $hit => $chance) {
                if ($this->randPercent($chance)) {
                    return $hit;
                }
            }
        } while (1);
    }

    protected function history($msg, $time = 0, $player = null)
    {
        $data    = $this->model->read($this->model->alias . ".history");
        $history = $data[$this->model->alias]['history'];
        $text    = isset($this->messages[$msg]) ? $this->messages[$msg] : $msg;

        //	$this->model->log($time.":\t".sprintf($text,$player));
        $history[] = ['time' => $time, 'msg' => sprintf($text, $player)];
        $this->saveField('history', $history);
    }


    private function randPercent($chance = 50)
    {
        return (rand(1, 100) <= $chance);
    }

    public function hit(&$model, $hit, $player, $time)
    {
        if ($hit == "penalty") {
            if ($data[$model->alias][$player]['penalty'] % 2) {
                $hit = 'second-penalty';
            }
        }
        $this->history($hit, $time, $player);
        $this->addPoint($player, $hit, $time);
    }

    private function penalty($player, $time)
    {
        $this->history('penalty', $time, $player);
        $this->addPoint($player, 'penalty', $time);
    }

    private function saveField($field, $value){
        $this->{$field} = $value;
        $this->save();
    }
}