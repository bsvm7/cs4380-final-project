#
#	Group #:			9
#
#	Group Members:		Anthony Forsythe, Songjie Wang, Weijian Li, Anjana Ramnath
#
#	Class:				CS4380 (Spring 2016)
#
#	Assignment:			Final Project
#
#	File Description:	This is the SQL file that will create all the indexes that we will be using for our project
#

#
#	1)	Photo Date Taken Index ( photo_date_taken_index )
#

#	First drop the index if it exists using a stored procedure
CALL drop_index_if_exists('photograph' , 'photograph_date_taken_index');

#	Now we can safely create the index
CREATE INDEX photograph_date_taken_index ON photograph(date_taken) USING BTREE;



#
#	2)	Person Date Born Index ( person_date_born_index )
#

#	First drop the index if it exists using a stored procedure
CALL drop_index_if_exists('person' , 'person_date_born_index');

#	Now we can safely create the index
CREATE INDEX person_date_born_index ON person( birthdate ) USING BTREE;
