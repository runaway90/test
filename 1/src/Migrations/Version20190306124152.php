<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190306124152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE allegro_offer DROP FOREIGN KEY FK_25EC2396AAFB4400');
        $this->addSql('DROP INDEX IDX_25EC2396AAFB4400 ON allegro_offer');
        $this->addSql('ALTER TABLE allegro_offer DROP allegro_event_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE allegro_offer ADD allegro_event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE allegro_offer ADD CONSTRAINT FK_25EC2396AAFB4400 FOREIGN KEY (allegro_event_id) REFERENCES allegro_event (id)');
        $this->addSql('CREATE INDEX IDX_25EC2396AAFB4400 ON allegro_offer (allegro_event_id)');
    }
}
