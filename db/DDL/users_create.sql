#
#	Group #:			9
#
#	Group Members:		Anthony Forsythe, Songjie Wang, Weijian Li, Anjana Ramnath
#
#	Class:				CS4380 (Spring 2016)
#
#	Assignment:			Final Project
#
#	File Description:	This is the SQL file that will create all the users that will
#						access the database.


/*
	First make sure that team member users have access to the entire database
*/

#	Anthony
GRANT INSERT, UPDATE, DELETE, SELECT ON photoarchiving.* TO forsythetony WITH GRANT OPTION;

#	Songjie
GRANT INSERT, UPDATE, DELETE, SELECT ON photoarchiving.* TO songjie WITH GRANT OPTION;

#	Weijian
GRANT INSERT, UPDATE, DELETE, SELECT ON photoarchiving.* TO weijian WITH GRANT OPTION;

/*
	Create the web user ( web_user ) that will be accessing the database 
	through the PHP
*/

#	Cleanup - Delete all of the web_users privileges ( if the web_user exists )

#	REVOKE ALL privileges FROM USER 'web_user'@'localhost';

#	Cleanup - Delete the web_user ( if the web_user exists )

DROP USER 'web_user'@'localhost';

# 	Create a new web_user

CREATE USER 'web_user'@'localhost' IDENTIFIED BY 'sSK80rkyYAdzx3LjWpSN';

#	Grant the web user privileges on all tables

GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.era TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.life_period TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.person TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.non_user TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.user TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.user_auth TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.user_pref TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.repository TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.family_repository TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.location_repository TO 'web_user'@'localhost'  ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.community_repository TO 'web_user'@'localhost'  ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.person_relation TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.location TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.photograph TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.photo_tag TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.story TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.photo_story TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.user_activity TO 'web_user'@'localhost' ;


#
#	The web user should only be able to insert and select from the log
#
#	It should not be able to delete or update any log reacords once they
#	have been created
#
GRANT INSERT, SELECT ON photoarchiving.activity_log TO 'web_user'@'localhost' ;

GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.photo_repo TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.user_auth_token TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.user_repo TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.photo_loc TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.photograph_archive TO 'web_user'@'localhost' ;
GRANT UPDATE, INSERT, SELECT, DELETE ON photoarchiving.photo_loc TO 'web_user'@'localhost' ;



