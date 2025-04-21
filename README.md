# Car Reservation API

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- PostgreSQL 14
- Symfony CLI (optionnel)

## Installation

1. Cloner le projet
```bash
git clone https://github.com/managnora/bnj_reservation_car.git
cd bnj_reservation_car
```

2. Installer les dépendances
```bash
composer install
```

3. Configurer la base de données
   
Copier le fichier `.env` en `.env.local` et modifier les paramètres de connexion :
```bash
cp .env .env.local
```

Modifier la ligne suivante dans `.env.local` avec vos paramètres :
```
DATABASE_URL="postgresql://postgres:root@127.0.0.1:5432/reservation_car?serverVersion=16&charset=utf8"
```

4. Créer la base de données
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## Tests Unitaires

1. Créer la base de données de test
```bash
php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:migrations:migrate
```

2. Lancer les tests
```bash
php bin/phpunit
```

Pour lancer un test spécifique :
```bash
php bin/phpunit tests/Domain/UseCase/CreateReservationTest.php
```

## Tester l'API

1. Démarrer le serveur Symfony
```bash
symfony server:start
# ou
php -S localhost:8000 -t public/
```

2. Charger les fixtures (données de test)
```bash
php bin/console doctrine:fixtures:load
```

3. Endpoints disponibles

### Lister les réservations
```bash
curl -X GET http://localhost:8000/api/reservations
```

### Créer une réservation
```bash
curl -X POST http://localhost:8000/api/reservations \
  -H "Content-Type: application/json" \
  -d '{
    "carId": 1,
    "userEmail": "test@example.com",
    "startTime": "2024-02-20T10:00:00+02:00",
    "endTime": "2024-02-20T12:00:00+02:00"
  }'
```

### Exemples de réponses

#### Succès (201 Created)
```json
{
    "id": 1,
    "userEmail": "test@example.com",
    "startTime": "2024-02-20T10:00:00+02:00",
    "endTime": "2024-02-20T12:00:00+02:00",
    "car": {
        "id": 1,
        "immatriculation": "AB-123-CD",
        "marque": "Renault",
        "modele": "Clio"
    }
}
```

#### Erreur (400 Bad Request)
```json
{
    "error": "La voiture est déjà réservée pour la période du 20/02/2024 10:00 au 20/02/2024 12:00"
}
```
