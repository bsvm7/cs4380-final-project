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


					// check to see if username and password are valid
					$user_check_sql='SELECT * FROM user WHERE user.username= ? AND user.password= ?';
				
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


?>
