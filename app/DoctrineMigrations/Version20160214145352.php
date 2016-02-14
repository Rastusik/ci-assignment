<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160214145352 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE VIEW path AS '
            . 'WITH RECURSIVE search_path (id, start_node_id, end_node_id, depth, path, path_cost, cycle) AS ('
                . 'SELECT md5(concat_ws(\'|\', array_to_string(ARRAY[id]::text[], \',\'), 0.0::text)), id::text, id::text, 0, ARRAY[id]::text[], 0.0, false FROM node '
                . 'UNION ALL '
                . 'SELECT md5(concat_ws(\'|\', array_to_string((path || to_id::text), \',\'), (path_cost + cost)::text)), start_node_id, to_id, depth + 1, (path || to_id::text), path_cost + cost, to_id = ANY(path) '
                . 'FROM edge, search_path '
                . 'WHERE from_id = end_node_id AND NOT CYCLE '
            . ') SELECT * FROM search_path WHERE NOT CYCLE AND depth > 0 ORDER BY path_cost');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP VIEW path');
    }
}
