<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225123615 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX fk__id_categorie ON produit');
        $this->addSql('DROP INDEX fk__id_souscategorie ON produit');
        $this->addSql('DROP INDEX fk__id_subsouscategorie ON produit');
        $this->addSql('ALTER TABLE produit RENAME INDEX fk__id_marque TO IDX_29A5EC277C582423');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX fk__id_categorie ON produit (id)');
        $this->addSql('CREATE INDEX fk__id_souscategorie ON produit (id)');
        $this->addSql('CREATE INDEX fk__id_subsouscategorie ON produit (id)');
        $this->addSql('ALTER TABLE produit RENAME INDEX idx_29a5ec277c582423 TO fk__id_marque');
    }
}
