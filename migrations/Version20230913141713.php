<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230913141713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE sortie CHANGE duree duree INT UNSIGNED DEFAULT NULL, CHANGE nb_inscriptions_max nb_inscriptions_max INT UNSIGNED DEFAULT NULL, CHANGE etat etat VARCHAR(50) NOT NULL, CHANGE nb_inscrits nb_inscrits INT UNSIGNED NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE sortie CHANGE duree duree DOUBLE PRECISION DEFAULT NULL, CHANGE nb_inscriptions_max nb_inscriptions_max INT DEFAULT NULL, CHANGE nb_inscrits nb_inscrits INT NOT NULL, CHANGE etat etat INT NOT NULL');
    }
}
