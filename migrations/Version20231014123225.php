<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231014123225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom_categorie VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE espace (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, nom_espace VARCHAR(50) NOT NULL, taille DOUBLE PRECISION NOT NULL, wifi TINYINT(1) NOT NULL, nb_places INT DEFAULT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_6AB096DBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, espace_id INT DEFAULT NULL, lien_image VARCHAR(50) DEFAULT NULL, alt_image VARCHAR(50) DEFAULT NULL, INDEX IDX_C53D045FB6885C6C (espace_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, espace_id INT NOT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, telephone INT NOT NULL, nb_personnes INT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, prix_total DOUBLE PRECISION NOT NULL, options JSON DEFAULT NULL, note DOUBLE PRECISION DEFAULT NULL, avis LONGTEXT DEFAULT NULL, email VARCHAR(100) NOT NULL, adresse_facturation VARCHAR(255) DEFAULT NULL, facture VARCHAR(255) NOT NULL, INDEX IDX_42C84955B6885C6C (espace_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, adresse VARCHAR(255) DEFAULT NULL, cp VARCHAR(10) DEFAULT NULL, ville VARCHAR(50) DEFAULT NULL, pays VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE espace ADD CONSTRAINT FK_6AB096DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB6885C6C FOREIGN KEY (espace_id) REFERENCES espace (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B6885C6C FOREIGN KEY (espace_id) REFERENCES espace (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE espace DROP FOREIGN KEY FK_6AB096DBCF5E72D');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB6885C6C');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B6885C6C');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE espace');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
