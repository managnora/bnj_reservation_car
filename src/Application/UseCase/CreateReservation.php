<?php

namespace App\Application\UseCase;

use App\Application\Request\ReservationRequest;
use App\Domain\Entity\Reservation;
use App\Domain\Exception\CarNotFoundException;
use App\Domain\Exception\InvalidReservationDateException;
use App\Domain\Exception\InvalidReservationEmailException;
use App\Domain\Exception\OverlappingReservationException;
use App\Domain\Repository\CarRepositoryInterface;
use App\Domain\Repository\ReservationRepositoryInterface;
use App\Domain\Service\ReservationValidationService;

class CreateReservation
{
    /**
     * @param CarRepositoryInterface $carRepository
     * @param ReservationRepositoryInterface $reservationRepository
     * @param ReservationValidationService $validationService
     */
    public function __construct(
        private readonly CarRepositoryInterface $carRepository,
        private readonly ReservationRepositoryInterface $reservationRepository,
        private readonly ReservationValidationService $validationService
    ) {}

    /**
     * @throws InvalidReservationDateException
     * @throws OverlappingReservationException|CarNotFoundException
     * @throws InvalidReservationEmailException
     */
    public function execute(ReservationRequest $request): Reservation
    {
        // Validation de l'email
        $this->validationService->validateEmail($request->userEmail);

        // Validation des dates
        $this->validationService->validateDates($request->startTime, $request->endTime);

        // Récupération de la voiture
        $car = $this->carRepository->find($request->carId);
        if (!$car) {
            throw new CarNotFoundException($request->carId);
        }

        // Validation du chevauchement
        $this->validationService->validateNoOverlap(
            $request->carId,
            $request->startTime,
            $request->endTime
        );

        // Création de la réservation
        $reservation = new Reservation(
            $car,
            $request->userEmail,
            \DateTimeImmutable::createFromMutable($request->startTime),
            \DateTimeImmutable::createFromMutable($request->endTime)
        );

        // Sauvegarde
        return $this->reservationRepository->save($reservation);
    }
}
