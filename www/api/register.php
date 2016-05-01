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
				/*
				$clean_birthdate_info = clean_date( $birthdate );
				
				if(!$clean_birthdate_info["isValidDate"]) {
					set_error_response( 205 , "The birthdate you passed was invalid..." );
					break;
				}
				else {
					$birthdate = $clean_birthdate_info["validDateString"];
				}
				*/

/*
				// check to see if the person is already in the person table
				$person_name_check_sql = 'SELECT * FROM person where person.fname= ? AND person.mname= ? AND person.lname= ?';
	
				$person_name_check_stmt = $db_conn->prepare($check_person_name_sql);
	
				$person_name_check_stmt->bind_param("ss", $req_fname, $req_mname, $rea_lname);
	
				if (!($person_name_check_stmt = $db_conn->prepare($person_name_check_sql))) {
					set_error_response( 201, "SQL Error -> " . $person_name_check_stmt->error);
					break;
				}
				
				if (!($person_name_check_stmt->bind_param("ss", $req_fname, $rea_lname))) {
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
	

	
?>
