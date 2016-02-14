<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160214011758 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE edge ADD graph_id VARCHAR(255) NOT NULL');
        $this->addSql('COMMENT ON COLUMN edge.graph_id IS \'A string identifier, imported from the XML file\'');
        $this->addSql('COMMENT ON COLUMN edge.from_id IS \'A string identifier, imported from the XML file\'');
        $this->addSql('COMMENT ON COLUMN edge.to_id IS \'A string identifier, imported from the XML file\'');
        $this->addSql('ALTER TABLE edge ADD CONSTRAINT FK_7506D36699134837 FOREIGN KEY (graph_id) REFERENCES graph (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE edge ADD CONSTRAINT FK_7506D36678CED90B FOREIGN KEY (from_id) REFERENCES node (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE edge ADD CONSTRAINT FK_7506D36630354A65 FOREIGN KEY (to_id) REFERENCES node (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7506D36699134837 ON edge (graph_id)');
        $this->addSql('CREATE INDEX IDX_7506D36678CED90B ON edge (from_id)');
        $this->addSql('CREATE INDEX IDX_7506D36630354A65 ON edge (to_id)');
        $this->addSql('COMMENT ON COLUMN node.graph_id IS \'A string identifier, imported from the XML file\'');
        $this->addSql('ALTER TABLE node ADD CONSTRAINT FK_857FE84599134837 FOREIGN KEY (graph_id) REFERENCES graph (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_857FE84599134837 ON node (graph_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE edge DROP CONSTRAINT FK_7506D36699134837');
        $this->addSql('ALTER TABLE edge DROP CONSTRAINT FK_7506D36678CED90B');
        $this->addSql('ALTER TABLE edge DROP CONSTRAINT FK_7506D36630354A65');
        $this->addSql('DROP INDEX IDX_7506D36699134837');
        $this->addSql('DROP INDEX IDX_7506D36678CED90B');
        $this->addSql('DROP INDEX IDX_7506D36630354A65');
        $this->addSql('ALTER TABLE edge DROP graph_id');
        $this->addSql('COMMENT ON COLUMN edge.from_id IS \'A mandatory reference to the source node.\'');
        $this->addSql('COMMENT ON COLUMN edge.to_id IS \'A mandatory reference to the destination node.\'');
        $this->addSql('ALTER TABLE node DROP CONSTRAINT FK_857FE84599134837');
        $this->addSql('DROP INDEX IDX_857FE84599134837');
        $this->addSql('COMMENT ON COLUMN node.graph_id IS \'A mandatory reference to the graph.\'');
    }
}
