<?php
namespace Yep\Stopwatch;

class Formatter {
	/**
	 * Returns formated memory
	 *
	 * @param float $size Memory in bytes
	 * @return string
	 */
	public static function formatMemory($size) {
		$unit = ['b', 'kb', 'mb', 'gb'];
		$i = 0;

		if ($size > 0) {
			$i = floor(log($size, 1024));
			$divisor = pow(1024, $i);
			$size = $size / $divisor;
		}

		return number_format($size, 3, '.', ' ') . ' ' . $unit[(int)$i];
	}

	/**
	 * Returns formated time
	 *
	 * @param float $s Time in seconds
	 * @return string
	 */
	public static function formatTime($s) {
		if ($s >= 60) {
			$time = $s / 60;
			$unit = 'm';
		}
		elseif ($s >= 1) {
			$time = $s;
			$unit = 's';
		}
		elseif (($ms = $s * 1000) >= 1) {
			$time = $ms;
			$unit = 'ms';
		}
		else {
			$time = $ms * 1000;
			$unit = 'μs';
		}

		return number_format($time, 3, '.', ' ') . " $unit";
	}
}
