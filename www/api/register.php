<?php
	
	
	//	Include reference to sensitive databse information
	include("../../../db_security/security.php");
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
		
	echo "database connected" . "\n";


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
				
				var $test_arr = array();
				
				array_push($test_arr, $registration_info->birth_date());
				array_push($test_arr, $registration_info->first_name());
				array_push($test_arr, $registration_info->middle_name());
				//array_push($test_arr, $registration_info->maiden_name());
				array_push($test_arr, $registration_info->username());
				array_push($test_arr, $registration_info->password());
				array_push($test_arr, $registration_info->email());
				
				echo var_dump($test_arr);
/*
			
				//PULL AND CLEAN ALL DATA FROM JSON POST
				
				$req_fname 		 = $decoded_json["firstname"];
				$req_lname 		 = $decoded_json["lastname"];
				$req_mname 		 = $decoded_json["middlename"];
				$req_maiden_name = $decoded_json["maiden_name"];
				$req_birthdate 	 = $decoded_json["birthdate"];
				$req_gender 	 = $decoded_json["gender"];
				$req_password 	 = $decoded_json["password"];
				$req_email		 = $decoded_json["email"];
				$req_username	 = $decoded_json["username"];

								//	Clean birthdate data
				
				$clean_birthdate_info = clean_date( $birthdate );
				
				if(!$clean_birthdate_info["isValidDate"]) {
					set_error_response( 205 , "The birthdate you passed was invalid..." );
					break;
				}
				else {
					$birthdate = $clean_birthdate_info["validDateString"];
				}
*/
				
				// check to see if the person is already in the person table
				$person_name_check_sql = 'SELECT * FROM person where person.fname= ? AND person.mname= ? AND person.lname= ?';

				if (!($person_name_check_stmt = $db_conn->prepare($person_name_check_sql))) {
					set_error_response( 201, "SQL Error -> " . $person_name_check_stmt->error);

					break;
				}	


				if (!($person_name_check_stmt->bind_param("sss", $registration_info->first_name(), $registration_info->middle_name(), $registration_info->last_name()))) {
					set_error_response( 201, "SQL Error -> " . $person_name_check_stmt->error);
					break;
				}

				$person_name_is_valid = true;
				

				if ($person_name_check_stmt->execute()) {

	
					if($person_name_check_result = $person_name_check_stmt->get_result()) {
	
						if($person_name_check_result->num_rows > 0) {
	
							$person_name_is_valid = false;
	
						}
	
					}
	
					else {
							
							set_error_response( 201, "SQL Error -> " . $person_name_check_stmt->error);
					}	
				}	
	
				else {
						
						set_error_response( 201, "SQL Error -> " . $db_conn->error);
						break;
				}
	
				$person_name_check_stmt->close();
	
				if (!$person_name_is_valid) {
					
					set_error_response( 203 , "The person with the same name already exists in the database, sorry!"."\n");
					break;
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

				if (!($insert_new_person_stmt->bind_param("ssssss", $registration_info->first_name(), $registration_info->middle_name(), $registration_info->last_name(), "nobody", $registration_info->gender() , $registration_info->birth_date()))) {
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
					
					/* bad programming here
					$insert_user_auth_sql = "INSERT INTO user_auth (ps_id , pass_hash, pass_salt) VALUES ('$saved_last_insert_id' , '$hash' , '$salt' )";
					if ($db_conn->query($insert_user_auth_sql)) {
						if ($db_conn->affected_rows == 1) {							
						}
						else {
							set_error_response( 201, "SQL Error 2 -> " . $db_conn->error);
							break;
						}						
					}
					else {
						set_error_response( 201, "SQL Error 1 -> " . $db_conn->error);
						break;
					}
					*/

					$insert_user_auth_sql = "INSERT INTO user_auth (ps_id , pass_hash, pass_salt) VALUES (?, ?, ?)";
											echo "user auth sql worked "."\n"; 

					$insert_user_auth_stmt = $db_conn->stmt_init();
					
						echo "user auth sql worked "."\n"; 

					if(!$insert_user_auth_stmt->prepare($insert_user_auth_sql)){
						
						set_error_response( 201, "SQL Error -> " . $insert_user_auth_stmt->error);

						break;

					}
						echo "user auth stmt prepare worked "."\n"; 

					if(!$insert_user_auth_stmt->bind_param("iss", $last_insert_id, $hash, $salt)){

						set_error_response( 201, "SQL Error -> " . $insert_user_auth_stmt->error);

						break;
					}
						echo "user auth stmt param bind worked "."\n"; 

					if(!$insert_user_auth_stmt->execute()){
						
						set_error_response( 201, "SQL Error -> " . $insert_user_auth_stmt->error);

						break;

					}

						echo "user auth stmt execution worked "."\n"; 

					/*
					if ($db_conn->affected_rows == 1) {							
					}
					else {
						set_error_response( 201, "SQL Error 2 -> " . $db_conn->error);
						break;
					}						
					*/

						
						echo $registration_info->getJSONString();

						$insert_user_auth_stmt->close();

						// record register information into activity log table

						echo "last insert_id is ".$last_insert_id; 

						$insert_log_sql = "INSERT INTO activity_log (ps_id, ac_type) VALUES (?, ?)";

						$insert_log_stmt = $db_conn->stmt_init();

						$insert_log_stmt->prepare($insert_log_sql);

						$ac_type= "user-register";

						$insert_log_stmt->bind_param("is", $last_insert_id, $ac_type);

						if($insert_log_stmt->execute()) {

							echo "registration activity has been logged"."\n";

						}

						else {

							set_error_response( 201, "SQL Error -> " . $insert_log_stmt->error);

						}

					

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
	
	
	/*
		UTILITY FUNCTIONS
	*/
	function clean_date( $date_string ) {
		
		$is_valid_date = true;
		
		$clean_date_string;
		
		$pieces = explode("-", $date_string);
		
		
		if (count($pieces) == 3) {
			
			$year = intval($pieces[2]);
			$valid_year = true;
			$month = intval($pieces[0]);
			$valid_month = true;
			$day = intval($pieces[1]);
			$valid_day = true;
			
			$min_age = 15;
			
			
			$max_year = intval(date("Y")) - $min_age;
			
			if (!($year >= 1900 && $year < $max_year)) {
				$valid_year = false;
			}
			
			if (!($month >= 1 && $month <= 12)) {
				$valid_month = false;
			}
			
			if ($valid_year && $valid_month) {
				
				$max_day = cal_days_in_month(CAL_GREGORIAN, $valid_month, $valid_year);
				
				
				if ($day > 0 && $day <= $max_day) {
					$is_valid_date = true;
				}
			}
			else {
				$is_valid_date = false;
			}
			
			if ($is_valid_date) {
				
				$month_string;
				$day_string;
				
				
				if ($day < 10) {
					$day_string = "0" . $day;
				}
				else {
					$day_string = "$day";
				}
				
				if ($month < 10) {
					$month_string = "0" . $month;
				}
				else {
					$month_string = "$month";
				}
				
				$clean_date_string = $year . "/" . $month_string . "/" . $day_string;
			}
		}
		
		$ret_array = array(
			"isValidDate" => $is_valid_date,
			"validDateString" => $clean_date_string
		);

		return $ret_array;
	}

/*
	function generate_64_char_random_string() {
		
		$length = 64;
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

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
