<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Reservation;
use DateTimeInterface;

interface ReservationRepositoryInterface
{
    /**
     * @return Reservation[]
     */
    public function findAll(): array;

    /**
     * @param Reservation $reservation
     * @return Reservation
     */
    public function save(Reservation $reservation): Reservation;

    /**
     * @param int $carId
     * @param DateTimeInterface $startTime
     * @param DateTimeInterface $endTime
     * @return bool
     */
    public function findOverlappingReservations(int $carId, DateTimeInterface $startTime, DateTimeInterface $endTime): bool;
}
