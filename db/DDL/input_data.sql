#	Make sure that you're using the right database
USE photoarchiving;



#	Load data for the eras table
LOAD DATA LOCAL INFILE '../input_data/era.csv' INTO TABLE era
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(name, @start_date, @end_date)
SET 
	start_date 	= STR_TO_DATE(@start_date, '%c-%e-%Y'),
	end_date	= STR_TO_DATE(@end_date, '%c-%e-%Y')
;


#	Load data for the life_period table
LOAD DATA LOCAL INFILE '../input_data/life_period.csv' INTO TABLE life_period
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
( name , start_year , end_year );



#	Load data for the person table
LOAD DATA LOCAL INFILE '../input_data/person.csv' INTO TABLE person
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
( fname, mname, lname, maiden_name, gender, @birth_date )
SET
	birthdate = STR_TO_DATE(@birth_date, '%c-%e-%Y')
;

#	Load data for the user table
LOAD DATA LOCAL INFILE '../input_data/user.csv' INTO TABLE user
FIELDS TERMINATED BY ','
OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
( ps_id , username, email );


#	Load data into the story table
LOAD DATA LOCAL INFILE '../input_data/story.csv' INTO TABLE story
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
( title , description, recording_url, recording_text );

#	Load data into the photograph table
LOAD DATA LOCAL INFILE '../input_data/photograph.csv' INTO TABLE photograph
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 LINES
( title, description, large_url, thumb_url, @date_taken, date_conf )
SET
	date_taken = STR_TO_DATE(@date_taken, '%c-%e-%Y')
;
