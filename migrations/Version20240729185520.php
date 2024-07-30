<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240729185520 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE books (id INT AUTO_INCREMENT NOT NULL, book_categorie_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(50) NOT NULL, author VARCHAR(50) NOT NULL, isbn VARCHAR(20) NOT NULL, description LONGTEXT NOT NULL, edition VARCHAR(40) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', location VARCHAR(50) NOT NULL, pages INT NOT NULL, state VARCHAR(255) NOT NULL, exchange_type LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', INDEX IDX_4A1B2A92E5142D75 (book_categorie_id), INDEX IDX_4A1B2A92A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE infos_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, user_name VARCHAR(20) NOT NULL, phone_number INT NOT NULL, city VARCHAR(30) NOT NULL, birth_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', bio LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_AA81A6EAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite (user_id INT NOT NULL, books_id INT NOT NULL, INDEX IDX_68C58ED9A76ED395 (user_id), INDEX IDX_68C58ED97DD8AC20 (books_id), PRIMARY KEY(user_id, books_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92E5142D75 FOREIGN KEY (book_categorie_id) REFERENCES book_categorie (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE infos_user ADD CONSTRAINT FK_AA81A6EAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED97DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92E5142D75');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92A76ED395');
        $this->addSql('ALTER TABLE infos_user DROP FOREIGN KEY FK_AA81A6EAA76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED9A76ED395');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED97DD8AC20');
        $this->addSql('DROP TABLE book_categorie');
        $this->addSql('DROP TABLE books');
        $this->addSql('DROP TABLE infos_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
