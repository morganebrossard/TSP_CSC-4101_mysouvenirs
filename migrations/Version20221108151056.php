<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221108151056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tableau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, créateur_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, publié BOOLEAN NOT NULL, CONSTRAINT FK_C6744DB16C83C3CE FOREIGN KEY (créateur_id) REFERENCES member (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C6744DB16C83C3CE ON tableau (créateur_id)');
        $this->addSql('CREATE TABLE tableau_souvenir (tableau_id INTEGER NOT NULL, souvenir_id INTEGER NOT NULL, PRIMARY KEY(tableau_id, souvenir_id), CONSTRAINT FK_4F2A92FDB062D5BC FOREIGN KEY (tableau_id) REFERENCES tableau (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4F2A92FDDBC4A80F FOREIGN KEY (souvenir_id) REFERENCES souvenir (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4F2A92FDB062D5BC ON tableau_souvenir (tableau_id)');
        $this->addSql('CREATE INDEX IDX_4F2A92FDDBC4A80F ON tableau_souvenir (souvenir_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__souvenir AS SELECT id, album_id, title, date, place, context, description FROM souvenir');
        $this->addSql('DROP TABLE souvenir');
        $this->addSql('CREATE TABLE souvenir (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, album_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, date DATE NOT NULL, place VARCHAR(255) DEFAULT NULL, context VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_53FBDDEE1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO souvenir (id, album_id, title, date, place, context, description) SELECT id, album_id, title, date, place, context, description FROM __temp__souvenir');
        $this->addSql('DROP TABLE __temp__souvenir');
        $this->addSql('CREATE INDEX IDX_53FBDDEE1137ABCF ON souvenir (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tableau');
        $this->addSql('DROP TABLE tableau_souvenir');
        $this->addSql('ALTER TABLE souvenir ADD COLUMN type VARCHAR(255) DEFAULT NULL');
    }
}
