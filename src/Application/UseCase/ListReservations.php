<?php

namespace App\Application\UseCase;

use App\Domain\Repository\ReservationRepositoryInterface;

class ListReservations
{
    /**
     * @var ReservationRepositoryInterface
     */
    private ReservationRepositoryInterface $reservationRepository;

    /**
     * @param ReservationRepositoryInterface $reservationRepository
     */
    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        return $this->reservationRepository->findAll();
    }
}
