<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201124094751 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Modifie la table des agents';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->write($this->getDescription());
        $this->addSql('ALTER TABLE personnel ADD column_toto VARCHAR(25)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE personnel DROP COLUMN column_toto');

    }
}
