<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\Reservations;
use App\ValueObject\TimeSlot;

class ScheduleGenerator
{
    private $reservations;

    public function __construct(Reservations $reservations)
    {
        $this->reservations = $reservations;
    }

    public function generate(int $station, \DateTimeImmutable $date)
    {
        $reservations = $this->reservations->findBy([
            'station' => $station,
            'date' => $date
        ]);

        $schedule = [];
        foreach ($reservations as $reservation) {
            $timeSlot = $reservation->timeSlot();
            $schedule[$timeSlot->value()] = $timeSlot->serialize();
        }

        return $this->fillFreeSlots($schedule);
    }

    private function fillFreeSlots(array $schedule): array
    {
        for ($i = 0; $i <= 47; $i++) {
            if (!isset($schedule[$i])) {
                $schedule[$i] = (new TimeSlot($i, true))->serialize();
            }
        }

        return $schedule;
    }
}