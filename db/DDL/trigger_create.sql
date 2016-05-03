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


#	A trigger to archive all deleted photographs in the archived_photographs table
DROP TRIGGER IF EXISTS archive_photographs;

DELIMITER //
CREATE TRIGGER archive_photographs BEFORE DELETE ON photograph
FOR EACH ROW
BEGIN
	SET arch_photo_url_large = OLD.large_url;
	SET arch_photo_url_thumb = OLD.thumb_url;
	SET arch_photo_title = OLD.title;
	SET arch_repo_title = (SELECT R.name FROM repository R, photo_repo PR WHERE PR.p_id = OLD.p_id AND PR.r_id = R.r_id LIMIT 1);
	SET arch_photo_date_uploaded = OLD.date_uploaded;
END;//
delimiter ;