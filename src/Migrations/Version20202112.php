<?php

namespace App\Migrations;

use App\Core\Model\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20202112 extends AbstractMigration
{
    /**
     * Understanding why, just a simple description as to for what this migration was needed/created.
     * @return string
     */
    public function getDescription() : string
    {
        return '';
    }

    public function up()
    {
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
    }

    public function down()
    {
        $this->addSql('DROP TABLE user');
    }
}