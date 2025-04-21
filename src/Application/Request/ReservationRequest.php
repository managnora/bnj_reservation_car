<?php

namespace App\Application\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ReservationRequest
{
    public function __construct(
        #[Assert\NotBlank(message: "Veuillez spécifier l'identifiant de la voiture")]
        #[Assert\Type(type: "integer", message: "L'identifiant de la voiture doit être un nombre entier valide")]
        #[Assert\Positive(message: "L'identifiant de la voiture doit être un nombre positif")]
        public readonly int $carId,

        #[Assert\NotBlank(message: "Veuillez spécifier une adresse email")]
        #[Assert\Email(message: "L'adresse email '{{ value }} n'est pas valide")]
        public readonly string $userEmail,

        #[Assert\NotBlank(message: "Veuillez spécifier une date de début de réservation")]
        #[Assert\Type(type: \DateTime::class, message: "La date de début n'est pas dans un format valide")]
        #[Assert\GreaterThan(
            'now',
            message: "La date de début doit être ultérieure à la date actuelle"
        )]
        public readonly ?\DateTime $startTime,

        #[Assert\NotBlank(message: "Veuillez spécifier une date de fin de réservation")]
        #[Assert\Type(type: \DateTime::class, message: "La date de fin n'est pas dans un format valide")]
        #[Assert\Expression(
            "this.endTime > this.startTime",
            message: "La date de fin doit être ultérieure à la date de début de réservation"
        )]
        #[Assert\GreaterThan(
            'now',
            message: "La date de fin doit être ultérieure à la date actuelle"
        )]
        public readonly ?\DateTime $endTime,
    ) {}
}
