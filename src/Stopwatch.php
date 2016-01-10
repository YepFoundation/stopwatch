<?php
namespace Yep\Stopwatch;

class Stopwatch {
	/** @var null|string */
	protected $name;

	/** @var Event[] */
	protected $events = [];

	public function __construct($name = null) {
		$this->name = $name;
	}

	/**
	 * Returns the stopwatch name
	 *
	 * @return null|string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Starts the new lap in the event
	 *
	 * @param string      $name
	 * @param null|string $group
	 * @return Event
	 */
	public function start($name, $group = null) {
		if (empty($this->events[$name])) {
			$this->events[$name] = new Event($name, $group);
		}

		return $this->getEvent($name)->startLap();
	}

	/**
	 * Stops the latest lap in the event
	 *
	 * @param string $name
	 * @return Event
	 */
	public function stop($name) {
		return $this->getEvent($name)->stopLap();
	}

	/**
	 * Stops the latest lap in the event and star new one
	 *
	 * @param string $name
	 * @return Event
	 */
	public function lap($name) {
		return $this->getEvent($name)->stopLap()->startLap();
	}

	/**
	 * Returns exists
	 *
	 * @param string $name
	 * @return Event
	 * @throws StopwatchEventDoesntExistException
	 */
	public function getEvent($name) {
		if (empty($this->events[$name])) {
			throw new StopwatchEventDoesntExistException(sprintf('Event with name "%s" doesn\'t exist.', $name));
		}

		return $this->events[$name];
	}

	/**
	 * Returns the stopwatch events
	 *
	 * @return Event[]
	 */
	public function getEvents() {
		return $this->events;
	}
}
