<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping AS ORM;
use App\ValueObject\TimeSlot;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Reservations")
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $station;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeSlot;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function __construct(
        int $station,
        TimeSlot $timeSlot,
        \DateTimeImmutable $date
    ) {
        $this->station = $station;
        $this->timeSlot = $timeSlot->value();
        $this->date = $date;
    }

    public function station(): int
    {
        return $this->station;
    }

    public function timeSlot(): TimeSlot
    {
        return new TimeSlot($this->timeSlot, false);
    }

    public function date(): \DateTimeImmutable
    {
        return $this->date;
    }
}
