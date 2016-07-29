<?php
	
	
	//	Include reference to sensitive databse information
	include("../../../../../db_security/security.php");
	include("./lib/PAFactory/PAFactory.php");
	
	
	$db_user = constant("DB_USER");
	$db_host = constant("DB_HOST");
	$db_pass = constant("DB_PASS");
	$db_database = constant("DB_DATABASE");
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
	
	if ($db_conn->error_code) {
		
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);
		die("The connection to the database failed: " . $db_conn->connect_error);
	}


	/*
		REQUEST HANDLING
	*/	
	$req_method = $_SERVER['REQUEST_METHOD'];		
	
	switch ($req_method) {
		
		case 'POST':
		
		
			//	Get the raw post data
			$json_raw = file_get_contents("php://input");
			
			if ($decoded_json = json_decode($json_raw, true)) {
				
				
				$registration_info = new PARegistrationInfo($decoded_json);
				
				if (!$registration_info->isValid) {
					set_error_response( 205, $registration_info->error);
				}
	
				//	Check to see if the username is already taken
				
				$username_check_sql = "SELECT * FROM user WHERE username = ?";
				
				$username_check_stmt = $db_conn->stmt_init();


				if (!($username_check_stmt = $db_conn->prepare($username_check_sql))) {
					set_error_response( 201, "SQL Error -> " . $username_check_stmt->error);
					break;
				}

				if (!($username_check_stmt->bind_param("s",  $registration_info->username()))) {
					set_error_response( 201, "SQL Error -> " . $username_check_stmt->error);
					break;
				}

				$username_is_valid = true;
								
				if ($username_check_stmt->execute()) {
					

					if ($username_check_result = $username_check_stmt->get_result()) {
						
						if ($username_check_result->num_rows > 0) {
							$username_is_valid = false;
						}
					}
					else {
						
						set_error_response( 201, "SQL Error -> " . $username_check_stmt->error);
					}
					
				}
				else {
					
					set_error_response( 201, "SQL Error -> " . $db_conn->error);
					break;
				}


				$username_check_stmt->close();
				
				if (!$username_is_valid) {
				
					set_error_response( 203 , "The username is already taken, please try another one.......");
					break;
				}
				
				//	If the information is valid then enter it into the database
			
				// insert the person into person table first
				$insert_new_person_sql = 'INSERT INTO person (fname, mname,	lname, maiden_name, gender, birthdate) VALUES (?, ?, ?, ?, ?, ?)';
	
				
				if (!($insert_new_person_stmt = $db_conn->prepare($insert_new_person_sql))) {
					set_error_response( 201, "SQL Error -> " . $insert_new_person_stmt->error);
					break;
				}
				$maiden = $registration_info->maiden_name();
				
				if (!($insert_new_person_stmt->bind_param(		"ssssss", 	
																$registration_info->first_name() , 
																$registration_info->middle_name() , 
																$registration_info->last_name() , 
																$registration_info->maiden_name() , 
																$registration_info->gender() , 
																$registration_info->birth_date()))) 
				{
					set_error_response( 201, "SQL Error -> " . $insert_new_person_stmt->error);
					break;
				}
	
				$last_insert_id;
	
				if ($insert_new_person_stmt->execute()) {
	
					$last_insert_id = $insert_new_person_stmt->insert_id;
					
				}
				else {
					
					set_error_response( 201, "SQL Error -> " . $insert_new_person_stmt->error);
					
				}
				
				$insert_new_person_stmt->close();											
								

				$saved_last_insert_id = $last_insert_id;
				
				//insert the user information into user table						
				$insert_new_user_sql = "INSERT INTO user (ps_id, username, email) VALUES (?, ? , ? )";
									
				$insert_new_user_stmt = $db_conn->prepare($insert_new_user_sql);
				
				$insert_new_user_stmt->bind_param("iss" , $last_insert_id, $registration_info->username() , $registration_info->email());
	
				if (!($insert_new_user_stmt = $db_conn->prepare($insert_new_user_sql))) {
					
					set_error_response( 201, "SQL Error -> " . $insert_new_user_stmt->error);
					
					break;
				}
					
				if (!($insert_new_user_stmt->bind_param("iss" , $last_insert_id, $registration_info->username() , $registration_info->email()))) {
					
					set_error_response( 201, "SQL Error -> " . $insert_new_user_stmt->error);
					
					break;
				}
	

				if ($insert_new_user_stmt->execute()) {
					
					//	Set that users salt and hash

					$salt = sha1( mt_rand() );
					
					$hash = sha1( $req_password.$salt );
					

					$insert_user_auth_sql = "INSERT INTO user_auth (ps_id , pass_hash, pass_salt) VALUES (?, ?, ?)";

					$insert_user_auth_stmt = $db_conn->stmt_init();

					if(!$insert_user_auth_stmt->prepare($insert_user_auth_sql)){
						
						set_error_response( 201, "SQL Error -> " . $insert_user_auth_stmt->error);

						break;

					}

					if(!$insert_user_auth_stmt->bind_param("iss", $last_insert_id, $hash, $salt)){

						set_error_response( 201, "SQL Error -> " . $insert_user_auth_stmt->error);

						break;
					} 

					if(!$insert_user_auth_stmt->execute()){
						
						set_error_response( 201, "SQL Error -> " . $insert_user_auth_stmt->error);

						break;

					}

					$insert_user_auth_stmt->close();


					// record register information into activity log table

					$insert_log_sql = "INSERT INTO activity_log (ps_id, ac_type) VALUES (?, ?)";

					$insert_log_stmt = $db_conn->stmt_init();

					$insert_log_stmt->prepare($insert_log_sql);

					$ac_type= "user-register";

					$insert_log_stmt->bind_param("is", $last_insert_id, $ac_type);

					if($insert_log_stmt->execute()) {

					}

					else {

						set_error_response( 201, "SQL Error -> " . $insert_log_stmt->error);

					}

					http_response_code(200);
					echo $registration_info->getJSONString();

				}
	
				else {
					
					set_error_response( 201, "SQL Error -> " . $insert_new_user_stmt->error);
					
				}
				
				$insert_new_user_stmt->close();

			}
	
			else {
	
				echo "info package decode error....";
			
			}

			$db_conn->close();
		
		break;
		
		
		default:
		
			set_error_response( 303, "Wrong request method....");
			$db_conn->close();

		break;
	}	
	
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
