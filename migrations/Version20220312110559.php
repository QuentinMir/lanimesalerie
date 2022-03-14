<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220312110559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFD3E314AE8');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D1157D2FC');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD022AAD4');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE contenir');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE moyenpaiement');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id_commande INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_moyen_paiement INT DEFAULT NULL, date_commande DATE DEFAULT NULL, numero VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, Id_etat INT DEFAULT NULL, INDEX fk__id_etat (Id_etat), INDEX fk__id_user (id_user), INDEX fk__id_poyen_paiement (id_moyen_paiement), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contenir (id_produit INT NOT NULL, id_commande INT NOT NULL, INDEX IDX_3C914DFDF7384557 (id_produit), INDEX IDX_3C914DFD3E314AE8 (id_commande), PRIMARY KEY(id_produit, id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE etat (id_etat INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id_etat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE moyenpaiement (id_moyen_paiement INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id_moyen_paiement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D1157D2FC FOREIGN KEY (Id_etat) REFERENCES etat (id_etat)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD022AAD4 FOREIGN KEY (id_moyen_paiement) REFERENCES moyenpaiement (id_moyen_paiement)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFD3E314AE8 FOREIGN KEY (id_commande) REFERENCES commande (id_commande)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
    }
}
