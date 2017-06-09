<?php

use yii\db\Migration;

class m170104_023342_create_message_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $sql = "CREATE TABLE IF NOT EXISTS `dts_message` (
              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `title` VARCHAR(155) NOT NULL,
              `body` TEXT NOT NULL,
              `created_time` INT UNSIGNED NOT NULL DEFAULT 0,
              `type` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '系统消息等等',
              `priority` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '优先级',
              `queue_id` VARCHAR(45) NOT NULL,
              `show_style` TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT '1 弹窗\n２　打开新页面',
              PRIMARY KEY (`id`))
            ENGINE = InnoDB 
            COMMENT = '消息表' charset utf8";
        $this->db->createCommand($sql)->execute();

        $sql = "CREATE TABLE IF NOT EXISTS `dts_message_queue_subscription` (
                  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                  `queue_id` VARCHAR(45) NOT NULL,
                  `user_id` VARCHAR(45) NOT NULL,
                  PRIMARY KEY (`id`))
                ENGINE = InnoDB charset utf8";
        $this->db->createCommand($sql)->execute();

        $sql = "CREATE TABLE IF NOT EXISTS `dts_message_user_map` (
              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
              `message_id` INT UNSIGNED NOT NULL,
              `checked` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 　未读\n１　已读',
              `checked_time` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '阅读时间',
              `user_id` VARCHAR(45) NOT NULL,
              PRIMARY KEY (`id`),
              INDEX `message_id_idx` (`message_id` ASC),
              CONSTRAINT `message_id`
                FOREIGN KEY (`message_id`)
                REFERENCES `dts_message` (`id`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION)
            ENGINE = InnoDB charset utf8";
        $this->db->createCommand($sql)->execute();
        echo 'create dts_message done';
    }

    public function down()
    {
        $this->dropTable('dts_message_user_map');
        $this->dropTable('dts_message');
        $this->dropTable('dts_message_queue_subscription');
    }
}
