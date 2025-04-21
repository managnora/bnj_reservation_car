<?php

namespace App\Domain\Exception;

class CarNotFoundException extends \Exception
{
    public function __construct(int $carId)
    {
        parent::__construct(sprintf("Aucune voiture trouvée avec l'identifiant %d", $carId));
    }
}
