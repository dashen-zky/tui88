delimiter $$
create EVENT if not EXISTS `update_task_getting_status`
ON SCHEDULE EVERY 1 DAY
STARTS '2017-05-21 23:00:00'
DO
BEGIN
UPDATE task SET `getting_status`=2 WHERE `getting_status`=1 AND `enable`=1 AND `distribute_status`=1 AND `start_getting_time` <= UNIX_TIMESTAMP() AND `end_getting_time` > UNIX_TIMESTAMP();
UPDATE task SET `getting_status`=3 WHERE (`getting_status`=2 OR `getting_status`=1) AND `enable`=1 AND `distribute_status`=1 AND `end_getting_time` <= UNIX_TIMESTAMP();
END $$

delimiter $$
create EVENT if not EXISTS `update_task_executing_status`
ON SCHEDULE EVERY 1 DAY
STARTS '2017-05-21 23:00:00'
DO
BEGIN
UPDATE `task` SET `executing_status`=2 WHERE `executing_status`=1 AND `enable`=1 AND `distribute_status`=1 AND `start_execute_time`<=UNIX_TIMESTAMP() AND `end_execute_time`>UNIX_TIMESTAMP();
UPDATE `task` SET `executing_status`=3 WHERE (`executing_status`=2 OR `executing_status`=1) AND  `enable`=1 AND `distribute_status`=1 AND `end_execute_time`<=UNIX_TIMESTAMP();
END $$

delimiter $$
create EVENT if not EXISTS `update_order_status`
ON SCHEDULE EVERY 1 DAY
STARTS '2017-05-21 23:00:00'
DO
BEGIN
UPDATE `executor_task_map` as t1 LEFT JOIN `task` as t2 ON t1.`task_uuid`=t2.`uuid`
SET t1.`status`=2 WHERE t1.`status`=1 AND t2.`enable`=1 AND t2.`start_execute_time`<=UNIX_TIMESTAMP() AND t2.`end_execute_time`>UNIX_TIMESTAMP();
UPDATE `executor_task_map` as t1 LEFT JOIN `task` as t2 ON t1.`task_uuid`=t2.`uuid`
SET t1.`status`=129 WHERE t1.`status` in (1,2) AND t2.`enable`=1 AND t2.`end_execute_time`<UNIX_TIMESTAMP();
END $$
