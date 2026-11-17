# PSR-20 Clock

A lightweight PSR-20 (`psr/clock`) implementation providing a `SystemClock` and a `FrozenClock` for deterministic time handling.

## Tech Stack

- **PHP** 8.3+
- **[PSR-20](https://www.php-fig.org/psr/psr-20/)** — `psr/clock ^1.0`

## Installation

```bash
composer require gadwinn/psr-clock
```

## Usage

Both classes implement `Psr\Clock\ClockInterface` and are under the `G\Clock` namespace.

### SystemClock

Returns the current system time as a `DateTimeImmutable`. Accepts an optional `DateTimeZone`; defaults to `date_default_timezone_get()`.

```php
use G\Clock\SystemClock;

// Default timezone (php.ini / date_default_timezone_get())
$clock = new SystemClock();
$now = $clock->now(); // DateTimeImmutable

// Explicit timezone
$clock = new SystemClock(new DateTimeZone('Europe/Paris'));
$now = $clock->now();
```

### FrozenClock

An immutable clock that always returns the same `DateTimeImmutable`. Ideal for unit tests.

```php
use G\Clock\FrozenClock;

$fixed = new DateTimeImmutable('2025-01-01 12:00:00');
$clock = new FrozenClock($fixed);

$clock->now(); // always returns 2025-01-01 12:00:00
```

### Freezing a SystemClock

`SystemClock::freeze()` captures the current instant and returns a `FrozenClock`.

```php
use G\Clock\SystemClock;

$system = new SystemClock(new DateTimeZone('UTC'));
$frozen = $system->freeze(); // FrozenClock

$frozen->now(); // DateTimeImmutable — instant captured at freeze() call
```

### Injecting via ClockInterface

Both classes satisfy `Psr\Clock\ClockInterface`, enabling clean dependency injection:

```php
use Psr\Clock\ClockInterface;
use G\Clock\SystemClock;
use G\Clock\FrozenClock;

class Scheduler
{
    public function __construct(private readonly ClockInterface $clock) {}

    public function getNextRunTime(): DateTimeImmutable
    {
        return $this->clock->now()->modify('+1 hour');
    }
}

// Production
$scheduler = new Scheduler(new SystemClock(new DateTimeZone('UTC')));

// Tests
$scheduler = new Scheduler(new FrozenClock(new DateTimeImmutable('2025-06-01 00:00:00')));
```

## License

MIT, see [LICENSE](LICENSE).
