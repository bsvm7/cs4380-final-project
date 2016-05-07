<?php


	//session_start(); // Starting Session
	include("../../../db_security/security.php");
	//include('./api/authorize.php');
	//$error=''; // Variable To Store Error Message

	$db_user = constant("DB_USER");
	$db_host = constant("DB_HOST");
	$db_pass = constant("DB_PASS");
	$db_database = constant("DB_DATABASE");
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
	
// 	echo "database connected" . "\n";


	if ($db_conn->error_code) {
		
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
		
	
	$req_method = $_SERVER['REQUEST_METHOD'];		
		
	switch ($req_method) {
		
		case 'POST':

			//if ($_POST['authtype'] == "initial") {

			//	Get the raw post database
			$json_raw = file_get_contents("php://input");
			
// 			echo $json_raw;
			
			if ($decoded_json = json_decode($json_raw, true)) {	

				$auth_type=$decoded_json['auth_type'];
				$username=$decoded_json['username'];
				$password=$decoded_json['password'];
				$refresh_token=$decoded_json['refresh_token'];

				if($auth_type == 'initial') {

					if (empty($username) || empty($password)) {
						//$error = "Username or Password is empty";
						echo "Username or Password is empty"."\n";
					}

					else {
						
						$hash_retrieve_sql = 'SELECT U.ps_id, U.username, UA.pass_hash, UA.pass_salt FROM user U, user_auth UA WHERE U.ps_id=UA.ps_id AND U.username= ?';

						$hash_retrieve_stmt = $db_conn->stmt_init();

						if (!($hash_retrieve_stmt = $db_conn->prepare($hash_retrieve_sql))) {

							set_error_response( 201, "SQL Error -> " . $hash_retrieve_stmt->error);

							break;
						}							

						$hash_retrieve_stmt->bind_param("s" , $username);
					
						if (!$hash_retrieve_stmt->execute()) {
					
							echo "Error" . " " . $stmt->error;
					
						}

					
						if ($hash_retrieve_result = $hash_retrieve_stmt->get_result()) {

							
							// this has not worked out yet
							if($hash_retrieve_result->num_rows > 1) {

								set_error_response( 201, "SQL Error -> " . $hash_retrieve_stmt->error);	

								break;

							}

							if($hash_retrieve_result->num_rows == 0) {

								echo "The username does not exist in our system, please try again....."."\n";

								break;

							}


							$row = $hash_retrieve_result->fetch_array(MYSQLI_NUM);
							
							$result_ps_id = $row[0];
							$result_username = $row[1];
							$result_hash = $row[2];
							$result_salt = $row[3];
							
							$computed_hash = sha1($password . $result_salt);
							
							if ($computed_hash == $result_hash) {

								$random_string1 = generate_255_char_random_string();								
		
								$random_string2 = generate_255_char_random_string();	

								$insert_token_sql = "INSERT INTO user_auth_token (ps_id, access_token, refresh_token) VALUES ( ? , ? , ?)";								
				
								$insert_token_statement = $db_conn->stmt_init();
								
								$insert_token_statement->prepare($insert_token_sql);
								
								$insert_token_statement->bind_param("iss", $result_ps_id, $random_string1, $random_string2);
								
								if ($insert_token_statement->execute()) {									
									
									$resp_array = array();							
							
									$resp_array["ps_id"] = $result_ps_id;
									$resp_array["username"] = $result_username;
									$resp_array["access_token"] = $random_string1;
									$resp_array["expires_in"] = 86400;	
									$resp_array["refresh_token"] = $random_string2;
									
									http_response_code(200);
									
									echo json_encode($resp_array);


									// record login information into activity log table

									$insert_log_sql = "INSERT INTO activity_log (ps_id, ac_type) VALUES (?, ?)";

									$insert_log_stmt = $db_conn->stmt_init();

									$insert_log_stmt->prepare($insert_log_sql);

									$ac_type="login";

									$insert_log_stmt->bind_param("is", $result_ps_id, $ac_type);

									if($insert_log_stmt->execute()) {

// 										echo "login activity has been logged"."\n";


										$user_level_check_sql = 'SELECT U.user_level FROM user U WHERE U.ps_id= ?';

										$user_level_check_stmt = $db_conn->stmt_init();
										
										$user_level_check_stmt = prepare($user_level_check_sql);
										
										$user_level_check_stmt -> bind_param ("i", $result_ps_id);

										if ($user_level_check_stmt->execute()) {

											$user_level_check_result = $user_level_check_stmt->get_result();

											if($user_level_check_result->num_rows > 1) {

												set_error_response( 201, "SQL Error -> " . $user_level_check_stmt->error);	

												break;

											}

											if($user_level_check_result->num_rows == 0) {

												echo "user level check failed, please try again....."."\n";

												break;

											}


											$row = $user_level_check_result->fetch_array(MYSQLI_NUM);

											$user_level = $row[0];

											if ($user_level == 0) {

												// user is a regular user 

												header ("Location: ../html/home.php");




											}
											if ($user_level ==1) {

												// user is an admin of some repository

												header ("Location: ../html/admin.php");			


											}



										}
										else {

											set_error_response( 201, "SQL Error -> " . $hash_retrieve_stmt->error);	
											break;
										}

									}
									else {

										set_error_response( 201, "SQL Error -> " . $insert_log_stmt->error);
										break;


									}


								}
								else {
									set_error_response( 13, "SQL Error" . $insert_token_statement->error);
									break;

								}
								
							}
							else {

								set_error_response( 11, "Your password does not match to our record, please try again......"."\n");
								break;

							}

						}
						else {
							set_error_response( 11, "SQL Error"."\n");
							break;
						}

					}

				}


				//if ($_POST['authtype'] == "refresh") {
				else if ($auth_type="refresh") {


					if (empty($username) || empty($refresh_token)) {
						//$error = "Username or Password is empty";
						echo "Username or refresh token is empty"."\n";
					}

					else {
						
						$random_string = generate_255_char_random_string();																

						$ps_id_retrieve_sql = "SELECT U.ps_id FROM user U WHERE U.username = ? ";
						$ps_id_retrieve_stmt = $db_conn->stmt_init();
						$ps_id_retrieve_stmt->prepare($ps_id_retrieve_sql);
						$ps_id_retrieve_stmt->bind_param("s", $username);

						if ($ps_id_retrieve_stmt->execute()) {

							if($ps_id_retrieve_sql= $ps_id_retrieve_stmt->get_result()) {

								$row = $ps_id_retrieve_sql->fetch_array(MYSQLI_NUM);

								$ps_id_return = $row[0]; 

							}

						}

						else {
							set_error_response( 13, "SQL Error" . $ps_id_retrieve_stmt->error);
							break;

						}

						$update_token_sql = "UPDATE user_auth_token SET access_token= ? WHERE ps_id=? AND refresh_token= ?";								
		
						if( !$update_token_statement = $db_conn->stmt_init()){
						
							set_error_response( 13, "SQL Error" . $update_token_statement->error);

						}
						
						if(! $update_token_statement->prepare($update_token_sql)){

							set_error_response( 13, "SQL Error" . $update_token_statement->error);


						}
						
						if(! $update_token_statement->bind_param("sis", $random_string, $ps_id_return, $refresh_token)) {

							set_error_response( 13, "SQL Error" . $update_token_statement->error);

						}
						
						if ($update_token_statement->execute()) {

							$resp_array = array();							
					
							$resp_array["ps_id"] = $ps_id_return;
							$resp_array["username"] = $username;
							$resp_array["access_token"] = $random_string;
							$resp_array["expires_in"] = 86400;	
							$resp_array["refresh_token"] = $refresh_token;
							
							http_response_code(200);

							echo json_encode($resp_array);
							
							break;
						}
						else {
							set_error_response( 13, "SQL Error" . $insert_token_statement->error);
							break;

						}
						
					}							
				}

				else {
					set_error_response( 11, "SQL Error");
					break;
				}

				$db_conn->close(); 
				echo "database disconnected successfully"."\n";

			}

			else {
				
				echo "no input from user"."\n";
				break;
			
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
