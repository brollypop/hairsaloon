<?php declare(strict_types=1);

namespace App\Controller\Station;

use App\Entity\Reservation;
use App\Repository\Reservations;
use App\Service\ScheduleGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ScheduleController extends AbstractController
{
    public function getScheduleAction(
        int $station,
        string $date,
        ScheduleGenerator $scheduleGenerator
    ): JsonResponse {
        return $this->json(
            ['schedule' => $scheduleGenerator->generate($station, new \DateTimeImmutable($date))]
        );
    }

    public function putScheduleAction(
        int $station,
        string $date,
        Reservations $reservations,
        Request $request
    ): JsonResponse {
        $timeSlotsToReserve = array_map(
            'intval',
            json_decode($request->getContent(), true)['time-slots']
        );

        $timeSlotsReserved = array_map(
            static function (Reservation $reservation) {
                return $reservation->timeSlot()->value();
            },
            $reservations->findBy([
                'station' => $station,
                'date' => new \DateTimeImmutable($date)
            ])
        );

        if (array_intersect($timeSlotsToReserve, $timeSlotsReserved)) {
            return $this->json(
                ['message' => 'Station is not available in selected time slot on given day'],
                Response::HTTP_CONFLICT
            );
        }
        foreach ($timeSlotsToReserve as $timeSlotToReserve) {
            $reservations->add($station, new \DateTimeImmutable($date), $timeSlotToReserve);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
