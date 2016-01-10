<?php
namespace Yep\Stopwatch;

class StopwatchLapNotStartedException extends \BadMethodCallException {
}

class StopwatchAlreadyExistsException extends \LogicException {
}

class StopwatchDoesntExistException extends \LogicException {
}

class StopwatchEventDoesntExistException extends \Exception {
}
