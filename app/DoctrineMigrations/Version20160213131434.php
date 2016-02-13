<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160213131434 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE edge (id VARCHAR(255) NOT NULL, "from" VARCHAR(255) NOT NULL, "to" VARCHAR(255) NOT NULL, cost NUMERIC(7, 2) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN edge.id IS \'A string identifier, imported from the XML file\'');
        $this->addSql('COMMENT ON COLUMN edge."from" IS \'A mandatory reference to the source node.\'');
        $this->addSql('COMMENT ON COLUMN edge."to" IS \'A mandatory reference to the destination node.\'');
        $this->addSql('COMMENT ON COLUMN edge.cost IS \'Unsigned value is checked during validation.\'');
        $this->addSql('CREATE TABLE graph (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN graph.id IS \'A string identifier, imported from the XML file\'');
        $this->addSql('CREATE TABLE node (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, graph VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN node.id IS \'A string identifier, imported from the XML file\'');
        $this->addSql('COMMENT ON COLUMN node.graph IS \'A mandatory reference to the graph.\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE edge');
        $this->addSql('DROP TABLE graph');
        $this->addSql('DROP TABLE node');
    }
}
