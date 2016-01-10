<?php

use Yep\Stopwatch\Formatter;

class FormatterTest extends PHPUnit_Framework_TestCase {
	public function testFormatTime() {
		$this->assertSame('0.100 μs', Formatter::formatTime(.0000001));
		$this->assertSame('1.000 μs', Formatter::formatTime(.000001));
		$this->assertSame('10.000 μs', Formatter::formatTime(.00001));
		$this->assertSame('100.000 μs', Formatter::formatTime(.0001));
		$this->assertSame('1.000 ms', Formatter::formatTime(.001));
		$this->assertSame('10.000 ms', Formatter::formatTime(.01));
		$this->assertSame('100.000 ms', Formatter::formatTime(.1));
		$this->assertSame('1.000 s', Formatter::formatTime(1));
		$this->assertSame('10.000 s', Formatter::formatTime(10));
		$this->assertSame('1.000 m', Formatter::formatTime(60));
		$this->assertSame('2.000 m', Formatter::formatTime(120));
	}

	public function testFormatMemory() {
		$this->assertSame('1.000 b', Formatter::formatMemory(1));
		$this->assertSame('10.000 b', Formatter::formatMemory(10));
		$this->assertSame('100.000 b', Formatter::formatMemory(100));
		$this->assertSame('1 000.000 b', Formatter::formatMemory(1000));
		$this->assertSame('1.000 kb', Formatter::formatMemory(1024));
		$this->assertSame('1.000 mb', Formatter::formatMemory(1024 * 1024));
		$this->assertSame('1.000 gb', Formatter::formatMemory(1024 * 1024 * 1024));
	}
}
