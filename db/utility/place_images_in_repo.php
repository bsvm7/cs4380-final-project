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
	$repo_info = array(
		"name"			=> "Forsythe Family",
		"description"	=> "This is the family repository for the Forsythe family.",
		"family_name"	=> "Forsythe"
	);
	
	$username = "forsythetony";
	
	
	
	
	/*
		GET INFORMATION FOR THE USER THAT WILL BE CREATING AND ADDING
		TO THE REPOSITORY
	*/
	$user_info;
	
	$get_user_info_sql = "SELECT U.ps_id, U.username, U.email FROM user U WHERE U.username = '$username'";
	
/*
	if(!($get_user_info_stmt = $db_conn->prepare($get_user_info_sql))) {
		
		$error_str = "I couldn't prepare the statement with the SQL -> " . $get_user_info_sql . "... SQL Error -> " . $db_conn->error;
		
		set_error_response( 201 , $error_str );
	}
	
	if(!($get_user_info_stmt->bind_param("s", $username))) {
		
		$error_str = "I couldn't bind the parameters for the SQL -> " . $get_user_info_sql . "... SQL Error -> " . $db_conn->error;
		
		set_error_response( 202 , $error_str );
	}
*/
	if(!($result = $db_conn->query($get_user_info_sql))) {
		
		$error_str = "I couldn't execute the statement with the SQL -> " . $get_user_info_sql . "... SQL Error -> " . $db_conn->error;
		
		set_error_response( 203 , $error_str );
	}
	
	if($result->num_rows != 1) {
		$error_str = "There was something off about the number of rows..." . " The number of rows was -> " . $result->num_rows . "\n";
		
		set_error_response( 203 , $error_str );
	}
	
	if(!($result_row = $result->fetch_array(MYSQLI_ASSOC))) {
		
		$error_str = "I could not get the result row from the prepared statement...";
		
		set_error_response( 204 , $error_str );
	}
	
	$user_info = array(
		"ps_id" 	=> $result_row["ps_id"],
		"username" 	=> $result_row["username"],
		"email"		=> $result_row["email"]
	);
	
	/*
		CREATE THE REPOSITORY
	*/
	$rep_name 			= $repo_info["name"];
	$rep_description 	= $repo_info["description"];
	
	$create_new_rep_sql = "INSERT INTO repository ( name , description ) VALUES ( ? , ? )";
	
	//	Prepare the statement
	if(!($create_new_rep_stmt = $db_conn->prepare($create_new_rep_sql))) {
		
		$error_str = error_string_for_statement_prepare( $create_new_rep_sql , $db_error->error );
		
		set_error_response( 203 , $error_str );
	}
	
	
	//	Bind the parameters
	if(!($create_new_rep_stmt->bind_param("ss", $rep_name, $rep_description))) {
		
		$error_str = error_string_for_param_bind( $create_new_rep_sql , $db_conn->error );
		
		set_error_response( 203 , $error_str );
	}
	
	//	Execute the statement
	
	if($create_new_rep_stmt->execute()) {
		
		//	Prepare the statement to insert into the family repository table 
		$last_rep_insert_id = $create_new_rep_stmt->insert_id;
		$family_name = $repo_info["family_name"];
		
		$create_new_family_repo_sql = "INSERT INTO family_repository ( r_id , family_name ) VALUES ( ? , ? )";
		
		if (!($create_new_family_repo_stmt = $db_conn->prepare($create_new_family_repo_sql))) {
			
			$error_str = error_string_for_statement_prepare( $create_new_family_repo_sql, $db_conn->error );
			
			set_error_response( 203 , $error_str );
			
		}
		
		//	Bind the parameters
		if (!($create_new_family_repo_stmt->bind_param("is", $last_rep_insert_id, $family_name))) {
			
			$error_str = error_string_for_param_bind( $create_new_family_repo_sql , $db_conn->error );
			
			set_error_response( 203 , $error_str );
		}
		
		
		//	Execute the statement
		if(!($create_new_family_repo_stmt->execute())) {
			
			$error_str = error_string_for_statement_execute( $create_new_family_repo_sql , $db_conn->error );
			
			set_error_response( 204 , $error_str );
		}
		
		
		
		/*
			Now that the family repository has been created we can add all the pictures to it
		*/
		
		//	Grab all the photographs from the photograph table
		
		$get_all_photos_sql = "SELECT P.p_id FROM photograph P";
		
		$all_photo_ids = array();
		
		if($result = $db_conn->query($get_all_photos_sql)) {
			
			while($result_row = $result->fetch_array(MYSQLI_ASSOC)) {
				
				$photo_id = $result_row["p_id"];
				
				array_push($all_photo_ids, $photo_id);
			}
		}
		
		
		$repository_id = $last_rep_insert_id;
		
		/*
			Make sure that you add the user repository relationship to the user_repo table
		*/
		
		$user_repo_sql = "INSERT INTO user_repo ( ps_id , r_id , permission_level ) VALUES ( " . $user_info["ps_id"] . " , " . $repository_id . " , 1 )";
		
		if(!($db_conn->query($user_repo_sql))) {
			custom_echo( "I couldn't insert the user repo relationship into the database");
			exit(-1);
		}
		
		//	Insert all the photographs into the repository
		
		$insert_photographs_for_repo_sql = "INSERT INTO photo_repo ( r_id , p_id ) VALUES ( ? , ? )";
		
		if(!($insert_photographs_for_repo_stmt = $db_conn->prepare($insert_photographs_for_repo_sql))) {
			
			$error_str = error_string_for_statement_prepare( $insert_photographs_for_repo_sql, $db_conn->error );
			
			set_error_response( 203 , $error_str );
		}
		
		foreach( $all_photo_ids as $the_photo_id ) {
			
			$repo_id = $repository_id;
			$p_id = $the_photo_id;
			
			if($insert_photographs_for_repo_stmt->bind_param("ii", $repo_id, $p_id)) {
				
				if($insert_photographs_for_repo_stmt->execute()) {
					echo "\n I successfully inserted the photo with photo id -> $photo_id into the repository with r_id -> $repo_id !\n";					
				}
				else {
					echo "\n I couldn't insert the photo with photo id -> $photo_id into the repository with r_id -> $repo_id \n";
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	/*
		CUSTOM FUNCTIONS
	*/
	function custom_echo( $str ) {
		
		echo "\n$str\n";
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