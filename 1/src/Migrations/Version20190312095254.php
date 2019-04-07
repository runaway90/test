<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312095254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE allegro_parameter_dictionary');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE allegro_parameter_dictionary (id INT AUTO_INCREMENT NOT NULL, allegro_parameter_id INT DEFAULT NULL, allegro_id VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, value VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_5C6ABF202BD80AB8 (allegro_parameter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE allegro_parameter_dictionary ADD CONSTRAINT FK_5C6ABF202BD80AB8 FOREIGN KEY (allegro_parameter_id) REFERENCES allegro_parameter (id)');
    }
}
