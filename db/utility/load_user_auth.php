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
		CONSTANTS
	*/
	$default_password = "pass";
	




	/*
		FIRST GET ALL THE ps_id's FROM THE user TABLE
	*/
	$get_all_users_sql = "SELECT U.ps_id FROM user U";
	
	if(!($result = $db_conn->query($get_all_users_sql))) {
		
		$error_str = "I couldn't get all the users with the SQL -> " . $get_all_users_sql . "... SQL Error -> " . $db_conn->error;
		
		set_error_response( 201 , $error_str );
		
	}
	
	$user_ids = array();
	
	while($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
		
		$user_id = $result_row["ps_id"];
		
		array_push($user_ids, $user_id);
	}
	
	
	
	//	Create the statement to insert values into the user_auth table	
	$insert_user_auth_sql = "INSERT INTO user_auth ( ps_id , pass_hash , pass_salt ) VALUES ( ? , ? , ? )";
	
	if(!($insert_user_auth_stmt = $db_conn->prepare($insert_user_auth_sql))) {
		
		$error_str = "I couldn't prepare the statement with the SQL -> " . $insert_user_auth_sql . "... SQL Error -> " . $db_conn->error;
		
		set_error_response( 201 , $error_str );
	}
	
	//	For each user generate salt and hash values and insert all values 
	//	into the database using the previously generated statement
	foreach($user_ids as $the_user_id) {
		
		//	Generate the salt value
		$salt = sha1( mt_rand() );
		
		//	Generate the hash value
		$hash = sha1( $default_password . $salt );
		
		//	Bind the parameters for the statement
		if(!($insert_user_auth_stmt->bind_param("iss", $the_user_id, $hash, $salt))) {
			
			$error_str = "I couldn't bind the parameters for the SQL -> " . $insert_user_auth_sql . "... SQL Error -> " . $db_conn->error;
			
			set_error_response( 201 , $error_str );
		}
		
		//	Execute the statement
		if(!($insert_user_auth_stmt->execute())) {
			
			$error_str = "I couldn't execute the statement with the SQL -> " . $insert_user_auth_sql . ".... SQL Error -> " . $db_conn->error;
			
			set_error_response( 201 , $error_str );
		}
		else {
			
			$success_str = "\nI inserted the user with ps_id -> $the_user_id , hash value -> $hash and salt value -> $salt into the database \n";
			
			echo $success_str;
		}
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
		
		exit(-1);
		
	}
?>