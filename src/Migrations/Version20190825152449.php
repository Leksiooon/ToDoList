<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190825152449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_task (category_id INT NOT NULL, task_id INT NOT NULL, INDEX IDX_D9E5A16212469DE2 (category_id), INDEX IDX_D9E5A1628DB60186 (task_id), PRIMARY KEY(category_id, task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, to_do_lists_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_7B00651CD01A2D9D (to_do_lists_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, to_do_list_id INT NOT NULL, status_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_527EDB25B3AB48EB (to_do_list_id), INDEX IDX_527EDB256BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE to_do_list (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4A6048ECA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_task ADD CONSTRAINT FK_D9E5A16212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_task ADD CONSTRAINT FK_D9E5A1628DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651CD01A2D9D FOREIGN KEY (to_do_lists_id) REFERENCES to_do_list (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B3AB48EB FOREIGN KEY (to_do_list_id) REFERENCES to_do_list (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB256BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE to_do_list ADD CONSTRAINT FK_4A6048ECA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_task DROP FOREIGN KEY FK_D9E5A16212469DE2');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB256BF700BD');
        $this->addSql('ALTER TABLE category_task DROP FOREIGN KEY FK_D9E5A1628DB60186');
        $this->addSql('ALTER TABLE status DROP FOREIGN KEY FK_7B00651CD01A2D9D');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25B3AB48EB');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_task');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE to_do_list');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
