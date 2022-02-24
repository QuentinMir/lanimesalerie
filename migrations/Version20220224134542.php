<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220224134542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD id_adresse INT DEFAULT NULL, ADD prenom VARCHAR(50) NOT NULL, ADD nom VARCHAR(50) NOT NULL, ADD est_homme TINYINT(1) NOT NULL, ADD date_naissance DATE NOT NULL, ADD date_inscription DATE NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491DC2A166 FOREIGN KEY (id_adresse) REFERENCES adresse (id_adresse)');
        $this->addSql('CREATE INDEX IDX_8D93D6491DC2A166 ON user (id_adresse)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491DC2A166');
        $this->addSql('DROP INDEX IDX_8D93D6491DC2A166 ON user');
        $this->addSql('ALTER TABLE user DROP id_adresse, DROP prenom, DROP nom, DROP est_homme, DROP date_naissance, DROP date_inscription');
    }
}
