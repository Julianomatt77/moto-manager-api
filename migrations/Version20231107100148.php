<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107100148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_3405975778B8F2AC');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757A76ED395');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757C0E45076');
        $this->addSql('DROP INDEX IDX_3405975778B8F2AC ON depense');
        $this->addSql('DROP INDEX IDX_34059757A76ED395 ON depense');
        $this->addSql('DROP INDEX IDX_34059757C0E45076 ON depense');
        $this->addSql('ALTER TABLE depense DROP user_id, DROP moto_id, DROP depense_type_id');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DA78B8F2AC');
        $this->addSql('ALTER TABLE entretien DROP FOREIGN KEY FK_2B58D6DAA76ED395');
        $this->addSql('DROP INDEX IDX_2B58D6DA78B8F2AC ON entretien');
        $this->addSql('DROP INDEX IDX_2B58D6DAA76ED395 ON entretien');
        $this->addSql('ALTER TABLE entretien DROP user_id, DROP moto_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense ADD user_id INT NOT NULL, ADD moto_id INT NOT NULL, ADD depense_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_3405975778B8F2AC FOREIGN KEY (moto_id) REFERENCES moto (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757C0E45076 FOREIGN KEY (depense_type_id) REFERENCES depense_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3405975778B8F2AC ON depense (moto_id)');
        $this->addSql('CREATE INDEX IDX_34059757A76ED395 ON depense (user_id)');
        $this->addSql('CREATE INDEX IDX_34059757C0E45076 ON depense (depense_type_id)');
        $this->addSql('ALTER TABLE entretien ADD user_id INT NOT NULL, ADD moto_id INT NOT NULL');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DA78B8F2AC FOREIGN KEY (moto_id) REFERENCES moto (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE entretien ADD CONSTRAINT FK_2B58D6DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2B58D6DA78B8F2AC ON entretien (moto_id)');
        $this->addSql('CREATE INDEX IDX_2B58D6DAA76ED395 ON entretien (user_id)');
    }
}
