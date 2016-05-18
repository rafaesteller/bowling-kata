<?php
/**
 * Created by PhpStorm.
 * User: rafa
 * Date: 17/05/16
 * Time: 14:18
 */
require_once dirname(__DIR__)."/vendor/autoload.php";

require_once __DIR__."/Frame.php";


define('_NUM_PINS_STRIKE_', 10);
define('_NUM_FRAMES_', 10);
define('_ROLLS_PER_FRAME_', 2);

class Game
{


    private $rolls;
    private $current_roll;

    private $frames;
    private $current_frame_index;


    public function __construct()
    {
        $this->rolls = array();
        $this->current_roll = 0;

        $this->frames = array();
        $this->current_frame_index = 0;
        array_push($this->frames, new Frame());
    }

    public function roll($pins)
    {
        if ($this->currentFrame()->isFull() || $this->currentFrame()->isStrike()) {
            array_push($this->frames, new Frame());
            $this->current_frame_index ++;
        }

        $this->currentFrame()->anotate($pins);
    }

    public function currentFrame()
    {
        return $this->frames[$this->current_frame_index];
    }


    public function nextFrame($index)
    {
        return $this->frames[$index +1];
    }

    public function showGame()
    {
        var_dump($this->frames);
    }


	public function score()
	{
		return $this->sumScores() + $this->sumBonuses();
	}


    public function sumScores()
    {
        $total = 0;

        foreach ($this->frames as $frame) {
            $total += $frame->score();
        }

        return $total;
    }


    public function sumBonuses()
    {
        $total = 0;

        foreach ($this->frames as $frame_index => $frame) {
            if ($frame_index == _NUM_FRAMES_ -1) {
                break;
            }
            if ($frame->isStrike()) {
                $total += $this->anotateStrikeBonus($frame_index);
            }
            if ($frame->isSpare()) {
                $total += $this->nextFrame($frame_index)->spareBonus();
            }
        }

        return $total;
    }


    //todo revisar casos en límite
    private function anotateStrikeBonus($index)
    {
        //sum next frame
        $total = $this->nextFrame($index)->score();
        //if next frame is a strike, sum also the first roll of the following roll.
        if ($this->nextFrame($index)->isStrike()) {
            $total += $this->nextFrame($index + 1)->spareBonus();
        }

        return $total;
    }


    private function sumPinsInFrame($roll_index)
    {
        return $this->rolls[$roll_index] + $this->rolls[$roll_index+1];
    }


    private function isStrike($roll_index)
    {
        return $this->rolls[$roll_index] == _NUM_PINS_STRIKE_;
    }

    private function strikeBonus($roll_index)
    {
        return $this->rolls[$roll_index+1] + $this->rolls[$roll_index+2];
    }



    private function isSpare($roll_index)
    {
        return $this->rolls[$roll_index] + $this->rolls[$roll_index+1] == _NUM_PINS_STRIKE_;
    }

    private function spareBonus($roll_index)
    {
        return $this->rolls[$roll_index+2];
    }


	//todo Remove
	public function rollMany($n, $pins)
	{
		for ($i = 0; $i < $n; $i++) {
			$this->roll($pins);
		}
	}


}



$g = new Game();


$g->roll(10);
$g->roll(3);
$g->roll(4);
$g->rollMany(16, 0);
$g->showGame();

var_dump($g->score());

/*
$g->roll(8);
$g->roll(8);
$g->roll(8);
$g->roll(8);

$g->showGame();
echo "<br>";
echo $g->sumScores();
*/