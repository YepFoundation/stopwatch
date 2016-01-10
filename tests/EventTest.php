<?php

use Yep\Stopwatch\Event;
use Yep\Stopwatch\Lap;

class EventTest extends PHPUnit_Framework_TestCase {
	public function testEvent() {
		$event = new Event('name', 'group');
		$this->assertSame('name', $event->getName());
		$this->assertSame('group', $event->getGroup());

		$this->assertFalse($event->hasStartedLaps());
		$this->assertSame(0, $event->getDuration());
		$this->assertSame(0, $event->getMinDuration());
		$this->assertSame(0, $event->getMaxDuration());
		$this->assertSame(0, $event->getAverageDuration());
		$this->assertSame(0, $event->getMemoryUsage());

		$this->assertSame($event, $event->startLap());
		$this->assertGreaterThan(0, $event->getStartTime());

		$this->assertTrue($event->hasStartedLaps());

		$this->assertSame($event, $event->stopLap());
		$this->assertGreaterThan(0, $event->getStopTime());
		$this->assertSame($event->getStopTime() - $event->getStartTime(), $event->getDuration());

		$this->assertFalse($event->hasStartedLaps());

		$stopped_laps = $event->getStoppedLaps();
		$this->assertArrayHasKey(0, $stopped_laps);
		$this->assertInstanceOf(Lap::class, $stopped_laps[0]);

		$lap = $stopped_laps[0];
		$this->assertGreaterThan(0, $lap->getStartTime());
		$this->assertGreaterThan(0, $lap->getMemoryUsage());
		$this->assertGreaterThan(0, $lap->getStopTime());
		$this->assertSame($lap->getStopTime() - $lap->getStartTime(), $lap->getDuration());

		$this->assertSame($event->getStartTime(), $lap->getStartTime());
		$this->assertSame($event->getStopTime(), $lap->getStopTime());
		$this->assertSame($event->getDuration(), $lap->getDuration());
		$this->assertSame($event->getMemoryUsage(), $lap->getMemoryUsage());
	}

	public function testStopStartedLaps() {
		$event = new Event('name', 'group');

		$this->assertFalse($event->hasStartedLaps());
		$event->startLap();
		$this->assertTrue($event->hasStartedLaps());
		$event->stopStartedLaps();
		$this->assertFalse($event->hasStartedLaps());
	}

	public function testFunctionsForDurationsAndMemoryUsage() {
		$event = new Event('name', 'group');

		$event->startLap();
		usleep(100);
		$event->stopLap();

		$event->startLap();
		usleep(1000);
		$event->stopLap();

		$this->assertTrue($event->getMinDuration() < $event->getMaxDuration());
		$this->assertTrue((($event->getMinDuration() + $event->getMaxDuration()) / 2) === $event->getAverageDuration());
		$this->assertInternalType('int', $event->getMemoryUsage());
	}

	/**
	 * @expectedException Yep\Stopwatch\StopwatchLapNotStartedException
	 */
	public function testStopLapWithException() {
		$event = new Event('name', 'group');
		$event->stopLap();
	}
}
