CREATE TABLE IF NOT EXISTS `frontend_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45)  NOT NULL COMMENT '用户的id',
  `phone` BIGINT NOT NULL COMMENT '用户的电话和用户名',
  `password` VARCHAR(45) NOT NULL COMMENT '密码',
  `email` VARCHAR(45) NOT NULL COMMENT '用户的邮箱',
  `status` TINYINT NOT NULL DEFAULT 1 COMMENT '用户状态', 
  `created_time` INT  NOT NULL,
  `update_time` INT  NOT NULL,
  `access_token` VARCHAR(45) NOT NULL,
  PRIMARY KEY(`uuid`),KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
  )
ENGINE = InnoDB
COMMENT = '前台用户表' charset utf8;

CREATE TABLE IF NOT EXISTS `frontend_user_information` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_uuid` varchar(45) NOT NULL,
  `location` varchar(155) DEFAULT NULL,
  `nick_name` varchar(45) DEFAULT NULL,
  `contact` varchar(45) DEFAULT NULL,
  `wechat` varchar(45) DEFAULT NULL,
  `qq` int(10) unsigned DEFAULT NULL,
  `finance_information` text COMMENT '金融账号信息',
  `id_code` varchar(20) DEFAULT NULL COMMENT '身份证',
  `received_money_total` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '收到总金额',
  `account_receivable_total` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '应收总金额',
  PRIMARY KEY (`id`),
  KEY `fk_frontend_user_information_1_idx` (`user_uuid`),
  CONSTRAINT `fk_frontend_user_information_1` FOREIGN KEY (`user_uuid`) REFERENCES `frontend_user` (`uuid`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `backend_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(45) NOT NULL,
  `phone` bigint(20) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1 active',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `access_token` varchar(45) NOT NULL,
  PRIMARY KEY (`uuid`),
  KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

CREATE TABLE IF NOT EXISTS `task` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` VARCHAR(45) NOT NULL,
  `title` VARCHAR(155) NOT NULL,
  `content` TEXT NOT NULL,
  `remarks` TEXT NULL,
  `check_standard` TEXT NULL COMMENT '验收标准',
  `start_execute_time` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '开始执行时间',
  `end_execute_time` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '结束执行时间',
  `create_uuid` VARCHAR(45) NOT NULL,
  `start_getting_time` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '开始领取时间',
  `end_getting_time` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '结束领取时间',
  `limit` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '人数上限',
  `unit_money` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
  `total_money` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0,
  `create_time` INT UNSIGNED NOT NULL DEFAULT 0,
  `update_time` INT UNSIGNED NOT NULL DEFAULT 0,
  `paied_money` DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '已支付金额',
  `getting_status` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `execute_status` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `enable` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `distribute_status` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `number_of_getted` INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '已领取数量',
  `executor_information_config` VARCHAR(45) NULL COMMENT '需要加载执行的相关信息的配置',
  PRIMARY KEY (`uuid`),KEY(`id`))
ENGINE = InnoDB
COMMENT = '任务表'charset utf8;



