<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190306121631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE allegro_offer (id INT AUTO_INCREMENT NOT NULL, allegro_event_id INT DEFAULT NULL, category_id INT NOT NULL, created_at VARCHAR(255) NOT NULL, allegro_id VARCHAR(255) NOT NULL, INDEX IDX_25EC2396AAFB4400 (allegro_event_id), UNIQUE INDEX UNIQ_25EC239612469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE allegro_event (id INT AUTO_INCREMENT NOT NULL, user_account_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, line_item_id VARCHAR(255) NOT NULL, created_at VARCHAR(255) NOT NULL, allegro_id VARCHAR(255) NOT NULL, INDEX IDX_3794AE0F3C0C9956 (user_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allegro_offer ADD CONSTRAINT FK_25EC2396AAFB4400 FOREIGN KEY (allegro_event_id) REFERENCES allegro_event (id)');
        $this->addSql('ALTER TABLE allegro_offer ADD CONSTRAINT FK_25EC239612469DE2 FOREIGN KEY (category_id) REFERENCES allegro_categories (id)');
        $this->addSql('ALTER TABLE allegro_event ADD CONSTRAINT FK_3794AE0F3C0C9956 FOREIGN KEY (user_account_id) REFERENCES allegro_user_accounts (id)');
        $this->addSql('ALTER TABLE allegro_photo ADD allegro_offer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE allegro_photo ADD CONSTRAINT FK_188D20B088CAD865 FOREIGN KEY (allegro_offer_id) REFERENCES allegro_offer (id)');
        $this->addSql('CREATE INDEX IDX_188D20B088CAD865 ON allegro_photo (allegro_offer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE allegro_photo DROP FOREIGN KEY FK_188D20B088CAD865');
        $this->addSql('ALTER TABLE allegro_offer DROP FOREIGN KEY FK_25EC2396AAFB4400');
        $this->addSql('DROP TABLE allegro_offer');
        $this->addSql('DROP TABLE allegro_event');
        $this->addSql('DROP INDEX IDX_188D20B088CAD865 ON allegro_photo');
        $this->addSql('ALTER TABLE allegro_photo DROP allegro_offer_id');
    }
}
