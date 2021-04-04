<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404202047 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE user_password_changes (id INT AUTO_INCREMENT NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(40) NOT NULL, user_id INT NOT NULL, date_time VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_75BAABEF5F37A13B (token), UNIQUE INDEX UNIQ_75BAABEFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_password_changes ADD CONSTRAINT FK_75BAABEFA76ED395 FOREIGN KEY (user_id) REFERENCES user_users (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE user_password_changes');
    }
}
