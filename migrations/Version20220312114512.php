<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220312114512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, etat VARCHAR(255) NOT NULL, paiement VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit_panier ADD commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE produit_panier ADD CONSTRAINT FK_D39EC6C882EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_D39EC6C882EA2E54 ON produit_panier (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit_panier DROP FOREIGN KEY FK_D39EC6C882EA2E54');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP INDEX IDX_D39EC6C882EA2E54 ON produit_panier');
        $this->addSql('ALTER TABLE produit_panier DROP commande_id');
    }
}
