<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106182039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, second_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, employee_code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE file (id VARCHAR(255) NOT NULL, admin_id VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C9F3610642B8210 ON file (admin_id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE file DROP CONSTRAINT FK_8C9F3610642B8210');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE file');
    }
}
