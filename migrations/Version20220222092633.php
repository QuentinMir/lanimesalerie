<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222092633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marque CHANGE nom nom VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE produit CHANGE id_sub_sous_categorie id_sub_sous_categorie INT NOT NULL, CHANGE id_categorie id_categorie INT NOT NULL, CHANGE id_sous_categorie id_sous_categorie INT NOT NULL, CHANGE id_marque id_marque INT NOT NULL, CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE description description TEXT NOT NULL');
        $this->addSql('ALTER TABLE souscategorie CHANGE nom nom VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE subsouscategorie CHANGE nom nom VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image CHANGE lien lien VARCHAR(250) DEFAULT NULL');
        $this->addSql('ALTER TABLE marque CHANGE nom nom VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE id_sub_sous_categorie id_sub_sous_categorie INT DEFAULT NULL, CHANGE id_categorie id_categorie INT DEFAULT NULL, CHANGE id_sous_categorie id_sous_categorie INT DEFAULT NULL, CHANGE id_marque id_marque INT DEFAULT NULL, CHANGE nom nom VARCHAR(50) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE souscategorie CHANGE nom nom VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE subsouscategorie CHANGE nom nom VARCHAR(50) DEFAULT NULL');
    }
}
