<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Reservation;
use App\ValueObject\TimeSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Reservations extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function add(int $station, \DateTimeImmutable $date, int $timeSlotToReserve): void
    {
        $reservation = new Reservation($station,new TimeSlot($timeSlotToReserve, true), $date);
        $this->_em->persist($reservation);
        $this->_em->flush();
    }
}
