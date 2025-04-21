<?php

namespace App\Domain\Service;

use DateTimeInterface;
use DateTimeZone;
use DateTime;

interface TimezoneServiceInterface
{
    public function getCurrentDateTime(): DateTime;
    public function convertToAppTimezone(DateTimeInterface $dateTime): DateTime;
    public function getDefaultTimezone(): DateTimeZone;
    public function formatDateTime(DateTimeInterface $dateTime, string $format = 'Y-m-d H:i:s'): string;
}