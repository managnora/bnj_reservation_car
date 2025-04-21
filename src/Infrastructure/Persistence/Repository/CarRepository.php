<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entity\Car as DomainCar;
use App\Domain\Repository\CarRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Car as PersistenceCar;
use Doctrine\ORM\EntityManagerInterface;

class CarRepository implements CarRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function find(int $id): ?DomainCar
    {
        $persistenceCar = $this->entityManager
            ->getRepository(PersistenceCar::class)
            ->find($id);

        if (!$persistenceCar) {
            return null;
        }

        return $persistenceCar->toDomain();
    }
}
