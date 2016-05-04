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
	DECLARE arch_photo_url_large 	VARCHAR(2083);
	DECLARE arch_photo_url_thumb 	VARCHAR(2083);
	DECLARE arch_photo_title 		VARCHAR(200);
	DECLARE arch_repo_title			VARCHAR(200);
	DECLARE arch_photo_date_uploaded TIMESTAMP;
	
	SET arch_photo_url_large = OLD.large_url;
	SET arch_photo_url_thumb = OLD.thumb_url;
	SET arch_photo_title = OLD.title;
	SET arch_repo_title = (SELECT R.name FROM repository R, photo_repo PR WHERE PR.p_id = OLD.p_id AND PR.r_id = R.r_id LIMIT 1);
	SET arch_photo_date_uploaded = OLD.date_uploaded;
	
	INSERT INTO photograph_archive ( 
										p_id,
										photo_url_large , 
										photo_url_thumb, 
										photo_title, 
										repo_title, 
										date_uploaded 
									) 
								VALUES 
								( 
									OLD.p_id,
									arch_photo_url_large , 
									arch_photo_url_thumb, 
									arch_photo_title, 
									arch_repo_title, 
									arch_photo_date_uploaded 
								);
END;//
delimiter ;



#	A trigger to archive all the deleted stories in the archived_stories table
DROP TRIGGER IF EXISTS archive_stories;

DELIMITER //
CREATE TRIGGER archive_stories BEFORE DELETE ON story
FOR EACH ROW
BEGIN
	
	#	First declare the variables to use
	DECLARE arch_rec_url		VARCHAR(2083);
	DECLARE arch_rec_title		VARCHAR(200);
	DECLARE arch_p_id			BIGINT UNSIGNED;
	DECLARE arch_p_title		VARCHAR(200);
	DECLARE arch_upload_date	TIMESTAMP;
	
	#	Now set the variables to their values
	SET arch_rec_url = OLD.recording_url;
	SET arch_rec_title = OLD.title;
	SET arch_p_id = (SELECT PS.p_id FROM photo_story PS WHERE PS.s_id = OLD.s_id LIMIT 1);
	SET arch_p_title = (SELECT P.title FROM photo_story PS, photo P WHERE PS.s_id = OLD.s_id AND P.p_id = PS.p_id LIMIT 1);
	SET arch_upload_date = (SELECT AL.time_logged FROM activity_log AL WHERE AL.s_id = OLD.s_id AND AL.ac_type = 'story-create' LIMIT 1);
	
	#	Now insert these values into the archived stories ( story_archive ) table
	INSERT INTO story_archive (
									s_id,
									recording_url,
									recording_title,
									p_id,
									p_title,
									upload_date,
								)
							VALUES
							(
								OLD.s_id,
								arch_rec_url,
								arch_rec_title,
								arch_p_id,
								arch_p_title,
								arch_upload_date
							);

END;//
delimiter ;