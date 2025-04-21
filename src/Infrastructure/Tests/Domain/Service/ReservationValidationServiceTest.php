<?php

namespace App\Tests\Domain\Service;

use App\Domain\Exception\InvalidReservationDateException;
use App\Domain\Exception\InvalidReservationEmailException;
use App\Domain\Exception\OverlappingReservationException;
use App\Domain\Repository\ReservationRepositoryInterface;
use App\Domain\Service\ReservationValidationService;
use App\Domain\Service\TimezoneServiceInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReservationValidationServiceTest extends TestCase
{
    private ReservationValidationService $service;
    private ReservationRepositoryInterface|MockObject $reservationRepository;
    private TimezoneServiceInterface|MockObject $timezoneService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->reservationRepository = $this->createMock(ReservationRepositoryInterface::class);
        $this->timezoneService = $this->createMock(TimezoneServiceInterface::class);

        $this->service = new ReservationValidationService(
            $this->reservationRepository,
            $this->timezoneService
        );
    }

    /**
     * @test
     * @throws InvalidReservationDateException
     */
    public function shouldValidateCorrectDates(): void
    {
        // Arrange
        $now = new \DateTime('2024-01-01 12:00:00');
        $startTime = new \DateTimeImmutable('2024-01-01 13:00:00');
        $endTime = new \DateTimeImmutable('2024-01-01 15:00:00');

        $this->timezoneService
            ->method('getCurrentDateTime')
            ->willReturn($now);

        // Act
        $this->service->validateDates($startTime, $endTime);

        // Assert
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionForPastStartDate(): void
    {
        // Arrange
        $now = new \DateTime('2024-01-01 12:00:00');
        $startTime = new \DateTimeImmutable('2024-01-01 11:00:00');
        $endTime = new \DateTimeImmutable('2024-01-01 13:00:00');

        $this->timezoneService
            ->method('getCurrentDateTime')
            ->willReturn($now);

        // Assert
        $this->expectException(InvalidReservationDateException::class);
        $this->expectExceptionMessage("La date de début doit être ultérieure à la date actuelle");

        // Act
        $this->service->validateDates($startTime, $endTime);
    }

    /**
     * @test
     * @throws OverlappingReservationException
     */
    public function shouldValidateNoOverlap(): void
    {
        // Arrange
        $carId = 1;
        $startTime = new \DateTimeImmutable('2024-01-01 13:00:00');
        $endTime = new \DateTimeImmutable('2024-01-01 15:00:00');

        $this->reservationRepository
            ->method('findOverlappingReservations')
            ->with($carId, $startTime, $endTime)
            ->willReturn(false);

        // Act
        $this->service->validateNoOverlap($carId, $startTime, $endTime);

        // Assert
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionForOverlappingReservation(): void
    {
        // Arrange
        $carId = 1;
        $startTime = new \DateTimeImmutable('2024-01-01 13:00:00');
        $endTime = new \DateTimeImmutable('2024-01-01 15:00:00');

        $this->reservationRepository
            ->method('findOverlappingReservations')
            ->with($carId, $startTime, $endTime)
            ->willReturn(true);

        // Assert
        $this->expectException(OverlappingReservationException::class);
        $this->expectExceptionMessage(sprintf(
            "La voiture est déjà réservée pour la période du %s au %s",
            $startTime->format('d/m/Y H:i'),
            $endTime->format('d/m/Y H:i')
        ));

        // Act
        $this->service->validateNoOverlap($carId, $startTime, $endTime);
    }

    /**
     * @test
     * @throws InvalidReservationEmailException
     */
    public function shouldValidateCorrectEmail(): void
    {
        // Arrange
        $validEmail = 'test@example.com';

        // Act
        $this->service->validateEmail($validEmail);

        // Assert
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionForInvalidEmail(): void
    {
        // Arrange
        $invalidEmail = 'invalid-email';

        // Assert
        $this->expectException(InvalidReservationEmailException::class);
        $this->expectExceptionMessage("L'adresse email n'est pas valide");

        // Act
        $this->service->validateEmail($invalidEmail);
    }
}
