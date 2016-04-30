#
#	Group #:			9
#
#	Group Members:		Anthony Forsythe, Songjie Wang, Weijin Li, Anjana Ramnath
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

#	Cleanup - Delete the web_user ( if the web_user exists )


CREATE USER 'web_user'@'localhost' IDENTIFIED BY 'sSK80rkyYAdzx3LjWpSN';

#	Grant the web user privileges on all tables
/*
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
GRANT UPDATE, INSERT, SELECT, DELETE TO 'web_user'@'localhost' ON photoarchiving. ;
*/