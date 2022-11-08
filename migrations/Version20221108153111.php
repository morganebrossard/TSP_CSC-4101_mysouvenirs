<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221108153111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tableau AS SELECT id, créateur_id, description, publié FROM tableau');
        $this->addSql('DROP TABLE tableau');
        $this->addSql('CREATE TABLE tableau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, createur_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, publie BOOLEAN NOT NULL, CONSTRAINT FK_C6744DB173A201E5 FOREIGN KEY (createur_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tableau (id, createur_id, description, publie) SELECT id, créateur_id, description, publié FROM __temp__tableau');
        $this->addSql('DROP TABLE __temp__tableau');
        $this->addSql('CREATE INDEX IDX_C6744DB173A201E5 ON tableau (createur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tableau AS SELECT id, createur_id, description, publie FROM tableau');
        $this->addSql('DROP TABLE tableau');
        $this->addSql('CREATE TABLE tableau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, créateur_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, publié BOOLEAN NOT NULL, CONSTRAINT FK_C6744DB16C83C3CE FOREIGN KEY (créateur_id) REFERENCES member (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tableau (id, créateur_id, description, publié) SELECT id, createur_id, description, publie FROM __temp__tableau');
        $this->addSql('DROP TABLE __temp__tableau');
        $this->addSql('CREATE INDEX IDX_C6744DB16C83C3CE ON tableau (créateur_id)');
    }
}
