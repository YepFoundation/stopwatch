<?php

use Yep\Reflection\ReflectionClass;
use Yep\Stopwatch\Event;
use Yep\Stopwatch\Stopwatch;

class StopwatchTest extends PHPUnit_Framework_TestCase {
	public function testStopwatch() {
		$stopwatch = new Stopwatch('event_name');

		$this->assertSame('event_name', $stopwatch->getName());

		$start_event = $stopwatch->start('name', 'group');
		$this->assertInstanceOf(Event::class, $start_event);
		$this->assertSame('name', $start_event->getName());
		$this->assertSame('group', $start_event->getGroup());

		$reflection = ReflectionClass::from($stopwatch);
		$events = $reflection->getPropertyValue('events');

		$this->assertSame($events, $stopwatch->getEvents());
		$this->assertArrayHasKey('name', $events);
		$this->assertSame($start_event, $events['name']);
		$this->assertSame($start_event, $stopwatch->getEvent('name'));
		$this->assertTrue($start_event->hasStartedLaps());
		$this->assertCount(0, $start_event->getStoppedLaps());

		$lap_event = $stopwatch->lap('name');
		$this->assertInstanceOf(Event::class, $lap_event);
		$this->assertSame($start_event, $lap_event);
		$this->assertTrue($start_event->hasStartedLaps());
		$this->assertCount(1, $start_event->getStoppedLaps());

		$stop_event = $stopwatch->stop('name');
		$this->assertInstanceOf(Event::class, $stop_event);
		$this->assertSame($start_event, $stop_event);
		$this->assertFalse($start_event->hasStartedLaps());
		$this->assertCount(2, $start_event->getStoppedLaps());
	}

	/**
	 * @expectedException \Yep\Stopwatch\StopwatchEventDoesntExistException
	 */
	public function testGetEventWithoutEventWithException() {
		$stopwatch = new Stopwatch();
		$stopwatch->stop('foo');
	}

	/**
	 * @expectedException \Yep\Stopwatch\StopwatchEventDoesntExistException
	 */
	public function testStopWithoutEventWithException() {
		$stopwatch = new Stopwatch();
		$stopwatch->stop('foo');
	}

	/**
	 * @expectedException \Yep\Stopwatch\StopwatchEventDoesntExistException
	 */
	public function testLapWithoutEventWithException() {
		$stopwatch = new Stopwatch();
		$stopwatch->stop('foo');
	}

	/**
	 * @expectedException \Yep\Stopwatch\StopwatchLapNotStartedException
	 */
	public function testStopWithEventWithException() {
		$stopwatch = new Stopwatch();
		$stopwatch->start('foo');
		$stopwatch->stop('foo');
		$stopwatch->stop('foo');
	}

	/**
	 * @expectedException \Yep\Stopwatch\StopwatchLapNotStartedException
	 */
	public function testLapWithEventWithException() {
		$stopwatch = new Stopwatch();
		$stopwatch->start('foo');
		$stopwatch->stop('foo');
		$stopwatch->lap('foo');
	}
}
