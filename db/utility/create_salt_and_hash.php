<?php
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
	
	
	echo $salt;
		
	
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