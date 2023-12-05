<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205131646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE depense_type ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE depense_type ADD CONSTRAINT FK_C6BEDCE8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C6BEDCE8A76ED395 ON depense_type (user_id)');
        $this->addSql('ALTER TABLE entretien ADD user_id INT NOT NULL, ADD moto_id INT NOT NULL, ADD kilometrage DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA78B8F2AC FOREIGN KEY (moto_id) REFERENCES moto (id)');
        $this->addSql('CREATE INDEX IDX_2B58D6DAA76ED395 ON entretien (user_id)');
        $this->addSql('CREATE INDEX IDX_2B58D6DA78B8F2AC ON entretien (moto_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DAA76ED395');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA78B8F2AC');
        $this->addSql('DROP INDEX IDX_2B58D6DAA76ED395 ON entretien');
        $this->addSql('DROP INDEX IDX_2B58D6DA78B8F2AC ON entretien');
        $this->addSql('ALTER TABLE entretien DROP user_id, DROP moto_id, DROP kilometrage');
        $this->addSql('ALTER TABLE depense_type DROP FOREIGN KEY FK_C6BEDCE8A76ED395');
        $this->addSql('DROP INDEX IDX_C6BEDCE8A76ED395 ON depense_type');
        $this->addSql('ALTER TABLE depense_type DROP user_id');
    }
}
