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
	
	
	if(false) {
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
	
	$photo_ids = array();
	
	$get_all_photo_ids_sql = "SELECT P.p_id FROM photograph P";
	
	if($result = $db_conn->query($get_all_photo_ids_sql)) {
		
		while($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
			
			$photo_id = $result_row["p_id"];
			
			$insert_arr = array(
				"photo_id"		=> $photo_id,
				"story_ids"		=> array()
			);
			
			array_push($photo_ids, $insert_arr);
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
	foreach( $photo_ids as $curr_photo_info) {
		
		$rand_date = get_random_date_between_now_and_month_ago();
		
		$curr_photo_info["rand_date"] = $rand_date;
		
		$curr_photo_id = $curr_photo_info["photo_id"];
		
		
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
	
	
	
	
	
	/*
		
	/*
		Now grab all the stories for each array
	*/
	
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
		CUSTOM FUNCTIONS
	*/
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
	
?>