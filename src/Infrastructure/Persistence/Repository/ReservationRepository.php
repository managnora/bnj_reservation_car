<?php

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Entity\Reservation as DomainReservation;
use App\Domain\Repository\ReservationRepositoryInterface;
use App\Infrastructure\Persistence\Entity\Car;
use App\Infrastructure\Persistence\Entity\Reservation as PersistenceReservation;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class ReservationRepository implements ReservationRepositoryInterface
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @param int $carId
     * @param DateTimeInterface $startTime
     * @param DateTimeInterface $endTime
     * @return bool
     */
    public function findOverlappingReservations(int $carId, DateTimeInterface $startTime, DateTimeInterface $endTime): bool
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('COUNT(r.id)')
           ->from(PersistenceReservation::class, 'r')
           ->join('r.car', 'c')
           ->where('c.id = :carId')
           ->andWhere('r.startTime < :endTime')
           ->andWhere('r.endTime > :startTime');

        $qb->setParameter('carId', $carId)
           ->setParameter('startTime', $startTime)
           ->setParameter('endTime', $endTime);


        return (int)$qb->getQuery()->getSingleScalarResult() > 0;
    }

    /**
     * @return array|DomainReservation[]
     */
    public function findAll(): array
    {
        $persistenceReservations = $this->entityManager
            ->getRepository(PersistenceReservation::class)
            ->findAll();

        return array_map(
            fn(PersistenceReservation $reservation) => $reservation->toDomain(),
            $persistenceReservations
        );
    }

    /**
     * @param DomainReservation $reservation
     * @return DomainReservation
     */
    public function save(DomainReservation $reservation): DomainReservation
    {
        $existingCar = $this->entityManager
            ->getRepository(Car::class)
            ->find($reservation->getCar()->getId());

        if (!$existingCar) {
            throw new \RuntimeException('Car not found');
        }

        $persistenceReservation = (new PersistenceReservation);
        $persistenceReservation->setUserEmail($reservation->getUserEmail());
        $persistenceReservation->setStartTime($reservation->getStartTime());
        $persistenceReservation->setEndTime($reservation->getEndTime());
        $persistenceReservation->setCar($existingCar);

        $this->entityManager->persist($persistenceReservation);
        $this->entityManager->flush();
        return $persistenceReservation->toDomain();
    }
}
