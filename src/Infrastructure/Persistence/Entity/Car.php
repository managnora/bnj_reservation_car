<?php

namespace App\Infrastructure\Persistence\Entity;

use App\Domain\Entity\Car as DomainCar;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\Table(name: 'cars')]
class Car
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $immatriculation;

    #[ORM\Column(length: 255)]
    private string $marque;

    #[ORM\Column(length: 255)]
    private string $modele;

    /**
     * @return DomainCar
     */
    public function toDomain(): DomainCar
    {
        $domainCar = new DomainCar(
            $this->getImmatriculation(),
            $this->getMarque(),
            $this->getModele()
        );

        // Utilisation de Reflection pour définir l'ID privé
        $reflectionClass = new \ReflectionClass(DomainCar::class);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($domainCar, $this->getId());

        return $domainCar;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImmatriculation(): string
    {
        return $this->immatriculation;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    public function getModele(): string
    {
        return $this->modele;
    }

    public function setId(?int $id): Car
    {
        $this->id = $id;
        return $this;
    }

    public function setImmatriculation(string $immatriculation): Car
    {
        $this->immatriculation = $immatriculation;
        return $this;
    }

    public function setMarque(string $marque): Car
    {
        $this->marque = $marque;
        return $this;
    }

    public function setModele(string $modele): Car
    {
        $this->modele = $modele;
        return $this;
    }
}
