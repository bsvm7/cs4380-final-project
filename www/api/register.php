<?php
	
	
	//	Include reference to sensitive databse information
	include("../../../db_security/security.php");
	
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

				echo "\nThe firstname of the user is " . $decoded_json["firstname"] . "\n";

				//	Clean birthdate data
				
				$clean_birthdate_info = clean_date( $birthdate );
				
				if(!$clean_birthdate_info["isValidDate"]) {
					set_error_response( 205 , "The birthdate you passed was invalid..." );
					break;
				}
				else {
					$birthdate = $clean_birthdate_info["validDateString"];
					echo "birthdate clean works"."\n";
				}
				
				// check to see if the person is already in the person table
				$person_name_check_sql = 'SELECT * FROM person where person.fname= ? AND person.mname= ? AND person.lname= ?';

				if (!($person_name_check_stmt = $db_conn->prepare($person_name_check_sql))) {
					set_error_response( 201, "SQL Error -> " . $person_name_check_stmt->error);

					break;
				}	

				echo "name check statement works"."\n";

				if (!($person_name_check_stmt->bind_param("sss", $req_fname, $req_mname, $rea_lname))) {
					set_error_response( 201, "SQL Error -> " . $person_name_check_stmt->error);
					break;
				}
				echo "name check param binding works"."\n";

				$person_name_is_valid = true;
				

				if ($person_name_check_stmt->execute()) {

					echo "name check statement execution works"."\n";
	
					if($person_name_check_result = $person_name_check_stmt->get_result()) {
	
						if($person_name_check_result->num_rows > 0) {
	
							$person_name_is_valid = false;
	
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
	
				$person_name_check_stmt->close();
	
				if (!$person_name_is_valid) {
					
					set_error_response( 203 , "The person with the same name already exists in the database");
					break;
				}

				echo "name check worked"."\n";

	
				//	Check to see if the username is already taken
				
				$username_check_sql = "SELECT * FROM user WHERE username = ?";
				
				$username_check_stmt = $db_conn->stmt_init();
				echo "username check statement works"."\n";


				if (!($username_check_stmt = $db_conn->prepare($username_check_sql))) {
					set_error_response( 201, "SQL Error -> " . $username_check_stmt->error);
					break;
				}
								echo "username check statement prepare works"."\n";
/*
				if (!($username_check_stmt->bind_param("s",  $req_username)) {
					set_error_response( 201, "SQL Error -> " . $username_check_stmt->error);
					break;
				}
						echo "username check statement param binding works"."\n";

				$username_is_valid = true;
				
				if ($username_check_stmt->execute()) {
					
						echo "username check statement execution works"."\n";

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
				
					set_error_response( 203 , "The username is already taken");
					break;
				}
	
				//	If the information is valid then enter it into the database
			
				// insert the person into person table first
				$insert_new_person_sql = 'INSERT INTO person (fname, mname,	lname, maiden_name, gender, birthdate) VALUES (?, ?, ?, ?, ?, ?)';
	
	
				if (!($insert_new_person_stmt = $db_conn->prepare($insert_new_person_sql))) {
					set_error_response( 201, "SQL Error -> " . $insert_new_person_stmt->error);
					break;
				}
				
				if (!($insert_new_person_stmt->bind_param("ssssss", $req_fname, $req_mname, $req_lname, $req_maiden_name, $req_gender, $req_birthdate))) {
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
				
				$insert_new_user_stmt->bind_param("iss" , $last_insert_id, $req_username , $req_email);
	
				if (!($insert_new_user_stmt = $db_conn->prepare($insert_new_user_sql))) {
					
					set_error_response( 201, "SQL Error -> " . $insert_new_user_stmt->error);
					
					break;
				}
					
				if (!($insert_new_user_stmt->bind_param("iss" , $last_insert_id, $req_username , $req_email))) {
					
					set_error_response( 201, "SQL Error -> " . $insert_new_user_stmt->error);
					
					break;
				}
	
	
				if ($insert_new_user_stmt->execute()) {
					
	
					//	Set that users salt and password
					
					$salt = sha1( mt_rand() );
					
					$hash = sha1( $salt . $req_password );
					
					$insert_user_auth_sql = "INSERT INTO user_authentication (ps_id , password_hash, salt) VALUES ('$saved_last_insert_id' , '$hash' , '$salt' )";
					
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
					
					
					
					$issued_to = $saved_last_insert_id;
					$auth_token = generate_64_char_random_string();
					
					
					$insert_auth_token_query = "INSERT INTO user_auth_tokens (issued_to, token) VALUES ('$issued_to', '$auth_token')";
					
					if ($db_conn->query($insert_auth_token_query)) {
	
						//	Return the persons information
					
						http_response_code(200);
					
						$ret_auth_info = array(
							"uid" => $issued_to,
							"auth_token" => $auth_token,
							"expires_in" => 15
						);
						
						$ret_user_info = array(
							
							"ps_id" => $saved_last_insert_id,
							"username" => $req_username,
							"email" => $req_email,
							"first_name" => $req_fname,
							"middle_name" => $req_mname,
							"last_name" => $req_lname,
							"maiden_name" => $req_maiden_name,
							"birth_date" => $req_birthdate,
							"gender" => $req_gender
						);
						
						$ret_arr = array(
							"auth_info" => $ret_auth_info,
							"user_info" => $ret_user_info
						);
						
						echo json_encode($ret_arr);
					}
	
					else {
						set_error_response( 201, "SQL Error -> " . $db_conn->error);
					}
					
				}
	
				else {
					
					set_error_response( 201, "SQL Error -> " . $insert_new_user_stmt->error);
					
				}
				
				$insert_new_user_stmt->close();

*/

			}
	
			else {
	
				echo "info package decode error";
			
			}


		
		break;
		
		
		
		
		default:
		
			set_error_response( 303, "Wrong request method...");
		
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
	
	/*
	function print_result_values( $result ) {
		
		$num_fields = $result->field_count;
		
		while ($row = $result->fetch_row()) {
			
			echo "\n";
			for ($i = 0; $i < $num_fields; $i++) {
				
				echo $row[$i] . "\t\t";
			}	
		}
		
		echo "\n";
	}
	
	function print_result_headers( $result ) {
		
		echo "\n";
		
		$num_fields = $result->field_count;
		
		$fields = $result->fetch_fields();
		
		for ($i = 0; $i < $num_fields; $i++) {
			echo $fields[$i]->name . "\t\t";
		}	
	}
	function print_result_all( $result ) {
		
		print_result_headers( $result );
		print_result_values( $result );
	}
	//
	//	Random Utility Functions
	//
	function execute_placeholder_query( $db_conn ) {
		
		//	First prepare the SQL query
		$query_string = "SELECT * FROM user";
		
		if ($result = $db_conn->query($query_string)) {
		
			
			print_result_all( $result );
			
			
		}
		else {
			echo "Couldn't prepare the statement";
		}
	}	
	//
	//	Error Handling
	//

	function handle_request_error() {	
		
		
	}
*/
	
?>
