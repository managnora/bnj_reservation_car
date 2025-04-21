<?php

namespace App\Application\Response;

class ReservationResponse
{
    public function __construct(
        private readonly int $id,
        private readonly string $userEmail,
        private readonly string $startTime,
        private readonly string $endTime,
        private readonly CarResponse $car
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'userEmail' => $this->userEmail,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'car' => $this->car->toArray(),
        ];
    }
}
