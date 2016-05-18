<?php
/**
 * Created by PhpStorm.
 * User: rafa
 */
require_once dirname(__DIR__)."/vendor/autoload.php";
require_once dirname(__DIR__)."/src/Game.php";



class GameTest extends PHPUnit_Framework_TestCase
{

    private $g;

    public function __construct()
    {
        $this->g = new Game();
    }

    public function scoresPin()
    {
        for ($i=0; $i<20; $i++) {
            $this->g->roll(1);
        }

        $this->assertEquals(20, $this->g->score());
    }

	private function rollMany($n, $pins)
	{
		for ($i = 0; $i < $n; $i++) {
			$this->g->roll($pins);
		}
	}

    public function testAllOnes()
    {
        $this->rollMany(20, 1);
        $this->assertEquals(20, $this->g->score());
    }


	private function rollStrike()
	{
		$this->g->roll(_NUM_PINS_STRIKE_);
	}

	private function rollSpare()
	{
		$this->g->roll(5);
		$this->g->roll(5);
	}


	public function  testSumBonuses(){
		$this->rollSpare();
		$this->g->roll(3);
		$this->rollMany(17, 0);
		$this->assertEquals(3, $this->g->sumBonuses());
	}

    public function testOneSpare()
    {
        $this->rollSpare();
        $this->g->roll(3);
        $this->rollMany(17, 0);
        $this->assertEquals(16, $this->g->score());
    }

    public function testOneStrike()
    {
        $this->rollStrike();
        $this->g->roll(3);
        $this->g->roll(4);
        $this->rollMany(16, 0);
        $this->assertEquals(24, $this->g->score());
    }

    public function testPerfectGame()
    {
        $this->rollMany(12, 10);
        $this->assertEquals(300, $this->g->score());
    }





}
