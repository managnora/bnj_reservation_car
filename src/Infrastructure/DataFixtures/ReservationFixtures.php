<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Reservation as DomainReservation;
use App\Infrastructure\Persistence\Entity\Car;
use App\Infrastructure\Persistence\Entity\Reservation as PersistenceReservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public const RESERVATIONS_COUNT = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::RESERVATIONS_COUNT; $i++) {
            // Récupération d'une référence de voiture
            $carReference = "car_" . $faker->numberBetween(0, CarFixtures::CARS_COUNT - 1);
            /** @var \App\Infrastructure\Persistence\Entity\Car $car */
            $car = $this->getReference($carReference, Car::class);

            // Génération d'une date aléatoire dans les 30 prochains jours
            $startDate = \DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('now', '+30 days')
            );
            $endDate = $startDate->modify('+' . $faker->numberBetween(1, 8) . ' hours');

            $domainReservation = new DomainReservation(
                $car->toDomain(),
                $faker->email(),
                $startDate,
                $endDate
            );

            $persistenceReservation = (new PersistenceReservation);
            $persistenceReservation->setUserEmail($domainReservation->getUserEmail());
            $persistenceReservation->setStartTime($domainReservation->getStartTime());
            $persistenceReservation->setEndTime($domainReservation->getEndTime());
            $persistenceReservation->setCar($car);
            $manager->persist($persistenceReservation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CarFixtures::class,
        ];
    }
}
