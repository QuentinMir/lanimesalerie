<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217131834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adresse (id_adresse INT AUTO_INCREMENT NOT NULL, nb_rue INT NOT NULL, nom_rue VARCHAR(50) NOT NULL, code_postal VARCHAR(5) NOT NULL, ville_nom VARCHAR(50) NOT NULL, complement VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id_adresse)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id_categorie INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id_commande INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_moyen_paiement INT DEFAULT NULL, date_commande DATE DEFAULT NULL, numero VARCHAR(20) DEFAULT NULL, Id_etat INT DEFAULT NULL, INDEX fk__id_poyen_paiement (id_moyen_paiement), INDEX fk__id_etat (Id_etat), INDEX fk__id_user (id_user), PRIMARY KEY(id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id_commentaire INT AUTO_INCREMENT NOT NULL, id_produit INT DEFAULT NULL, auteur VARCHAR(100) NOT NULL, contenu VARCHAR(500) NOT NULL, note INT NOT NULL, INDEX fk__id_produit_comment (id_produit), PRIMARY KEY(id_commentaire)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat (id_etat INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_etat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id_image INT AUTO_INCREMENT NOT NULL, id_produit INT DEFAULT NULL, lien VARCHAR(50) DEFAULT NULL, INDEX IDX_C53D045FF7384557 (id_produit), PRIMARY KEY(id_image)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id_marque INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_marque)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moyenpaiement (id_moyen_paiement INT AUTO_INCREMENT NOT NULL, type VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_moyen_paiement)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, id_sub_sous_categorie INT DEFAULT NULL, id_categorie INT DEFAULT NULL, id_sous_categorie INT DEFAULT NULL, id_marque INT DEFAULT NULL, nom VARCHAR(50) DEFAULT NULL, description TEXT DEFAULT NULL, prix_ht NUMERIC(15, 2) NOT NULL, INDEX fk__id_souscategorie (id_sous_categorie), INDEX fk__id_marque (id_marque), INDEX fk__id_categorie (id_categorie), INDEX fk__id_subsouscategorie (id_sub_sous_categorie), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenir (id_produit INT NOT NULL, id_commande INT NOT NULL, INDEX IDX_3C914DFDF7384557 (id_produit), INDEX IDX_3C914DFD3E314AE8 (id_commande), PRIMARY KEY(id_produit, id_commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_panier (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_D39EC6C8F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id_role INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_role)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE souscategorie (id_sous_categorie INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_sous_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subsouscategorie (id_sub_sous_categorie INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id_sub_sous_categorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id_user INT AUTO_INCREMENT NOT NULL, id_adresse INT DEFAULT NULL, id_role INT DEFAULT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, est_homme TINYINT(1) NOT NULL, mdp VARCHAR(50) NOT NULL, date_naissance DATE NOT NULL, date_inscription DATE NOT NULL, INDEX fk__id_adresse (id_adresse), INDEX fk__id_role (id_role), PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D1157D2FC FOREIGN KEY (Id_etat) REFERENCES etat (id_etat)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id_user)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD022AAD4 FOREIGN KEY (id_moyen_paiement) REFERENCES moyenpaiement (id_moyen_paiement)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27B0F2DDC0 FOREIGN KEY (id_sub_sous_categorie) REFERENCES subsouscategorie (id_sub_sous_categorie)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC276F12807D FOREIGN KEY (id_sous_categorie) REFERENCES souscategorie (id_sous_categorie)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC277C582423 FOREIGN KEY (id_marque) REFERENCES marque (id_marque)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFD3E314AE8 FOREIGN KEY (id_commande) REFERENCES commande (id_commande)');
        $this->addSql('ALTER TABLE produit_panier ADD CONSTRAINT FK_D39EC6C8F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B31DC2A166 FOREIGN KEY (id_adresse) REFERENCES adresse (id_adresse)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3DC499668 FOREIGN KEY (id_role) REFERENCES role (id_role)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B31DC2A166');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C9486A13');
        $this->addSql('ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFD3E314AE8');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D1157D2FC');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC277C582423');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD022AAD4');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF7384557');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF7384557');
        $this->addSql('ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFDF7384557');
        $this->addSql('ALTER TABLE produit_panier DROP FOREIGN KEY FK_D39EC6C8F347EFB');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3DC499668');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC276F12807D');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27B0F2DDC0');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('DROP TABLE adresse');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE moyenpaiement');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE contenir');
        $this->addSql('DROP TABLE produit_panier');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE souscategorie');
        $this->addSql('DROP TABLE subsouscategorie');
        $this->addSql('DROP TABLE utilisateur');
    }
}
