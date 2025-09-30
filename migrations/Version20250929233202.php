<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929233202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE joke (id SERIAL NOT NULL, joke_category_id INT NOT NULL, value TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8D8563DDD0E8CE7A ON joke (joke_category_id)');
        $this->addSql('COMMENT ON COLUMN joke.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE joke ADD CONSTRAINT FK_8D8563DDD0E8CE7A FOREIGN KEY (joke_category_id) REFERENCES joke_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE joke DROP CONSTRAINT FK_8D8563DDD0E8CE7A');
        $this->addSql('DROP TABLE joke');
    }
}
