<?php

use Yep\Stopwatch\Lap;

class LapTest extends PHPUnit_Framework_TestCase {
	public function testLap() {
		$lap = new Lap(1, 2, 3);

		$this->assertSame(1, $lap->getStartTime());
		$this->assertSame(2, $lap->getStopTime());
		$this->assertSame(1, $lap->getDuration());
		$this->assertSame(3, $lap->getMemoryUsage());
	}
}
