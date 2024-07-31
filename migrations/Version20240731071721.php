<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240731071721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE books ADD back_id INT NOT NULL, ADD cover_id INT NOT NULL');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92E9583FF0 FOREIGN KEY (back_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE books ADD CONSTRAINT FK_4A1B2A92922726E9 FOREIGN KEY (cover_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A1B2A92E9583FF0 ON books (back_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A1B2A92922726E9 ON books (cover_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92E9583FF0');
        $this->addSql('ALTER TABLE books DROP FOREIGN KEY FK_4A1B2A92922726E9');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP INDEX UNIQ_4A1B2A92E9583FF0 ON books');
        $this->addSql('DROP INDEX UNIQ_4A1B2A92922726E9 ON books');
        $this->addSql('ALTER TABLE books DROP back_id, DROP cover_id');
    }
}
