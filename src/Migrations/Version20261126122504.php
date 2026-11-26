<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261126122504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tarea AS SELECT id, titulo, descripcion, completada, fecha_limite FROM tarea');
        $this->addSql('DROP TABLE tarea');
        $this->addSql('CREATE TABLE tarea (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion CLOB DEFAULT NULL, completada BOOLEAN NOT NULL, fecha_limite DATE DEFAULT NULL, propietario_id INTEGER DEFAULT NULL, CONSTRAINT FK_3CA0536653C8D32C FOREIGN KEY (propietario_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tarea (id, titulo, descripcion, completada, fecha_limite) SELECT id, titulo, descripcion, completada, fecha_limite FROM __temp__tarea');
        $this->addSql('DROP TABLE __temp__tarea');
        $this->addSql('CREATE INDEX IDX_3CA0536653C8D32C ON tarea (propietario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tarea AS SELECT id, titulo, descripcion, completada, fecha_limite FROM tarea');
        $this->addSql('DROP TABLE tarea');
        $this->addSql('CREATE TABLE tarea (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion CLOB DEFAULT NULL, completada BOOLEAN NOT NULL, fecha_limite DATE DEFAULT NULL)');
        $this->addSql('INSERT INTO tarea (id, titulo, descripcion, completada, fecha_limite) SELECT id, titulo, descripcion, completada, fecha_limite FROM __temp__tarea');
        $this->addSql('DROP TABLE __temp__tarea');
    }
}
