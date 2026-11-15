<?php

declare(strict_types=1);

namespace G\Clock;

use Psr\Clock\ClockInterface;
use DateTimeImmutable;
use DateTimeZone;

/**
 * PSR-20 clock returning the current system time.
 */
final class SystemClock implements ClockInterface
{
    /**
     * @param DateTimeZone|null $timezone
     */
    public function __construct(
        private ?DateTimeZone $timezone = null
    ) {
        $this->timezone ??= new DateTimeZone(date_default_timezone_get());
    }

    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable(timezone: $this->timezone);
    }

    /**
     * Freezes the clock at the current time and returns a FrozenClock instance.
     *
     * @return FrozenClock
     */
    public function freeze(): FrozenClock
    {
        return new FrozenClock($this->now());
    }
}
