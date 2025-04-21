<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Car;
use Symfony\Component\Uid\Uuid;

interface CarRepositoryInterface
{
    public function find(int $id): ?Car;
}
