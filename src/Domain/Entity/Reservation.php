<?php

namespace App\Domain\Entity;


use DateTimeImmutable;

class Reservation
{
    /**
     * @var ?int $id
     */
    private ?int $id = null;

    /**
     * @var Car $car
     */
    private Car $car;

    /**
     * @var string $userEmail
     */
    private string $userEmail;

    /**
     * @var DateTimeImmutable $startTime
     */
    private DateTimeImmutable $startTime;

    /**
     * @var DateTimeImmutable $endTime
     */
    private DateTimeImmutable $endTime;

    /**
     * @param Car $car
     * @param string $userEmail
     * @param DateTimeImmutable $startTime
     * @param DateTimeImmutable $endTime
     */
    public function __construct(Car $car, string $userEmail, DateTimeImmutable $startTime, DateTimeImmutable $endTime)
    {
        $this->car = $car;
        $this->userEmail = $userEmail;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    /**
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getStartTime(): DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getEndTime(): DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'userEmail' => $this->getUserEmail(),
            'startTime' => $this->getStartTime()->format('Y-m-d\TH:i:sP'),
            'endTime' => $this->getEndTime()->format('Y-m-d\TH:i:sP'),
            'car' => $this->getCar()->toArray(),
        ];
    }
}
