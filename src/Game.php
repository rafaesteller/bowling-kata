<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 17/05/16
 * Time: 14:18
 */
require_once dirname(__DIR__)."/vendor/autoload.php";


define('_NUM_PINS_STRIKE_', 10);
define('_NUM_FRAMES_', 10);
define('_ROLLS_PER_FRAME_', 2);

class Game
{


    private $rolls;
    private $currentRoll;


    public function __construct()
    {
        $this->rolls = array();
        $this->current_roll = 0;
    }

    public function roll($pins)
    {
        $this->rolls[$this->current_roll] = $pins;
	    $this->current_roll ++;
    }

    public function score()
    {
        $score = 0;
        $roll_index = 0;

        for ($frame = 0; $frame < _NUM_FRAMES_ ; $frame++) {
            if ($this->isStrike($roll_index)) {
                $score += _NUM_PINS_STRIKE_ + $this->strikeBonus($roll_index);
	            $roll_index++;
            } elseif ($this->isSpare($roll_index)) {
                $score += _NUM_PINS_STRIKE_ + $this->spareBonus($roll_index);
	            $roll_index += 2;
            } else {
                $score += $this->sumPinsInFrame($roll_index);
	            $roll_index += 2;
            }
        }

        return $score;
    }

	private function sumPinsInFrame($roll_index)
	{
		return $this->rolls[$roll_index] + $this->rolls[$roll_index+1];
	}


	private function isStrike($roll_index)
	{
		return $this->rolls[$roll_index] == _NUM_PINS_STRIKE_;
	}

	private function strikeBonus($roll_index) {
		return $this->rolls[$roll_index+1] + $this->rolls[$roll_index+2];
	}



	private function isSpare($roll_index) {
		return $this->rolls[$roll_index] + $this->rolls[$roll_index+1] == _NUM_PINS_STRIKE_;
	}

	private function spareBonus($roll_index) {
		return $this->rolls[$roll_index+2];
	}





}
