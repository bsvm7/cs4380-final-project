<?php
	//	Include the file with the database connect credentials
	include("../../../db_security/security.php");

	//	First connect to the database using values from the included file
	$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
	
	if ($db_conn->error_code) {
		
		//	This should be replace PHP that sets the HTTP status code to 500 and
		//	sets the body to the JSON object that contains the error_code and
		//	error_string as defined by the API
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
	
	
		/*
		REQUEST HANDLING
	*/
	
	$req_method = $_SERVER['REQUEST_METHOD'];
	
	switch ($req_method) {
		
		case 'GET':
			//	Check the authorization_type (either 'initial' or 'refresh'
			
			if (isset($_GET['username'])) {
				
				if ($_GET['authtype'] == "initial") {
										
					$username = $_GET['username'];
					$password = $_GET['password'];
					
					
					$stmt = $db_conn->stmt_init();
					
					$sql_query = "SELECT U.ps_id, U.username, UA.pass_hash as hash, UA.pass_salt as salt FROM user U, user_auth UA WHERE U.ps_id = UA.ps_id AND U.username = ?  LIMIT 1";
					
					if ($stmt->prepare($sql_query)) {
						
						$stmt->bind_param("s" , $username);
						if (!$stmt->execute()) {
							echo "Error" . " " . $stmt->error;
						}
					
						
						if ($result = $stmt->get_result())
						{
							//	Check to make sure the row count is equal to one
							
							$row = $result->fetch_array(MYSQLI_NUM);
							
							$result_uid = $row[0];
							$result_username = $row[1];
							$result_hash = $row[2];
							$result_salt = $row[3];
							
							$computed_hash = sha1($result_salt.$password);
							
							if ($computed_hash == $result_hash) {								
								
								$random_string = generate_255_char_random_string();
								
								
								$insert_token_sql = "INSERT INTO user_auth_tokens (issued_to, token) VALUES ( ? , ? )";
								
				
								$insert_statement = $db_conn->stmt_init();
								
								$insert_statement->prepare($insert_token_sql);
								
								$insert_statement->bind_param("is", $result_uid, $random_string);
								
								if ($insert_statement->execute()) {
									
									
									$resp_array = array();
							
							
									$resp_array["ps_id"] = $result_uid;
									$resp_array["username"] = $result_username;
									$resp_array["auth_token"] = $random_string;
									$resp_array["expires_in"] = 10;
									
									
									http_response_code(200);
									
									echo json_encode($resp_array);
								}
								else
								{
									set_error_response( 13, "SQL Error" . $insert_statement->error);
								}
								
							}							
						}
						else
						{
							set_error_response( 11, "SQL Error");
							break;
						}
						
						
						
					}
					else
					{
						set_error_response( 10, "The proper SQL statement could not be prepared");
						break;
					}
					
				}
				else if ($_GET['auth_type'] == "refresh") {
					
					
					
					
					
					
					
				}
				else {
					set_error_response( 5, "You set an invalid value for the 'auth_type' key");
				}
				
			}
			else {
				set_error_response( 4, "The auth parameter was not properly set");
			}
		break;
		
		default:
			
			//	Execute the placeholder query
			//execute_placeholder_query( $db_conn );
			
			//	The authorization endpoint is only set up to handle GET requests. All other types should throw an error.
			handle_request_error();
		break;
	}
	
	
	
	
	/*
		UTILITY FUNCTIONS
	*/
	
	
	function generate_255_char_random_string() {
		
		$length = 64;
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
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
	
	
	
	//	PHP automatically closes the connection to the DB when the script is finished running
	//	so you don't have to do this yourself.
	


?>
