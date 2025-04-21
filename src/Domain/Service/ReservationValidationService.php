<?php

namespace App\Domain\Service;

use App\Domain\Exception\InvalidReservationDateException;
use App\Domain\Exception\InvalidReservationEmailException;
use App\Domain\Exception\OverlappingReservationException;
use App\Domain\Repository\ReservationRepositoryInterface;

class ReservationValidationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly TimezoneServiceInterface $timezoneService
    ) {}

    /**
     * @throws InvalidReservationDateException
     * @throws \Exception
     */
    public function validateDates(\DateTimeInterface $startTime, \DateTimeInterface $endTime): void
    {
        $now = $this->timezoneService->getCurrentDateTime();

        ReservationRules::validateStartTime($startTime, $now);
        ReservationRules::validateEndTime($startTime, $endTime);
    }

    /**
     * @throws InvalidReservationEmailException
     */
    public function validateEmail(string $email): void
    {
        ReservationRules::validateEmail($email);
    }

    /**
     * @throws OverlappingReservationException
     */
    public function validateNoOverlap(int $carId, \DateTimeInterface $startTime, \DateTimeInterface $endTime): void
    {
        $hasOverlap = $this->reservationRepository->findOverlappingReservations(
            $carId,
            $startTime,
            $endTime
        );

        if ($hasOverlap) {
            throw new OverlappingReservationException(
                sprintf(
                    "La voiture est déjà réservée pour la période du %s au %s",
                    $startTime->format('d/m/Y H:i'),
                    $endTime->format('d/m/Y H:i')
                )
            );
        }
    }
}
