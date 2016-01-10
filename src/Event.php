<?php
namespace Yep\Stopwatch;

class Event {
	protected $name;
	protected $group;

	/** @var int|float */
	protected $start_time = 0;

	/** @var int|float */
	protected $stop_time = 0;

	/** @var int */
	protected $memory_usage = 0;
	protected $started_laps = [];

	/** @var Lap[] */
	protected $stopped_laps = [];

	/**
	 * Event constructor
	 *
	 * @param string      $name
	 * @param null|string $group
	 */
	public function __construct($name, $group = null) {
		$this->name = $name;
		$this->group = $group;
	}

	/**
	 * Returns the event name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns the event group
	 *
	 * @return null|string
	 */
	public function getGroup() {
		return $this->group;
	}

	/**
	 * Starts new lap in the event
	 *
	 * @return Event
	 */
	public function startLap() {
		$start_time = microtime(true);

		if ($this->start_time === 0) {
			$this->start_time = $start_time;
		}

		$this->started_laps[] = $start_time;

		return $this;
	}

	/**
	 * Stops latest lap in the event
	 *
	 * @return Event
	 */
	public function stopLap() {
		if (empty($this->started_laps)) {
			throw new StopwatchLapNotStartedException('You must call startLap() before stopLap()');
		}

		$this->memory_usage = memory_get_usage(true);
		$this->stop_time = microtime(true);

		$this->stopped_laps[] = new Lap(array_pop($this->started_laps), $this->stop_time, $this->memory_usage);

		return $this;
	}

	/**
	 * Stops started laps
	 */
	public function stopStartedLaps() {
		while (!empty($this->started_laps)) {
			$this->stopLap();
		}
	}

	/**
	 * Returns true if some started lap exists, or false if not
	 *
	 * @return bool
	 */
	public function hasStartedLaps() {
		return !empty($this->started_laps);
	}

	/**
	 * Returns the start time of the first lap
	 *
	 * @return int|float Time in seconds
	 */
	public function getStartTime() {
		return $this->start_time;
	}

	/**
	 * Returns the stop time of the last stopped lap
	 *
	 * @return int|float Time in seconds
	 */
	public function getStopTime() {
		return $this->stop_time;
	}

	/**
	 * Returns the duration of all laps durations
	 *
	 * @return float|int Time in seconds
	 */
	public function getDuration() {
		return $this->stop_time - $this->start_time;
	}

	/**
	 * Returns the memory usage
	 *
	 * @return int Memory in bytes
	 */
	public function getMemoryUsage() {
		return $this->memory_usage;
	}

	/**
	 * Returns the stopped laps
	 *
	 * @return Lap[]
	 */
	public function getStoppedLaps() {
		return $this->stopped_laps;
	}

	/**
	 * Returns the lowest duration of all laps
	 *
	 * @return float|int Time in seconds
	 */
	public function getMinDuration() {
		if (empty($this->stopped_laps)) {
			return 0;
		}

		$duration = 0;

		foreach ($this->stopped_laps as $lap) {
			$lap_duration = $lap->getDuration();

			if ($duration === 0 || $lap_duration < $duration) {
				$duration = $lap_duration;
			}
		}

		return $duration;
	}

	/**
	 * Returns the highest duration of all laps
	 *
	 * @return float|int Time in seconds
	 */
	public function getMaxDuration() {
		if (empty($this->stopped_laps)) {
			return 0;
		}

		$duration = 0;

		foreach ($this->stopped_laps as $lap) {
			if (($lap_duration = $lap->getDuration()) > $duration) {
				$duration = $lap_duration;
			}
		}

		return $duration;
	}

	/**
	 * Returns the average duration of all laps
	 *
	 * @return float|int Time in seconds
	 */
	public function getAverageDuration() {
		if (empty($this->stopped_laps)) {
			return 0;
		}

		$duration = 0;
		$count = 0;

		foreach ($this->stopped_laps as $lap) {
			$duration += $lap->getDuration();
			$count++;
		}

		return $duration / $count;
	}
}
