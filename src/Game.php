<?php

require_once dirname(__DIR__)."/vendor/autoload.php";

require_once __DIR__."/Frame.php";


define('_NUM_PINS_STRIKE_', 10);
define('_NUM_FRAMES_', 10);


class Game
{

    private $frames;
    private $current_frame_index;


    public function __construct()
    {
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


    public function score()
    {
        return $this->sumScores() + $this->sumBonuses();
    }


    public function sumScores()
    {
        $total = 0;

        foreach ($this->frames as $k => $frame) {
            $total += $frame->score();
        }

        return $total;
    }

    public function sumBonuses()
    {
        $total = 0;

        foreach ($this->frames as $frame_index => $frame) {
            if ($this->isBonusComputable($frame_index)) {
                if ($frame->isStrike()) {
                    $total += $this->computeStrikeBonus($frame_index);
                }
                if ($frame->isSpare()) {
                    $total += $this->nextFrame($frame_index)->spareBonus();
                }
            }
        }

        return $total;
    }


    private function computeStrikeBonus($index)
    {
        $total = $this->nextFrame($index)->score();

        if ($this->nextFrame($index)->isStrike()) {
            $total += $this->nextFrame($index+1)->spareBonus();
        }

        return $total;
    }

    private function isBonusComputable($index)
    {
        return $index < _NUM_FRAMES_ -1;
    }
}
