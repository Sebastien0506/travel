<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240511172542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE destination_avion (destination_id INT NOT NULL, avion_id INT NOT NULL, INDEX IDX_72303C8B816C6140 (destination_id), INDEX IDX_72303C8B80BBB841 (avion_id), PRIMARY KEY(destination_id, avion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE destination_avion ADD CONSTRAINT FK_72303C8B816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE destination_avion ADD CONSTRAINT FK_72303C8B80BBB841 FOREIGN KEY (avion_id) REFERENCES avion (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE destination_avion DROP FOREIGN KEY FK_72303C8B816C6140');
        $this->addSql('ALTER TABLE destination_avion DROP FOREIGN KEY FK_72303C8B80BBB841');
        $this->addSql('DROP TABLE destination_avion');
    }
}
