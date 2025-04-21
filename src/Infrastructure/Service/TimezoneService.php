<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\TimezoneServiceInterface;
use DateTimeInterface;
use DateTimeZone;
use DateTime;
use InvalidArgumentException;

class TimezoneService implements TimezoneServiceInterface
{
    private readonly DateTimeZone $timezone;

    public function __construct(string $defaultTimezone = 'UTC')
    {
        try {
            $this->timezone = new DateTimeZone($defaultTimezone);
        } catch (\Exception $e) {
            throw new InvalidArgumentException(
                "Invalid timezone configuration: {$defaultTimezone}",
                0,
                $e
            );
        }
    }

    public function getCurrentDateTime(): DateTime
    {
        return new DateTime('now', $this->timezone);
    }

    public function convertToAppTimezone(DateTimeInterface $dateTime): DateTime
    {
        $converted = DateTime::createFromInterface($dateTime);
        $converted->setTimezone($this->timezone);
        return $converted;
    }

    public function getDefaultTimezone(): DateTimeZone
    {
        return $this->timezone;
    }

    public function formatDateTime(DateTimeInterface $dateTime, string $format = 'Y-m-d H:i:s'): string
    {
        return $this->convertToAppTimezone($dateTime)->format($format);
    }
}
