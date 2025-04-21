<?php

namespace App\Domain\Entity;

class Car
{
    /**
     * @var ?int $id
     */
    private ?int $id = null;

    /**
     * @var string $immatriculation
     */
    private string $immatriculation;

    /**
     * @var string $marque
     */
    private string $marque;

    /**
     * @var string $modele
     */
    private string $modele;

    /**
     * @param string $immatriculation
     * @param string $marque
     * @param string $modele
     */
    public function __construct(string $immatriculation, string $marque, string $modele)
    {
        $this->immatriculation = $immatriculation;
        $this->marque = $marque;
        $this->modele = $modele;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImmatriculation(): string
    {
        return $this->immatriculation;
    }

    /**
     * @return string
     */
    public function getMarque(): string
    {
        return $this->marque;
    }

    /**
     * @return string
     */
    public function getModele(): string
    {
        return $this->modele;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'immatriculation' => $this->getImmatriculation(),
            'marque' => $this->getMarque(),
            'modele' => $this->getModele(),
        ];
    }
}
