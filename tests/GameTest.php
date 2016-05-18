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

	public function testOne()
	{

		$this->rollMany(20, 1);
		$this->assertEquals(20, $this->g->score());
	}


	//http://slocums.homestead.com/gamescore.html
	public function testRandomGame(){

		$this->g->roll(10);
	    $this->g->roll(7);
	    $this->g->roll(3);
	    $this->g->roll(9);
	    $this->g->roll(0);
	    $this->g->roll(10);
	    $this->g->roll(0);
	    $this->g->roll(8);
	    $this->g->roll(8);
	    $this->g->roll(2);
	    $this->g->roll(0);
	    $this->g->roll(6);
		$this->g->roll(10);
	    $this->g->roll(10);
	    $this->g->roll(10);
		$this->g->roll(8);
	    $this->g->roll(1);

		$this->assertEquals(167, $this->g->score());


	}
	
    public function testPerfectGame()
    {
        $this->rollMany(12, 10);
        $this->assertEquals(300, $this->g->score());
    }


	public function testTenStrikes()
	{
		$this->rollMany(10, 10);
		$this->rollMany(2, 0);
		$this->assertEquals(270, $this->g->score());
	}




}
