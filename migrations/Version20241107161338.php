<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107161338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_user CHANGE phone_number phone_number VARCHAR(20) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA81A6EA24A232CF ON infos_user (user_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA81A6EA6B01BC5B ON infos_user (phone_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_AA81A6EA24A232CF ON infos_user');
        $this->addSql('DROP INDEX UNIQ_AA81A6EA6B01BC5B ON infos_user');
        $this->addSql('ALTER TABLE infos_user CHANGE phone_number phone_number INT NOT NULL');
    }
}
