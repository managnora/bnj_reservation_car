<?php

namespace App\Infrastructure\DataFixtures;

use App\Domain\Entity\Car as DomainCar;
use App\Infrastructure\Persistence\Entity\Car as PersistenceCar;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CarFixtures extends Fixture
{
    public const CARS_COUNT = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $marques = ['Tesla', 'Renault', 'Peugeot', 'BMW', 'Mercedes', 'Audi'];
        $modeles = [
            'Tesla' => ['Model 3', 'Model S', 'Model X', 'Model Y'],
            'Renault' => ['Zoe', 'Megane E-Tech', 'Twingo Electric'],
            'Peugeot' => ['e-208', 'e-2008', 'e-308'],
            'BMW' => ['i3', 'i4', 'iX3'],
            'Mercedes' => ['EQA', 'EQB', 'EQC', 'EQS'],
            'Audi' => ['e-tron', 'Q4 e-tron', 'Q8 e-tron'],
        ];

        for ($i = 0; $i < self::CARS_COUNT; $i++) {
            $marque = $faker->randomElement($marques);
            $modele = $faker->randomElement($modeles[$marque]);

            // Génération d'une immatriculation au format AA-123-BB
            $immatriculation = sprintf(
                '%s-%s-%s',
                strtoupper($faker->lexify('??')),
                $faker->numberBetween(100, 999),
                strtoupper($faker->lexify('??'))
            );

            $domainCar = new DomainCar(
                $immatriculation,
                $marque,
                $modele
            );

            $persistenceCar = (new PersistenceCar);
            $persistenceCar->setImmatriculation($domainCar->getImmatriculation());
            $persistenceCar->setMarque($domainCar->getMarque());
            $persistenceCar->setModele($domainCar->getModele());
            $manager->persist($persistenceCar);

            // Création d'une référence
            $this->addReference("car_$i", $persistenceCar);
        }

        $manager->flush();
    }
}
