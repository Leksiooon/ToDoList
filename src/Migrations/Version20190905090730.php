<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190905090730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE status ADD position INT NOT NULL, CHANGE to_do_lists_id to_do_lists_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE status DROP position, CHANGE to_do_lists_id to_do_lists_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE task CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
