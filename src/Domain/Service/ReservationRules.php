<?php

namespace App\Domain\Service;

use App\Domain\Exception\InvalidReservationDateException;
use App\Domain\Exception\InvalidReservationEmailException;
use DateTimeInterface;

class ReservationRules
{
    public const MINIMUM_DURATION_MINUTES = 30;

    /**
     * @throws InvalidReservationDateException
     */
    public static function validateStartTime(DateTimeInterface $startTime, DateTimeInterface $now): void
    {
        if ($startTime <= $now) {
            throw new InvalidReservationDateException(
                "La date de début doit être ultérieure à la date actuelle"
            );
        }
    }

    /**
     * @throws InvalidReservationDateException
     */
    public static function validateEndTime(DateTimeInterface $startTime, DateTimeInterface $endTime): void
    {
        if ($endTime <= $startTime) {
            throw new InvalidReservationDateException(
                "La date de fin doit être ultérieure à la date de début"
            );
        }

        $duration = $endTime->getTimestamp() - $startTime->getTimestamp();
        $durationMinutes = $duration / 60;

        if ($durationMinutes < self::MINIMUM_DURATION_MINUTES) {
            throw new InvalidReservationDateException(
                sprintf("La durée minimale de réservation est de %d minutes", self::MINIMUM_DURATION_MINUTES)
            );
        }
    }

    /**
     * @throws InvalidReservationEmailException
     */
    public static function validateEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidReservationEmailException("L'adresse email n'est pas valide");
        }
    }
}
