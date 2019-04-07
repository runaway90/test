<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190306132801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE allegro_order (id INT AUTO_INCREMENT NOT NULL, allegro_events_id INT DEFAULT NULL, buyer_id INT NOT NULL, checkout_form_id VARCHAR(255) NOT NULL, INDEX IDX_F9133730E7DB4661 (allegro_events_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allegro_order ADD CONSTRAINT FK_F9133730E7DB4661 FOREIGN KEY (allegro_events_id) REFERENCES allegro_event (id)');
        $this->addSql('ALTER TABLE allegro_offer ADD allegro_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE allegro_offer ADD CONSTRAINT FK_25EC23965693C1B3 FOREIGN KEY (allegro_order_id) REFERENCES allegro_order (id)');
        $this->addSql('CREATE INDEX IDX_25EC23965693C1B3 ON allegro_offer (allegro_order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE allegro_offer DROP FOREIGN KEY FK_25EC23965693C1B3');
        $this->addSql('DROP TABLE allegro_order');
        $this->addSql('DROP INDEX IDX_25EC23965693C1B3 ON allegro_offer');
        $this->addSql('ALTER TABLE allegro_offer DROP allegro_order_id');
    }
}
