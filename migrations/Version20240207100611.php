<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207100611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE serie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, overview LONGTEXT DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, popularity NUMERIC(10, 0) DEFAULT NULL, genres VARCHAR(255) DEFAULT NULL, vote DOUBLE PRECISION DEFAULT NULL, first_air_date DATE NOT NULL, last_air_date DATE DEFAULT NULL, backdrop VARCHAR(255) DEFAULT NULL, poster VARCHAR(255) DEFAULT NULL, tmdb_id INT DEFAULT NULL, date_created DATETIME NOT NULL, date_modified DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE serie');
    }
}
