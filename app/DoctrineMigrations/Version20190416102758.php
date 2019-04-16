<?php
namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190416102758 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            DROP TABLE IF EXISTS `complain`;
            CREATE TABLE `complain` (
                `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
                `context` varchar(500) NOT NULL DEFAULT '' COMMENT '投诉内容',
                `adminId` int(10) NOT NULL DEFAULT '0' COMMENT '投诉人id',
                `updatedTime` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
                `createdTime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投诉表';
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}