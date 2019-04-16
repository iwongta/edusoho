<?php

use Phpmig\Migration\Migration;

class Complain extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $this->getConnection()->exec("
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
     * Undo the migration
     */
    public function down()
    {
        $this->getConnection()->exec('
            DROP TABLE IF EXISTS `complain`;
        ');
    }

    public function getConnection()
    {
        $biz = $this->getContainer();

        return $biz['db'];
    }
}
