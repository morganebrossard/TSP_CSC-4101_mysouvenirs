<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111142327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE context (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_E25D857E727ACA70 FOREIGN KEY (parent_id) REFERENCES context (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E25D857E727ACA70 ON context (parent_id)');
        $this->addSql('CREATE TABLE souvenir_context (souvenir_id INTEGER NOT NULL, context_id INTEGER NOT NULL, PRIMARY KEY(souvenir_id, context_id), CONSTRAINT FK_CABD6D14DBC4A80F FOREIGN KEY (souvenir_id) REFERENCES souvenir (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CABD6D146B00C1CF FOREIGN KEY (context_id) REFERENCES context (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CABD6D14DBC4A80F ON souvenir_context (souvenir_id)');
        $this->addSql('CREATE INDEX IDX_CABD6D146B00C1CF ON souvenir_context (context_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__souvenir AS SELECT id, album_id, title, date, place, description FROM souvenir');
        $this->addSql('DROP TABLE souvenir');
        $this->addSql('CREATE TABLE souvenir (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, album_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, date DATE NOT NULL, place VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_53FBDDEE1137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO souvenir (id, album_id, title, date, place, description) SELECT id, album_id, title, date, place, description FROM __temp__souvenir');
        $this->addSql('DROP TABLE __temp__souvenir');
        $this->addSql('CREATE INDEX IDX_53FBDDEE1137ABCF ON souvenir (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE context');
        $this->addSql('DROP TABLE souvenir_context');
        $this->addSql('ALTER TABLE souvenir ADD COLUMN context VARCHAR(255) DEFAULT NULL');
    }
}
