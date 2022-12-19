<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219181852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_etablissement (user_id INT NOT NULL, etablissement_id INT NOT NULL, INDEX IDX_CE73F47CA76ED395 (user_id), INDEX IDX_CE73F47CFF631228 (etablissement_id), PRIMARY KEY(user_id, etablissement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_etablissement ADD CONSTRAINT FK_CE73F47CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_etablissement ADD CONSTRAINT FK_CE73F47CFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE pseudo pseudo VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_etablissement DROP FOREIGN KEY FK_CE73F47CA76ED395');
        $this->addSql('ALTER TABLE user_etablissement DROP FOREIGN KEY FK_CE73F47CFF631228');
        $this->addSql('DROP TABLE user_etablissement');
        $this->addSql('ALTER TABLE user CHANGE pseudo pseudo VARCHAR(255) DEFAULT NULL');
    }
}
