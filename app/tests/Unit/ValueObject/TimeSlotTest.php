<?php

namespace App\Tests\Unit\ValueObject;

use App\Exception\TimeSlotOutOfRangeException;
use App\ValueObject\TimeSlot;
use PHPUnit\Framework\TestCase;

class TimeSlotTest extends TestCase
{
    public function test_there_is_error_for_value_out_of_range(): void
    {
        $this->expectException(TimeSlotOutOfRangeException::class);

        new TimeSlot(123, false);
    }

    public function test_it_serializes_properly_for_valid_value(): void
    {
        $timeSlot = new TimeSlot(47, true);

        self::assertEquals(
            [
                'from' => '23:30',
                'to' => '23:59',
                'available' => true
            ],
            $timeSlot->serialize()
        );
    }
}
