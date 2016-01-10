<?php
namespace Yep\Stopwatch;

class Manager {
	/** @var Stopwatch[] */
	protected $stopwatches;

	/**
	 * Adds the stopwatch into the manager
	 *
	 * @param Stopwatch $stopwatch
	 * @throws StopwatchAlreadyExistsException
	 */
	public function addStopwatch(Stopwatch $stopwatch) {
		$name = $stopwatch->getName();

		if (isset($this->stopwatches[$name])) {
			throw new StopwatchAlreadyExistsException(sprintf('The stopwatch with name "%s" already exists.', $name));
		}

		$this->stopwatches[$name] = $stopwatch;
	}

	/**
	 * Returns the stopwatch from the manager if exists (or creates and adds new one)
	 *
	 * @param string $name
	 * @param bool   $must_exist
	 * @return Stopwatch
	 * @throws StopwatchDoesntExistException
	 * @throws StopwatchAlreadyExistsException
	 */
	public function getStopwatch($name, $must_exist = true) {
		if (!isset($this->stopwatches[$name])) {
			if ($must_exist) {
				throw new StopwatchDoesntExistException(sprintf('The stopwatch with name "%s" doesn\'t exist.', $name));
			}

			$this->addStopwatch(new Stopwatch($name));
		}

		return $this->stopwatches[$name];
	}

	/**
	 * Retuns true, if the stopwatch exists or false if not
	 *
	 * @param string $name
	 * @return bool
	 */
	public function stopwatchExists($name) {
		return isset($this->stopwatches[$name]);
	}

	/**
	 * Removes the stopwatch from the manager
	 *
	 * @param string $name
	 */
	public function removeStopwatch($name) {
		unset($this->stopwatches[$name]);
	}

	/**
	 * Returns all stopwatches
	 *
	 * @return Stopwatch[]
	 */
	public function getStopwatches() {
		return $this->stopwatches;
	}
}
