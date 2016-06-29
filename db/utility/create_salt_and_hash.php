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
	
	
	//	First make sure there is at least one argument passed to the script
	
	if($argc != 2)
		echo "\nYou didn't pass in the right number of arguments...\n";
		exit(200);
		
	if(!string_is_valid( $argv[1], 5, 20)) {
		echo "\nThat string isn't valid...\n";
		exit(300);
	}
	
	$salt = sha1( mt_rand() );
	$hash = sha1( $argv[1] . $salt );
	
	
	echo "\n\nSalt\t:\t$salt\nHash\t:\t$hash\n";
	
	
	/*
		CUSTOM FUNCTIONS
	*/
	function string_is_valid( $str , $min_len, $max_len ) {
		
		$is_valid = true;
		
		if( strlen( $str ) < $min_len || strlen($str) > $max_len ) {
			$is_valid = false;
		}
		
		return $is_valid;
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