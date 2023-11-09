<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109121017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moto ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE moto ADD CONSTRAINT FK_3DDDBCE4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3DDDBCE4A76ED395 ON moto (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE moto DROP FOREIGN KEY FK_3DDDBCE4A76ED395');
        $this->addSql('DROP INDEX IDX_3DDDBCE4A76ED395 ON moto');
        $this->addSql('ALTER TABLE moto DROP user_id');
    }
}
