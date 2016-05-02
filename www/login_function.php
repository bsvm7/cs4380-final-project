<?php


	//session_start(); // Starting Session
	include("../../db_security/security.php");
	//include('./api/authorizate.php');
	//$error=''; // Variable To Store Error Message

	$db_user = constant("DB_USER");
	$db_host = constant("DB_HOST");
	$db_pass = constant("DB_PASS");
	$db_database = constant("DB_DATABASE");
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
	
	echo "database connected" . "\n";


	if ($db_conn->error_code) {
		
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
		
	
	$req_method = $_SERVER['REQUEST_METHOD'];		
		
	switch ($req_method) {
		
		case 'POST':

			//	Get the raw post database
			$json_raw = file_get_contents("php://input");

			if ($decoded_json = json_decode($json_raw, true)) {	

				$username=$decoded_json['username'];
				$password=$decoded_json['password'];


				if (empty($username) || empty($password)) {
					//$error = "Username or Password is empty";
					echo "Username or Password is empty"."\n";
				}

				else {
					
					echo "username is ".$username."\n";
					echo "password is ".$password."\n";


					$hash_retrieve_sql = 'SELECT U.ps_id, U.username, UA.pass_hash, UA.pass_salt FROM user U, user_auth UA WHERE U.ps_id=UA.ps_id AND U.username= ?';

					$hash_retrieve_stmt = $db_conn->stmt_init();

					if (!($hash_retrieve_stmt = $db_conn->prepare($hash_retrieve_sql))) {

						set_error_response( 201, "SQL Error -> " . $hash_retrieve_stmt->error);

						break;
					}							

					echo "user hash retrieve statement prepared"."\n";

					$hash_retrieve_stmt->bind_param("s" , $username);
				
					if (!$hash_retrieve_stmt->execute()) {
				
						echo "Error" . " " . $stmt->error;
				
					}
					
					if ($hash_retrieve_result = $hash_retrieve_stmt->get_result())
						{
							if($hash_retrieve_stmt->num_rows != 1) {

								set_error_response( 201, "SQL Error -> " . $hash_retrieve_stmt->error);	

								break;

							}

							$row = $hash_retrieve_result->fetch_array(MYSQLI_NUM);
							
							$result_ps_id = $row[0];
							$result_username = $row[1];
							$result_hash = $row[2];
							$result_salt = $row[3];
							
							$computed_hash = sha1($result_salt.$password);
							
							if ($computed_hash == $result_hash) {								
								
								$random_string = generate_255_char_random_string();								
								
								$insert_token_sql = "INSERT INTO user_auth_token (issued_to, token) VALUES ( ? , ? )";								
				
								$insert_token_statement = $db_conn->stmt_init();
								
								$insert_token_statement->prepare($insert_token_sql);
								
								$insert_token_statement->bind_param("is", $result_ps_id, $random_string);
								
								if ($insert_token_statement->execute()) {									
									
									$resp_array = array();							
							
									$resp_array["ps_id"] = $result_ps_id;
									$resp_array["username"] = $result_username;
									$resp_array["auth_token"] = $random_string;
									$resp_array["expires_in"] = 10;									
									
									http_response_code(200);
									
									echo json_encode($resp_array);
								}
								else
								{
									set_error_response( 13, "SQL Error" . $insert_token_statement->error);
								}
								
							}							
						}
						else
						{
							set_error_response( 11, "SQL Error");
							break;
						}






					// check to see if username and user hash are valid
					$user_check_sql='SELECT U.username, UA.pass_hash FROM user U, user_auth UA WHERE user.username= ? AND user.pass_hash= ?';
				
					$username_check_stmt = $db_conn->stmt_init();

					if (!($user_check_stmt = $db_conn->prepare($user_check_sql))) {
						set_error_response( 201, "SQL Error -> " . $user_check_stmt->error);

						break;
					}							

					echo "user check statement works"."\n";

					if (!($user_check_stmt->bind_param("sss", $username, $password))) {
						set_error_response( 201, "SQL Error -> " . $user_check_stmt->error);
						break;
					}
					echo "user check param binding works"."\n";

					$user_is_valid = false;

					if ($user_check_stmt->execute()) {

						echo "user check statement execution works"."\n";
		
						if($user_check_result = $user_check_stmt->get_result() ){

							if($user_check_result->num_rows == 1) {
		
								$user_is_valid = true;
		
							}

							else {
								$error = "Username or Password is invalid";
							}
						}

						else {
							
							set_error_response( 201, "SQL Error -> " . $user_check_stmt->error);
						}

					}

					else {
							
						set_error_response( 201, "SQL Error -> " . $db_conn->error);
						break;
					}
			
					$user_check_stmt->close();

					$db_conn->close; // Closing Connection
			
				}

			}

			
			else {

				echo "no input from user"."\n";
			}
		
		break;
		
		default:

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
