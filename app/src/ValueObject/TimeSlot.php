<?php declare(strict_types=1);

namespace App\ValueObject;

use App\Exception\TimeSlotOutOfRangeException;

class TimeSlot
{
    private $value;
    private $available;

    public function __construct(int $value, bool $available)
    {
        if ($value < 0 || $value > 47) {
            throw new TimeSlotOutOfRangeException('Time slot must be between 0 and 47');
        }

        $this->value = $value;
        $this->available = $available;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function serialize(): array
    {
        $date = new \DateTimeImmutable('2000-01-01 00:00:00');
        $from = $date->add(new \DateInterval('PT' . $this->value * 30 . 'M'));
        $to = $from->add(new \DateInterval('PT29M'));

        return [
            'from' => $from->format('H:i'),
            'to' => $to->format('H:i'),
            'available' => $this->available
        ];
    }
}
