<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261103151309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarea ADD COLUMN fecha_limite DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tarea AS SELECT id, titulo, description, completada FROM tarea');
        $this->addSql('DROP TABLE tarea');
        $this->addSql('CREATE TABLE tarea (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, completada BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO tarea (id, titulo, description, completada) SELECT id, titulo, description, completada FROM __temp__tarea');
        $this->addSql('DROP TABLE __temp__tarea');
    }
}
