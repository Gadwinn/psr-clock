<?php

declare(strict_types=1);

namespace G\Clock;

use Psr\Clock\ClockInterface;
use DateTimeImmutable;

/**
 * PSR-20 clock that always returns the same instant.
 */
final class FrozenClock implements ClockInterface
{
    /**
     * @param DateTimeImmutable $now
     */
    public function __construct(
        private DateTimeImmutable $now
    ) {
    }

    public function now(): DateTimeImmutable
    {
        return $this->now;
    }
}
