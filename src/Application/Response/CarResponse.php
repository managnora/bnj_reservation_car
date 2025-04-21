<?php

namespace App\Application\Response;

class CarResponse
{
    public function __construct(
        private readonly int $id,
        private readonly string $immatriculation,
        private readonly string $marque,
        private readonly string $modele,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'immatriculation' => $this->immatriculation,
            'marque' => $this->marque,
            'modele' => $this->modele,
        ];
    }
}
