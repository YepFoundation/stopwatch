<?php
namespace Yep\Stopwatch;

class Lap {
	/** @var int|float */
	protected $start_time;

	/** @var int|float */
	protected $stop_time;

	/** @var int */
	protected $memory_usage;

	public function __construct($start_time, $stop_time, $memory_usage) {
		$this->start_time = $start_time;
		$this->stop_time = $stop_time;
		$this->memory_usage = $memory_usage;
	}

	/**
	 * Returns the start time of this lap
	 *
	 * @return int|float
	 */
	public function getStartTime() {
		return $this->start_time;
	}

	/**
	 * Returns the stop time of this lap
	 *
	 * @return int|float
	 */
	public function getStopTime() {
		return $this->stop_time;
	}

	/**
	 * Returns the duration of this lap
	 *
	 * @return int|float
	 */
	public function getDuration() {
		return $this->stop_time - $this->start_time;
	}

	/**
	 * Returns the memory usage of this lap
	 *
	 * @return int
	 */
	public function getMemoryUsage() {
		return $this->memory_usage;
	}
}
