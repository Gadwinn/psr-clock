<?php

declare(strict_types=1);

namespace G\Clock\Tests;

use DateTimeImmutable;
use DateTimeZone;
use G\Clock\SystemClock;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

final class SystemClockTest extends TestCase
{
    public function testImplementsPsrClock(): void
    {
        $this->assertInstanceOf(ClockInterface::class, new SystemClock());
    }

    public function testNowIsTheCurrentTime(): void
    {
        $before = new DateTimeImmutable();
        $now = (new SystemClock())->now();
        $after = new DateTimeImmutable();

        $this->assertGreaterThanOrEqual($before, $now);
        $this->assertLessThanOrEqual($after, $now);
    }

    public function testNowReturnsANewInstanceThatMovesForward(): void
    {
        $clock = new SystemClock();

        $first = $clock->now();
        usleep(2000);
        $second = $clock->now();

        $this->assertNotSame($first, $second);
        $this->assertGreaterThan($first, $second);
    }

    public function testNowUsesTheDefaultTimezone(): void
    {
        $this->assertSame(
            date_default_timezone_get(),
            (new SystemClock())->now()->getTimezone()->getName()
        );
    }

    public function testNowUsesTheGivenTimezone(): void
    {
        $clock = new SystemClock(new DateTimeZone('Asia/Tokyo'));

        $this->assertSame('Asia/Tokyo', $clock->now()->getTimezone()->getName());
    }

    public function testFreezeCapturesTheCurrentInstant(): void
    {
        $clock = new SystemClock(new DateTimeZone('Europe/Paris'));

        $before = $clock->now();
        $frozen = $clock->freeze();
        $after = $clock->now();

        $this->assertGreaterThanOrEqual($before, $frozen->now());
        $this->assertLessThanOrEqual($after, $frozen->now());
        $this->assertSame('Europe/Paris', $frozen->now()->getTimezone()->getName());

        usleep(2000);
        $this->assertSame($frozen->now(), $frozen->now());
    }
}
