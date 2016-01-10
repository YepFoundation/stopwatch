<?php

use Yep\Reflection\ReflectionClass;
use Yep\Stopwatch\Manager;
use Yep\Stopwatch\Stopwatch;

class ManagerTest extends PHPUnit_Framework_TestCase {
	public function testManager() {
		$manager = new Manager();

		$this->assertFalse($manager->stopwatchExists('foo'));

		$reflection = ReflectionClass::from($manager);
		$reflection->setPropertyValue('stopwatches', ['foo' => true]);

		$this->assertTrue($manager->stopwatchExists('foo'));
		$this->assertSame(['foo' => true], $manager->getStopwatches());

		$manager->removeStopwatch('foo');
		$this->assertFalse($manager->stopwatchExists('foo'));

		$stopwatch = new Stopwatch();
		$manager->addStopwatch($stopwatch);
		$this->assertTrue($manager->stopwatchExists(null));
		$this->assertSame($stopwatch, $manager->getStopwatch(null));
	}

	/**
	 * @expectedException \Yep\Stopwatch\StopwatchAlreadyExistsException
	 */
	public function testAddStopwatchWithException() {
		$manager = new Manager();

		$stopwatch = new Stopwatch();
		$manager->addStopwatch($stopwatch);
		$manager->addStopwatch($stopwatch);
	}

	public function testGetStopwatch() {
		$manager = new Manager();
		$manager->getStopwatch('foo', false);
		$this->assertTrue($manager->stopwatchExists('foo'));
		$this->assertInstanceOf('\Yep\Stopwatch\Stopwatch', $manager->getStopwatch('foo'));
		$this->assertSame('foo', $manager->getStopwatch('foo')->getName());
	}

	/**
	 * @expectedException \Yep\Stopwatch\StopwatchDoesntExistException
	 */
	public function testGetStopwatchWithException() {
		$manager = new Manager();
		$manager->getStopwatch('foo', true);
	}
}
