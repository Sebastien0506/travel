<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506215526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ville_image ADD destination_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ville_image ADD CONSTRAINT FK_4AB8EB80816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('CREATE INDEX IDX_4AB8EB80816C6140 ON ville_image (destination_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ville_image DROP FOREIGN KEY FK_4AB8EB80816C6140');
        $this->addSql('DROP INDEX IDX_4AB8EB80816C6140 ON ville_image');
        $this->addSql('ALTER TABLE ville_image DROP destination_id');
    }
}
