<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106135109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depense (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, moto_id INT NOT NULL, depense_type_id INT NOT NULL, montant DOUBLE PRECISION NOT NULL, km_parcouru DOUBLE PRECISION DEFAULT NULL, essence_consomme DOUBLE PRECISION DEFAULT NULL, conso_moyenne DOUBLE PRECISION DEFAULT NULL, essence_type VARCHAR(255) DEFAULT NULL, essence_price DOUBLE PRECISION DEFAULT NULL, date DATE NOT NULL, INDEX IDX_34059757A76ED395 (user_id), INDEX IDX_3405975778B8F2AC (moto_id), INDEX IDX_34059757C0E45076 (depense_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depense_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entretien (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, moto_id INT NOT NULL, graissage INT DEFAULT NULL, lavage TINYINT(1) DEFAULT NULL, pression_av DOUBLE PRECISION DEFAULT NULL, pression_ar DOUBLE PRECISION DEFAULT NULL, date DATE NOT NULL, INDEX IDX_2B58D6DAA76ED395 (user_id), INDEX IDX_2B58D6DA78B8F2AC (moto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moto (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, INDEX IDX_3DDDBCE4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_3405975778B8F2AC FOREIGN KEY (moto_id) REFERENCES moto (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757C0E45076 FOREIGN KEY (depense_type_id) REFERENCES depense_type (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA78B8F2AC FOREIGN KEY (moto_id) REFERENCES moto (id)');
        $this->addSql('ALTER TABLE moto ADD CONSTRAINT FK_3DDDBCE4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757A76ED395');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_3405975778B8F2AC');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757C0E45076');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DAA76ED395');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA78B8F2AC');
        $this->addSql('ALTER TABLE moto DROP FOREIGN KEY FK_3DDDBCE4A76ED395');
        $this->addSql('DROP TABLE depense');
        $this->addSql('DROP TABLE depense_type');
        $this->addSql('DROP TABLE entretien');
        $this->addSql('DROP TABLE moto');
        $this->addSql('DROP TABLE user');
    }
}
