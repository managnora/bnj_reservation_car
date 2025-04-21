<?php

namespace App\Application\Mapper;

use App\Domain\Entity\Reservation;
use App\Application\Response\ReservationResponse;
use App\Application\Response\CarResponse;
use App\Domain\Service\TimezoneServiceInterface;

class ReservationMapper
{
    public function __construct(
        private readonly TimezoneServiceInterface $timezoneService
    ) {}

    public function toResponse(Reservation $reservation): ReservationResponse
    {
        $car = $reservation->getCar();

        return new ReservationResponse(
            id: $reservation->getId(),
            userEmail: $reservation->getUserEmail(),
            startTime: $this->timezoneService->formatDateTime(
                $reservation->getStartTime(),
                'Y-m-d\TH:i:sP'
            ),
            endTime: $this->timezoneService->formatDateTime(
                $reservation->getEndTime(),
                'Y-m-d\TH:i:sP'
            ),
            car: new CarResponse(
                id: $car->getId(),
                immatriculation: $car->getImmatriculation(),
                marque: $car->getMarque(),
                modele: $car->getModele(),
            )
        );
    }
}
