<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to add tables for roads & events happening on them.
 */
final class Version20200421152404 extends AbstractMigration
{
    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return 'Add tables for roads & events happening on them.';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, road_id INT NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', resolved_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_location_name VARCHAR(255) NOT NULL, start_location_latitude VARCHAR(255) NOT NULL, start_location_longitude VARCHAR(255) NOT NULL, end_location_name VARCHAR(255) NOT NULL, end_location_latitude VARCHAR(255) NOT NULL, end_location_longitude VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA7962F8178 (road_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'CREATE TABLE road (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_95C0C4B15E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7962F8178 FOREIGN KEY (road_id) REFERENCES road (id)'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7962F8178');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE road');
    }
}
