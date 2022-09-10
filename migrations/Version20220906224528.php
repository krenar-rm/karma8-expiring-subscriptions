<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906224528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE emails (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, checked BOOLEAN NOT NULL, valid BOOLEAN DEFAULT NULL)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email_id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, confirmed BOOLEAN NOT NULL, CONSTRAINT FK_1483A5E9A832C1C9 FOREIGN KEY (email_id) REFERENCES emails (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9A832C1C9 ON users (email_id)');
        $this->addSql('CREATE TABLE users_subscription (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, validts INTEGER DEFAULT NULL, CONSTRAINT FK_F08242DFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F08242DFA76ED395 ON users_subscription (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F08242DF6113BDD ON users_subscription (validts)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE emails');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_subscription');
    }
}
