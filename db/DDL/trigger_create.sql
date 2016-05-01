#
#	Group #:			9
#
#	Group Members:		Anthony Forsythe, Songjie Wang, Weijian Li, Anjana Ramnath
#
#	Class:				CS4380 (Spring 2016)
#
#	Assignment:			Final Project
#
#	File Description:	This file will create all the triggers that will be used in
#						'photoarchiving' database

USE photoarchiving;



#	A trigger to set all empty strings to untitled in the story table on insert
DROP TRIGGER IF EXISTS story_title_string_insert;

DELIMITER //
CREATE TRIGGER story_title_string_insert BEFORE INSERT ON story
FOR EACH ROW
BEGIN
	IF NEW.title = '' THEN
		SET NEW.title = 'untitled';
	ELSEIF NEW.title = ' ' THEN
		SET NEW.title = 'untitled';
	END IF;
END;//
delimiter ;



#	A trigger to set all empty strings to untitled in the photograph table on insert
DROP TRIGGER IF EXISTS photo_title_string_insert;

DELIMITER //
CREATE TRIGGER photo_title_string_insert BEFORE INSERT ON photograph
FOR EACH ROW
BEGIN
	IF NEW.title = '' THEN
		SET NEW.title = 'untitled';
	ELSEIF NEW.title = ' ' THEN
		SET NEW.title = 'untitled';
	END IF;
END;//
delimiter ;