<?php

declare(strict_types=1);

namespace G\Clock\Tests;

use DateTimeImmutable;
use DateTimeZone;
use G\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

final class FrozenClockTest extends TestCase
{
    public function testImplementsPsrClock(): void
    {
        $this->assertInstanceOf(ClockInterface::class, new FrozenClock(new DateTimeImmutable()));
    }

    public function testNowReturnsTheGivenInstant(): void
    {
        $expectedTime = new DateTimeImmutable('2001-01-01 00:00:00');
        $clock = new FrozenClock($expectedTime);

        $this->assertSame($expectedTime, $clock->now());
    }

    public function testNowNeverMoves(): void
    {
        $clock = new FrozenClock(new DateTimeImmutable('2001-01-01 00:00:00.123456'));

        $first = $clock->now();
        usleep(2000);

        $this->assertSame($first->format('U.u'), $clock->now()->format('U.u'));
    }

    public function testNowKeepsTheTimezone(): void
    {
        $clock = new FrozenClock(new DateTimeImmutable('2001-01-01 00:00:00', new DateTimeZone('Asia/Tokyo')));

        $this->assertSame('Asia/Tokyo', $clock->now()->getTimezone()->getName());
    }
}
