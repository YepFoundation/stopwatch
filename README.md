[![Build Status](https://travis-ci.org/YepFoundation/stopwatch.svg?branch=master)](https://travis-ci.org/YepFoundation/stopwatch)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/YepFoundation/stopwatch/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/stopwatch/?branch=master)
[![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/YepFoundation/stopwatch/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/YepFoundation/stopwatch/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/yep/stopwatch/v/stable)](https://packagist.org/packages/yep/stopwatch)
[![Total Downloads](https://poser.pugx.org/yep/stopwatch/downloads)](https://packagist.org/packages/yep/stopwatch)
[![License](https://poser.pugx.org/yep/stopwatch/license)](https://github.com/YepFoundation/stopwatch/blob/master/LICENSE.md)

# Stopwatch ([docs](http://yepfoundation.github.io/stopwatch))

## Packagist
Stopwatch are available on [Packagist.org](https://packagist.org/packages/yep/stopwatch),
just add the dependency to your composer.json.

```json
{
  "require" : {
    "yep/stopwatch": "1.*"
  }
}
```

or run Composer command:

```php
php composer.phar require yep/stopwatch
```

## Usage
Stopwatch allows you to measure how long it takes to execute a certain parts of code.

You can use `start`, `stop` and `lap` like in real world.

```php
<?php
use Yep\Stopwatch\Stopwatch;
use Yep\Stopwatch\Formatter;

require __DIR__ . '/vendor/autoload.php';

$stopwatch = new Stopwatch();

// Start lap
$stopwatch->start('event_name');

// ... some code

// Stop latest lap and start new one
$stopwatch->lap('event_name');

// ... some code

// Stop latest lap
$event = $stopwatch->stop('event_name');

// Results are in seconds
echo $event->getDuration(); // 0.0014588832855225
echo $event->getAverageDuration(); // 0.00063395500183105

// Result is in bytes
echo $event->getMemoryUsage(); // 1310720

// or, when you want read duration or memory usage in a human format :)
echo Formatter::formatTime($event->getDuration()); // 1.459 ms
echo Formatter::formatTime($event->getAverageDuration()); // 633.955 μs
echo Formatter::formatMemory($event->getMemoryUsage()); // 1.250 mb
```

## Example usage
```php
<?php
use Yep\Stopwatch\Stopwatch;
use Yep\Stopwatch\Formatter;

require __DIR__ . '/vendor/autoload.php';

$stopwatch = new Stopwatch();

$stopwatch->start('data');
$data = array_merge(
	range(rand(0, 100), rand(4000, 5000)),
	range(rand(0, 100), rand(4000, 5000))
);
$stopwatch->stop('data');

$stopwatch->start('unique');
$unique = array_unique($data);
$stopwatch->stop('unique');

$stopwatch->start('flip + keys');
$flip_and_keys = array_keys(array_flip($data));
$stopwatch->stop('flip + keys');

$stopwatch->start('unique in cycle');
for ($i = 0; $i < 1000; $i++) {
	$unique = array_unique($data);
	$stopwatch->lap('unique in cycle');
}
$stopwatch->stop('unique in cycle');

$stopwatch->start('flip + keys in cycle');
for ($i = 0; $i < 1000; $i++) {
	$flip_and_keys = array_keys(array_flip($data));
	$stopwatch->lap('flip + keys in cycle');
}
$stopwatch->stop('flip + keys in cycle');

echo Formatter::formatTime($stopwatch->getEvent('data')->getDuration()); // 1.707 ms
echo Formatter::formatMemory($stopwatch->getEvent('data')->getMemoryUsage()); // 1.750 mb

echo Formatter::formatTime($stopwatch->getEvent('unique')->getDuration()); // 47.297 ms
echo Formatter::formatMemory($stopwatch->getEvent('unique')->getMemoryUsage()); // 1.750 mb

echo Formatter::formatTime($stopwatch->getEvent('flip + keys')->getDuration()); // 1.153 ms
echo Formatter::formatMemory($stopwatch->getEvent('flip + keys')->getMemoryUsage()); // 2.250 mb

echo Formatter::formatTime($stopwatch->getEvent('unique in cycle')->getDuration()); // 48.365 s
echo Formatter::formatTime($stopwatch->getEvent('unique in cycle')->getMinDuration()); // 22.173 μs
echo Formatter::formatTime($stopwatch->getEvent('unique in cycle')->getMaxDuration()); // 83.298 ms
echo Formatter::formatTime($stopwatch->getEvent('unique in cycle')->getAverageDuration()); // 48.302 ms
echo Formatter::formatMemory($stopwatch->getEvent('unique in cycle')->getMemoryUsage()); // 2.500 mb

echo Formatter::formatTime($stopwatch->getEvent('flip + keys in cycle')->getDuration()); // 1.386 s
echo Formatter::formatTime($stopwatch->getEvent('flip + keys in cycle')->getMinDuration()); // 12.159 μs
echo Formatter::formatTime($stopwatch->getEvent('flip + keys in cycle')->getMaxDuration()); // 1.878 ms
echo Formatter::formatTime($stopwatch->getEvent('flip + keys in cycle')->getAverageDuration()); // 1.375 ms
echo Formatter::formatMemory($stopwatch->getEvent('flip + keys in cycle')->getMemoryUsage()); // 3.250 mb
```

### How can I get the Stopwatch Event?
The `Stopwatch\Event` object is returned from the `start()`, `stop()`, `lap()` and `getEvent()` methods.

### How can I get the lowest Event lap duration?
```php
<?php
use Yep\Stopwatch\Stopwatch;
$stopwatch = new Stopwatch();

// ...

$event = $stopwatch->getEvent('event_name');

echo $event->getMinDuration(); // 2.6941299438477E-5
```

### How can I get the highest Event lap duration?
```php
<?php
use Yep\Stopwatch\Stopwatch;
$stopwatch = new Stopwatch();

// ...

$event = $stopwatch->getEvent('event_name');

echo $event->getMaxDuration(); // 0.00012302398681641
```

### How can I get the average Event duration?
```php
<?php
use Yep\Stopwatch\Stopwatch;
$stopwatch = new Stopwatch();

// ...

$event = $stopwatch->getEvent('event_name');

echo $event->getAverageDuration(); // 0.00063395500183105
```

### How can I get the Event memory usage?
```php
<?php
use Yep\Stopwatch\Stopwatch;
$stopwatch = new Stopwatch();

// ...

$event = $stopwatch->getEvent('event_name');

echo $event->getMemoryUsage(); // 1835008
```

### How can I get all Event laps?
```php
<?php
use Yep\Stopwatch\Stopwatch;
use Yep\Stopwatch\Formatter;

$stopwatch = new Stopwatch();

$stopwatch->start('event_name');
range(0, 100);
$stopwatch->lap('event_name');
range(0, 3000);
$stopwatch->lap('event_name');
range(0, 1000);
$event = $stopwatch->stop('event_name');

foreach ($event->getStoppedLaps() as $i => $lap) {
	echo "Lap $i = " . Formatter::formatTime($lap->getDuration()) . "\n";
}
```

will print something like this:
```
Lap 0 = 41.962 μs
Lap 1 = 364.065 μs
Lap 2 = 70.095 μs
```

## Can I work with multiple stopwatches?
Yes :)

Try stopwatch manager ;)


### How?
```php
<?php
use Yep\Stopwatch\Manager;
use Yep\Stopwatch\Stopwatch;

require __DIR__ . '/vendor/autoload.php';

$manager = new Manager();
$manager->addStopwatch(new Stopwatch('kernel'));
$manager->addStopwatch(new Stopwatch('controller'));

$manager->getStopwatch('kernel')->start('event_name');

$manager->getStopwatch('controller')->start('event_name');
$manager->getStopwatch('controller')->stop('event_name');

$manager->getStopwatch('kernel')->stop('event_name');
```

or simply

```php
<?php
use Yep\Stopwatch\Manager;

require __DIR__ . '/vendor/autoload.php';

$manager = new Manager();
$manager->getStopwatch('kernel', false)->start('event_name');

$manager->getStopwatch('controller', false)->start('event_name');
$manager->getStopwatch('controller')->stop('event_name');

$manager->getStopwatch('kernel')->stop('event_name');
```
