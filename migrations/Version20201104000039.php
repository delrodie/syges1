<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201104000039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, classe_id INT DEFAULT NULL, matricule VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenoms VARCHAR(255) NOT NULL, date_naissance VARCHAR(255) DEFAULT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) NOT NULL, nationalite VARCHAR(255) DEFAULT NULL, domicile VARCHAR(255) DEFAULT NULL, nom_parent VARCHAR(255) DEFAULT NULL, profession_parent VARCHAR(255) DEFAULT NULL, contact_parent VARCHAR(255) DEFAULT NULL, residence VARCHAR(255) DEFAULT NULL, nom_tuteur VARCHAR(255) DEFAULT NULL, profession_tuteur VARCHAR(255) DEFAULT NULL, contact_tuteur VARCHAR(255) DEFAULT NULL, residence_tuteur VARCHAR(255) DEFAULT NULL, annee VARCHAR(255) DEFAULT NULL, INDEX IDX_ECA105F78F5EA509 (classe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F78F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE eleve');
    }
}
