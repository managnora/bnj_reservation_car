<?php

namespace App\Infrastructure;

use App\Domain\Service\TimezoneServiceInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    private ?TimezoneServiceInterface $timezoneService = null;

    public function process(ContainerBuilder $container): void
    {
        if ($container->hasDefinition(TimezoneServiceInterface::class)) {
            $container->getDefinition(TimezoneServiceInterface::class)->setPublic(true);
        }
    }

    public function setTimezoneService(TimezoneServiceInterface $timezoneService): void
    {
        $this->timezoneService = $timezoneService;
    }

    public function boot(): void
    {
        parent::boot();

        try {
            if ($this->timezoneService === null) {
                $this->timezoneService = $this->container->get(TimezoneServiceInterface::class);
            }

            date_default_timezone_set($this->timezoneService->getDefaultTimezone()->getName());
        } catch (\Exception $e) {
            date_default_timezone_set('UTC');
            error_log('Failed to set timezone, falling back to UTC: ' . $e->getMessage());
        }
    }
}
