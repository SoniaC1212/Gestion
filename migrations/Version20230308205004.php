<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230308205004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidat_offre (candidat_id INT NOT NULL, offre_id INT NOT NULL, INDEX IDX_6F6BEA1D8D0EB82 (candidat_id), INDEX IDX_6F6BEA1D4CC8505A (offre_id), PRIMARY KEY(candidat_id, offre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, niveau VARCHAR(255) NOT NULL, note INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_cours (matiere_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_716A22E9F46CD258 (matiere_id), INDEX IDX_716A22E97ECF78B0 (cours_id), PRIMARY KEY(matiere_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, proprietaire VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidat_offre ADD CONSTRAINT FK_6F6BEA1D8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_offre ADD CONSTRAINT FK_6F6BEA1D4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_cours ADD CONSTRAINT FK_716A22E9F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_cours ADD CONSTRAINT FK_716A22E97ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat_offre DROP FOREIGN KEY FK_6F6BEA1D8D0EB82');
        $this->addSql('ALTER TABLE candidat_offre DROP FOREIGN KEY FK_6F6BEA1D4CC8505A');
        $this->addSql('ALTER TABLE matiere_cours DROP FOREIGN KEY FK_716A22E9F46CD258');
        $this->addSql('ALTER TABLE matiere_cours DROP FOREIGN KEY FK_716A22E97ECF78B0');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE candidat_offre');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE matiere_cours');
        $this->addSql('DROP TABLE offre');
    }
}
