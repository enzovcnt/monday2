<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929133813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id SERIAL NOT NULL, name TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE chose ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chose ADD CONSTRAINT FK_9C63169312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9C63169312469DE2 ON chose (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE chose DROP CONSTRAINT FK_9C63169312469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_9C63169312469DE2');
        $this->addSql('ALTER TABLE chose DROP category_id');
    }
}
