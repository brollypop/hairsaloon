<?php

namespace App\Tests\Unit\Service;

use App\Entity\Reservation;
use App\Repository\Reservations;
use App\Service\ScheduleGenerator;
use App\ValueObject\TimeSlot;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ScheduleGeneratorTest extends TestCase
{
    private const STATION = 1;
    private const DATE = '2020-01-10';

    /** @var Reservations|MockObject */
    private $reservations;
    /** @var ScheduleGenerator */
    private $scheduleGenerator;

    protected function setUp(): void
    {
        $this->reservations = $this->createMock(Reservations::class);
        $this->scheduleGenerator = new ScheduleGenerator($this->reservations);
    }

    public function test_schedule_generation(): void
    {
        $this->reservations
            ->method('findBy')
            ->willReturn([
                new Reservation(
                    self::STATION,
                    new TimeSlot(10, false),
                    new \DateTimeImmutable(self::DATE)
                )
            ]);
        $schedule = $this->scheduleGenerator->generate(self::STATION, new \DateTimeImmutable(self::DATE));

        self::assertCount(48, $schedule);
        self::assertEquals(
            ['from' => '05:00', 'to' => '05:29', 'available' => false],
            $schedule[10]
        );
    }
}
