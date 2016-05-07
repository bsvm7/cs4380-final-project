<?php


	//session_start(); // Starting Session
	include("../../../db_security/security.php");
	//include('./api/authorize.php');
	//$error=''; // Variable To Store Error Message

	$db_user = constant("DB_USER");
	$db_host = constant("DB_HOST");
	$db_pass = constant("DB_PASS");
	$db_database = constant("DB_DATABASE");
	
	// connect to the database 
	$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_database);					
	
	if ($db_conn->error_code) {
		
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);

		die("The connection to the database failed: " . $db_conn->connect_error);
	}
			
	//echo "database connected" . "\n";		

	$req_method = $_SERVER['REQUEST_METHOD'];		
		
	switch ($req_method) {
		
		case 'POST':

			//	Get the raw post database
			$json_raw = file_get_contents("php://input");

			if ($decoded_json = json_decode($json_raw, true)) {	

				$access_token=$decoded_json['access_token'];

				echo "access token you entered is ".$access_token."\n";

				if (empty($access_token)) {

					//echo "access_token is needed"."\n";

					break;
				}

				else {

					// retrieve the access token for the current user from database
					$token_retrieve_sql = 'SELECT * FROM user_auth_token UAT WHERE access_token = ?';

					$token_retrieve_stmt = $db_conn->stmt_init();

					if (!($token_retrieve_stmt = $db_conn->prepare($token_retrieve_sql))) {

						set_error_response( 201, "SQL Error -> " . $token_retrieve_stmt->error);

						break;
					}							

					//echo "token retrieve statement prepared"."\n";

					if (!$token_retrieve_stmt->bind_param("s" , $access_token)) {

						set_error_response( 201, "SQL Error -> " . $token_retrieve_stmt->error);

						break;
					}
				
					//echo "token retrieve statement param bind worked"."\n";


					if (!$token_retrieve_stmt->execute()) {
				
						set_error_response( 201, "SQL Error -> " . $token_retrieve_stmt->error);

						break;				
					}
					//echo "token retrieve statement execution worked"."\n";

					// compare the access token provided and the one retrived from database
					if ($token_retrieve_result = $token_retrieve_stmt->get_result()) {

						/*
						// this has not worked out yet
						if($token_retrieve_result->num_rows != 1) {

							set_error_response( 201, "SQL Error -> " . $token_retrieve_stmt->error);	

							break;

						}

						echo "only one user record found"."\n";
						*/

						$row = $token_retrieve_result->fetch_array(MYSQLI_NUM);

						$result_ps_id = $row[0];						
						$result_access_token = $row[1];
						//$result_refresh_token = $row[2];
					
						//echo "token need to be compared"."\n";

					
						if ($result_access_token == $access_token) {

							//echo "access token matched ";

							// insert logout activity in activity log table
							$insert_log_sql = "INSERT INTO activity_log (ps_id, ac_type) VALUES ( ? , ?)";	
			
									//echo "insert log sql worked"."\n";

							$insert_log_stmt = $db_conn->stmt_init();

							
							$insert_log_stmt->prepare($insert_log_sql);

							//echo "insert log stmt prepared"."\n";


							$ac_type="logout";
							
							$insert_log_stmt->bind_param("is", $result_ps_id, $ac_type);

							
							if ($insert_log_stmt->execute()) {	

								//echo "insert log stmt executed"."\n";


								// delete access token and refresh token from user_auth_token table

								$delete_token_sql = "DELETE FROM user_auth_token WHERE ps_id = ?";	
	
								$delete_token_stmt = $db_conn->stmt_init();
								
								if(!$delete_token_stmt->prepare($delete_token_sql)){
									
									set_error_response( 201, "SQL Error -> " . $delete_token_stmt->error);

									break;
								}

									//echo "delete tokenstmt prepared"."\n";

								
								if(!$delete_token_stmt->bind_param("i", $result_ps_id)){

									set_error_response( 201, "SQL Error -> " . $delete_token_stmt->error);

									break;

								}

									//echo "delete tokenstmt param bind worked"."\n";


								if (!$delete_token_stmt->execute()) {

									set_error_response( 201, "SQL Error -> " . $delete_token_stmt->error);

									break;								

								}
								else{
									
									//header('Location: ');
									//echo "You have successfully logged out"."\n";

								}		
							}
							else
							{
								set_error_response( 13, "SQL Error" . $insert_token_statement->error);
							}
							
						}
						else{

							//echo "tokens do not match! .....";
						}							
					}
					else
					{
						set_error_response( 11, "SQL Error");
						break;
					}
			
				}
								
			}

			else {

				//echo "no input from user"."\n";
			
			}

			$db_conn->close; 

		
		break;
		
		default:

			$db_conn->close; 

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

?>
