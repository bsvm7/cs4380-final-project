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
		FIRST GET ALL THE NON USER PERSONS
	*/
	
	//	Create the SQL to grab people IDs from the database that
	//	are not already in the user table
	$non_user_sql = "SELECT P.ps_id FROM person P WHERE P.ps_id NOT IN ( SELECT U.ps_id FROM user U)";
	
	if(!($result = $db_conn->query($non_user_sql))) {
		
		$error_str = "There was a SQL error -> " . $db_conn->error;
		
		set_error_response( 201 , $error_str );	
	}
	
	
	$non_user_ids = array();
	
	while( $result_row = $result->fetch_array(MYSQLI_ASSOC)) {
	
		echo "\n";
		echo json_encode($result_row);
		echo "\n";
	}
		
	
	
	
	
	
	
	
	
	
	
	
	
	/*
		CUSTOM FUNCTIONS
	*/
	function set_error_response( $error_code , $error_message ) {
		
		
		$http_response_code = 500;
		
		$response_array = array(
			"error_code" => $error_code,
			"error_message" => $error_message
		);
				echo json_encode($response_array);
		http_response_code($error_code);
		
	}
	
?>