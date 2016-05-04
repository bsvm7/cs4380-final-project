<?php
	//	Include reference to sensitive databse information
	include("../../../db_security/security.php");
	
	$db_user 		= constant("DB_USER");
	$db_host 		= constant("DB_HOST");
	$db_pass 		= constant("DB_PASS");
	$db_database 	= constant("DB_DATABASE");
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
	
	if ($db_conn->error_code) {
		
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);
		die("The connection to the database failed: " . $db_conn->connect_error);
	}


	/*
		First set the registration date of the primary user ( forsythetony )
	*/
	$user_info = array( 
		"username" 	=> "forsythetony",
		"user_id" 	=> 1
	);
	
	$month_ago_date = convert_timestamp_to_proper_date(get_timestamp_for_days_age( 33 ));
	
	$mont_ago_timestamp = strtotime($month_ago_date);
	
	echo "\nThe formatted timestamp is -> $month_ago_date and the raw timestamp is -> $mont_ago_timestamp\n";
	
	
	$set_user_reg_date_sql = "INSERT INTO activity_log ( ps_id , ac_type , time_logged ) VALUES ( " . $user_info["user_id"] . " , 'user-register', '$month_ago_date');";
	
	if(!($db_conn->query($set_user_reg_date_sql))) {
		
		$error_str = "I couldn't set the registration date of the specified user with user information -> " . json_encode($user_info) . "\n 
						And the following SQL -> " . $set_user_reg_date_sql . "\n";
		
		set_error_response( 201 , $error_str );
	}
	else {
		
		$success_str = "I successfully set the registration date of the user with user info -> " . json_encode($user_info);
		
		echo "\n$success_str\n";
	}
	
	
	
	
	/*
		Now grab all the photo IDs
	*/
	
	$photo_information = array();
	
	$get_all_photo_ids_sql = "SELECT P.p_id FROM photograph P";
	
	if($result = $db_conn->query($get_all_photo_ids_sql)) {
		
		while($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
			
			$photo_id = $result_row["p_id"];
			
			$new_photo_information = new PhotoInfo();
			
			$new_photo_information->photo_id = $photo_id;
					
			array_push($photo_information, $new_photo_information);
		}
	}
	
	/*
		Insert photo uploads into the log
	*/
	
	//	First create the SQL for the statement
	$insert_photo_to_log_sql = "INSERT INTO activity_log ( ps_id , ac_type , p_id , time_logged ) "
								. "VALUES ( " . $user_info["user_id"] . " , 'photo-upload' , ? , ? )";
								
	//	Now prepare the statement
	if(!($insert_photo_to_log_stmt = $db_conn->prepare($insert_photo_to_log_sql))) {
		$error_message = error_string_for_statement_prepare( $insert_photo_to_log_sql , $db_conn->error );
		
		set_error_response( 0 , $error_message );
	}
	
	//	Loop through all the photographs
	for( $i = 0; $i < count($photo_information); $i++) {
		
		$curr_photo_info = $photo_information[$i];
		
		$rand_date = get_random_date_between_now_and_month_ago();
		
		$curr_photo_info->random_date = $rand_date;
		
		$curr_photo_id = $curr_photo_info->photo_id;
		
		
		//	Bind the parameters
		if(!($insert_photo_to_log_stmt->bind_param("is", $curr_photo_id, $rand_date))) {
			
			$error_str = error_string_for_param_bind( $insert_photo_to_log_sql , $db_conn->error );
			set_error_response( 0, $error_str );
		}
		
		if($insert_photo_to_log_stmt->execute()) {
			$success_str = "\nI successfully inserted the photo with information -> " . json_encode($curr_photo_info) . " to the database...\n";
			echo $success_str;
		}
		else {
			$error_str = error_string_for_statement_execute( $insert_photo_to_log_sql , $db_conn->error);
			set_error_response( 0 , $error_str);
		}

	}
	
	foreach( $photo_information as $curr_photo_info) {
		
		$curr_photo_info->print_values();
	}
	
	
	
	/*
		
	/*
		Now grab all the stories for each array
	*/
	
	foreach (  $photo_information as $curr_photo_info ) {
		
		$curr_photo_id 			= $curr_photo_info->photo_id;
		$curr_photo_upload_date = $curr_photo_info->random_date;
		
		//	The SQL for grabbing all the story IDs
		
		$get_all_photo_ids_sql = "SELECT PS.s_id FROM photo_story PS WHERE PS.p_id = " . $curr_photo_id . ";";
		
		if($result = $db_conn->query($get_all_photo_ids_sql)) {

			while($result_array = $result->fetch_array(MYSQLI_ASSOC)) {
				
				$story_id = $result_array["s_id"];
				$story_rand_date = get_date_between_now_and_date( $curr_photo_upload_date );
				
				$new_story_info = new StoryInfo();
				
				$new_story_info->story_id = $story_id;
				$new_story_info->set_random_date_with_date( $curr_photo_upload_date );
				
				$curr_photo_info->add_story( $new_story_info );
			}
		}
		else {
			$error_str = "I couldn't get all the stories for the photo with id -> " . $curr_photo_id . "... SQL Error -> " . $db_conn->error;
			echo_in_newlines( $error_str );
		}

	}
	
	
	/*
		Set the story upload time in the log database
	*/
	
	//	SQL for inserting a story upload into the log table
	$insert_story_into_log_sql = "INSERT INTO activity_log ( ps_id , ac_type , s_id , p_id , time_logged ) "
									. "VALUES ( " . $user_info["user_id"] . " , 'story-upload' , ? , ? , ? )";
									
	//	Prepare the statement for inserting a story upload into the activity log
	if(!($insert_story_into_log_stmt = $db_conn->prepare($insert_story_into_log_sql))) {
		$error_str = error_string_for_statement_prepare( $insert_story_into_log_sql , $db_conn->error );
		set_error_response( 0 , $error_str );
	}
	
	foreach( $photo_information as $curr_photo_info ) {
		
		$curr_photo_id = $curr_photo_info->photo_id;
		
		$all_stories = $curr_photo_info->stories;
		
		
		foreach( $all_stories as $story_info ) {
			
			$story_upload_timestamp = $story_info->random_date;
			$story_id = $story_info->story_id;
			
			//	Bind the parameters to the previously created prepared statement
			if(!($insert_story_into_log_stmt->bind_param("iis", $story_id , $curr_photo_id , $story_upload_timestamp))) {
				
				$error_str = error_string_for_param_bind( $insert_story_into_log_sql , $db_conn->error );
				set_error_response( 0 , $error_str );
			}
			else {
				
				if($insert_story_into_log_stmt->execute()) {
					$success_values = array(
						"story_id" => $story_id,
						"photo_id" => $curr_photo_id,
						"story_timestamp" => $story_upload_timestamp 
					);
				
					echo_in_newlines( "Success! -> " . json_encode($success_values) );	
				}
				else {
					$error_str = error_string_for_statement_execute( $insert_story_into_log_sql, $db_conn->error );
					set_error_response( 0 , $error_str );
				}
				
			}
			
			
			
		}
		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	/*
		CUSTOM FUNCTIONS
	*/
	function echo_in_newlines( $str ) {
		echo "\n$str\n";
	}
	
	function get_date_between_now_and_date( $date ) {
		
		$todays_timestamp = time();
		$dates_timestamp = strtotime( $date );
		
		$rand_timestamp = mt_rand($dates_timestamp, $todays_timestamp );
		
		return convert_timestamp_to_proper_date( $rand_timestamp );
	}
	
	function get_timestamp_for_days_age( $days_ago ) {
		
		$days_ago_timestamp = time() - convert_days_to_seconds( $days_ago );
		
		return $days_ago_timestamp;
		
	}
	function convert_timestamp_to_proper_date( $timestamp ) {
		
		$date = date("Y-m-d H:i:s", $timestamp);
		
		return $date;
	}
	
	function get_random_date_between_now_and_month_ago() {
		
		$todays_timestamp = time();
		$last_months_timestamp = $todays_timestamp - convert_days_to_seconds( 31 );	
		
		$rand_timestamp = mt_rand($last_months_timestamp, $todays_timestamp);
		
		return convert_timestamp_to_proper_date( $rand_timestamp );
	}
	
	function convert_days_to_seconds( $days ) {
		
		return ($days * 24 * 60 * 60 );
	}
	function error_string_for_param_bind( $sql_statement , $db_error ) {
		
		$error_str = "There was an error binding the parameters for the SQL statement -> ' " . $sql_statement . " ' ... SQL Error -> " . $db_error;
		
		return $error_str;
	}
	
	function error_string_for_statement_execute( $sql_statement, $db_error ) {
		
		$error_str = "There was an error executing the statement with the SQL -> " . $sql_statement . "... SQL Error -> " . $db_error;
		return $error_str;
	}
	
	function error_string_for_statement_prepare( $sql_statement , $db_error ) {
		
		$error_str = "There was an error preparing the statement with the SQL -> " . $sql_statement . "... SQL Error -> " . $db_error;
		return $error_str;
	}

	function set_error_response( $error_code , $error_message ) {
		
		
		$http_response_code = 500;
		
		$response_array = array(
			"error_code" => $error_code,
			"error_message" => $error_message
		);
				echo json_encode($response_array);
		http_response_code($error_code);
		
		exit(-1);
		
	}
	
	
	/*
		Custom classes
	*/
	
	class StoryInfo {
		
		public $story_id;
		public $random_date;
		
		
		public function set_random_date_with_date( $date ) {
			$this->random_date = get_date_between_now_and_date( $date );
		}
		
	}
	
	class PhotoInfo {
		
		public $photo_id;
		public $random_date;
		public $stories;
		
		
		public function add_story( $new_story ) {
			
			if(!(isset($this->stories))) {
				$this->stories = array();
			}
			
			array_push($this->stories, $new_story );
			
		}
		
		public function print_values() {
			
			$echo_values = array(
				"photo_id"	=> $this->photo_id,
				"random_date" => $this->random_date,
				"stories" => $this->stories
			);
			
			echo_in_newlines( json_encode($echo_values) );
		}
	}
?>