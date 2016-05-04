#
#	Group #:			9
#
#	Group Members:		Anthony Forsythe, Songjie Wang, Weijian Li, Anjana Ramnath
#
#	Class:				CS4380 (Spring 2016)
#
#	Assignment:			Final Project
#
#	File Description:	This is the SQL file that will create some useful procedures that will be used by
#						our program
#

#
#	1)	Drop Index If Exists
#		- 	The SQL for this procure was taken from this GitHub Gist ( https://gist.github.com/drawcode/4441638 )
#	
DELIMITER $$

DROP PROCEDURE IF EXISTS drop_index_if_exists $$
CREATE PROCEDURE drop_index_if_exists(in theTable varchar(128), in theIndexName varchar(128) )
BEGIN
 IF((SELECT COUNT(*) AS index_exists FROM information_schema.statistics WHERE TABLE_SCHEMA = DATABASE() and table_name =
theTable AND index_name = theIndexName) > 0) THEN
   SET @s = CONCAT('DROP INDEX `' , theIndexName , '` ON `' , theTable, '`');
   PREPARE stmt FROM @s;
   EXECUTE stmt;
 END IF;
END $$

DELIMITER ;