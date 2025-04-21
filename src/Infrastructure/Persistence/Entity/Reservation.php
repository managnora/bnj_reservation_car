<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Entity\Reservation as DomainReservation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'reservations')]
class Reservation
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Car::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: "id")]
    private Car $car;

    #[ORM\Column(length: 255)]
    private string $userEmail;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $startTime;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $endTime;

    /**
     * @return DomainReservation
     */
    public function toDomain(): DomainReservation
    {
        $domainReservation = new DomainReservation(
            $this->getCar()->toDomain(),
            $this->getUserEmail(),
            $this->getStartTime(),
            $this->getEndTime()
        );

        // Utilisation de Reflection pour définir l'ID privé
        $reflectionClass = new \ReflectionClass(DomainReservation::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($domainReservation, $this->id);

        return $domainReservation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCar(): Car
    {
        return $this->car;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getStartTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }

    public function getEndTime(): \DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setCar(Car $car): self
    {
        $this->car = $car;
        return $this;
    }

    public function setUserEmail(string $userEmail): self
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    public function setStartTime(\DateTimeImmutable $startTime): self
    {
        $this->startTime = $startTime;
        return $this;
    }

    public function setEndTime(\DateTimeImmutable $endTime): self
    {
        $this->endTime = $endTime;
        return $this;
    }
}
