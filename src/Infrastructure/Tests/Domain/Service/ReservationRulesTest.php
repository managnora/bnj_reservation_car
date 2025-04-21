<?php

namespace App\Tests\Domain\Service;

use App\Domain\Exception\InvalidReservationDateException;
use App\Domain\Exception\InvalidReservationEmailException;
use App\Domain\Service\ReservationRules;
use PHPUnit\Framework\TestCase;

class ReservationRulesTest extends TestCase
{
    /**
     * @test
     * @dataProvider provideInvalidStartTimes
     */
    public function shouldThrowExceptionForInvalidStartTime(\DateTimeImmutable $startTime, \DateTimeImmutable $now): void
    {
        $this->expectException(InvalidReservationDateException::class);
        $this->expectExceptionMessage("La date de début doit être ultérieure à la date actuelle");

        ReservationRules::validateStartTime($startTime, $now);
    }

    public static function provideInvalidStartTimes(): array
    {
        $now = new \DateTimeImmutable('2024-01-01 12:00:00');

        return [
            'start_time_in_past' => [
                'startTime' => new \DateTimeImmutable('2024-01-01 11:59:59'),
                'now' => $now
            ],
            'start_time_equals_now' => [
                'startTime' => new \DateTimeImmutable('2024-01-01 12:00:00'),
                'now' => $now
            ],
        ];
    }

    /**
     * @test
     * @throws InvalidReservationDateException
     */
    public function shouldValidateCorrectStartTime(): void
    {
        $now = new \DateTimeImmutable('2024-01-01 12:00:00');
        $startTime = new \DateTimeImmutable('2024-01-01 12:00:01');

        ReservationRules::validateStartTime($startTime, $now);
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider provideInvalidEndTimes
     */
    public function shouldThrowExceptionForInvalidEndTime(
        \DateTimeImmutable $startTime,
        \DateTimeImmutable $endTime,
        string $expectedMessage
    ): void {
        $this->expectException(InvalidReservationDateException::class);
        $this->expectExceptionMessage($expectedMessage);

        ReservationRules::validateEndTime($startTime, $endTime);
    }

    public static function provideInvalidEndTimes(): array
    {
        $baseTime = new \DateTimeImmutable('2024-01-01 12:00:00');

        return [
            'end_before_start' => [
                'startTime' => $baseTime,
                'endTime' => $baseTime->modify('-1 hour'),
                'message' => "La date de fin doit être ultérieure à la date de début"
            ],
            'end_equals_start' => [
                'startTime' => $baseTime,
                'endTime' => $baseTime,
                'message' => "La date de fin doit être ultérieure à la date de début"
            ],
            'duration_too_short' => [
                'startTime' => $baseTime,
                'endTime' => $baseTime->modify('+29 minutes'),
                'message' => sprintf(
                    "La durée minimale de réservation est de %d minutes",
                    ReservationRules::MINIMUM_DURATION_MINUTES
                )
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidEndTimes
     * @throws InvalidReservationDateException
     */
    public function shouldValidateCorrectEndTime(
        \DateTimeImmutable $startTime,
        \DateTimeImmutable $endTime
    ): void {
        ReservationRules::validateEndTime($startTime, $endTime);
        $this->assertTrue(true);
    }

    public static function provideValidEndTimes(): array
    {
        $baseTime = new \DateTimeImmutable('2024-01-01 12:00:00');

        return [
            'minimum_valid_duration' => [
                'startTime' => $baseTime,
                'endTime' => $baseTime->modify('+30 minutes')
            ],
            'normal_duration' => [
                'startTime' => $baseTime,
                'endTime' => $baseTime->modify('+12 hours')
            ],
        ];
    }

    /**
     * @test
     * @dataProvider provideInvalidEmails
     */
    public function shouldThrowExceptionForInvalidEmail(string $email): void
    {
        $this->expectException(InvalidReservationEmailException::class);
        $this->expectExceptionMessage("L'adresse email n'est pas valide");

        ReservationRules::validateEmail($email);
    }

    public static function provideInvalidEmails(): array
    {
        return [
            'empty_string' => [''],
            'no_at_symbol' => ['testexample.com'],
            'no_domain' => ['test@'],
            'invalid_format' => ['test@example'],
            'multiple_at_symbols' => ['test@@example.com'],
        ];
    }

    /**
     * @test
     * @dataProvider provideValidEmails
     * @throws InvalidReservationEmailException
     */
    public function shouldValidateCorrectEmail(string $email): void
    {
        ReservationRules::validateEmail($email);
        $this->assertTrue(true);
    }

    public static function provideValidEmails(): array
    {
        return [
            'simple_email' => ['test@example.com'],
            'with_subdomain' => ['test@sub.example.com'],
            'with_numbers' => ['test123@example.com'],
            'with_dots' => ['test.name@example.com'],
            'with_plus' => ['test+tag@example.com'],
        ];
    }
}
