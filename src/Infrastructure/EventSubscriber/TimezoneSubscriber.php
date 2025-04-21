<?php

namespace App\Infrastructure\EventSubscriber;

use App\Infrastructure\Service\TimezoneService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class TimezoneSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly TimezoneService $timezoneService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['setTimezone', 15],
        ];
    }

    public function setTimezone(): void
    {
        try {
            date_default_timezone_set($this->timezoneService->getDefaultTimezone()->getName());
        } catch (\Exception $e) {
            date_default_timezone_set('UTC');
            error_log('Failed to set timezone, falling back to UTC: ' . $e->getMessage());
        }
    }
}