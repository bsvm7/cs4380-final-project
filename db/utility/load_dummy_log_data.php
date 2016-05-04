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
	
	$month_ago_date = get_timestamp_for_days_age( 33 );
	
	$set_user_reg_date_sql = "INSERT INTO activity_log ( ps_id , ac_type , time_logged ) VALUES ( " . $user_info["user_id"] . " , 'user-register', '$month_ago_date'";
	
	if(!($db_conn->query($set_user_reg_date_sql))) {
		
		$error_str = "I couldn't set the registration date of the specified user with user information -> " . json_encode($user_info) . "\n";
		
		set_error_response( 201 , $error_str );
	}
	else {
		
		$success_str = "I successfully set the registration date of the user with user info -> " . json_encode($user_info);
		
		echo "\n$success_str\n";
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