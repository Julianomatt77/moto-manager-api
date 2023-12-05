<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205125309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense ADD user_id INT NOT NULL, ADD moto_id INT NOT NULL');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_34059757A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depense ADD CONSTRAINT FK_3405975778B8F2AC FOREIGN KEY (moto_id) REFERENCES moto (id)');
        $this->addSql('CREATE INDEX IDX_34059757A76ED395 ON depense (user_id)');
        $this->addSql('CREATE INDEX IDX_3405975778B8F2AC ON depense (moto_id)');
//        $this->addSql('ALTER TABLE moto ADD CONSTRAINT FK_3DDDBCE4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
//        $this->addSql('CREATE INDEX IDX_3DDDBCE4A76ED395 ON moto (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_34059757A76ED395');
        $this->addSql('ALTER TABLE depense DROP FOREIGN KEY FK_3405975778B8F2AC');
        $this->addSql('DROP INDEX IDX_34059757A76ED395 ON depense');
        $this->addSql('DROP INDEX IDX_3405975778B8F2AC ON depense');
        $this->addSql('ALTER TABLE depense DROP user_id, DROP moto_id');
//        $this->addSql('ALTER TABLE moto DROP FOREIGN KEY FK_3DDDBCE4A76ED395');
//        $this->addSql('DROP INDEX IDX_3DDDBCE4A76ED395 ON moto');
    }
}
