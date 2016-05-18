<?php

require_once dirname(__DIR__)."/vendor/autoload.php";


class Frame
{

	private $first_roll;
	private $second_roll;

    public function __construct()
    {
        $this->first_roll  = null;
        $this->second_roll = null;
    }

    public function score()
    {
        return $this->first_roll + $this->second_roll;
    }

    public function spareBonus()
    {
        return $this->first_roll;
    }


    public function isStrike()
    {
        return $this->first_roll == _NUM_PINS_STRIKE_;
    }


    public function isSpare()
    {
        if (! $this->isStrike()) {
            return $this->score() == _NUM_PINS_STRIKE_;
        }
    }

    public function anotate($pins)
    {
        if ($this->first_roll === null) {
            $this->first_roll = $pins;
        } else {
            $this->second_roll = $pins;
        }
    }


    public function isFrameCompleted()
    {
        return $this->second_roll !== null;
    }

    public function isFull()
    {
	    return $this->second_roll !== null;
    }
}
