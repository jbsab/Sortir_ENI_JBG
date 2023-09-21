<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230921105649 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE filters_sorties (id INT AUTO_INCREMENT NOT NULL, campus LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', nom_sortie VARCHAR(100) DEFAULT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, organisateur TINYINT(1) DEFAULT NULL, inscrit TINYINT(1) DEFAULT NULL, pas_inscrit TINYINT(1) DEFAULT NULL, passees TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sortie ADD phpDocumentor\\Reflection\\Types\\Boolean TINYINT(1) NOT NULL, ADD Doctrine\\DBAL\\Types\\TextType VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE filters_sorties');
        $this->addSql('ALTER TABLE sortie DROP phpDocumentor\\Reflection\\Types\\Boolean, DROP Doctrine\\DBAL\\Types\\TextType');
    }
}
