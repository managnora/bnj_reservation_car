<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421074627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE cars (id INT NOT NULL, immatriculation VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE reservations (id INT NOT NULL, car_id INT NOT NULL, user_email VARCHAR(255) NOT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4DA239C3C6F69F ON reservations (car_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN reservations.start_time IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN reservations.end_time IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations ADD CONSTRAINT FK_4DA239C3C6F69F FOREIGN KEY (car_id) REFERENCES cars (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservations DROP CONSTRAINT FK_4DA239C3C6F69F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE cars
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE reservations
        SQL);
    }
}
