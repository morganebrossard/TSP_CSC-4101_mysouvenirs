<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113234637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE souvenir ADD COLUMN image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE souvenir ADD COLUMN image_updated_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__souvenir AS SELECT id, album_id, title, date, place, description FROM souvenir');
        $this->addSql('DROP TABLE souvenir');
        $this->addSql('CREATE TABLE souvenir (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, album_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, date DATE NOT NULL, place VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_53FBDDEE1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO souvenir (id, album_id, title, date, place, description) SELECT id, album_id, title, date, place, description FROM __temp__souvenir');
        $this->addSql('DROP TABLE __temp__souvenir');
        $this->addSql('CREATE INDEX IDX_53FBDDEE1137ABCF ON souvenir (album_id)');
    }
}
