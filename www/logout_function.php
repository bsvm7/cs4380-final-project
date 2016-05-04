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

			//if ($_POST['authtype'] == "initial") {

			//	Get the raw post database
			$json_raw = file_get_contents("php://input");

			if ($decoded_json = json_decode($json_raw, true)) {	

				$access_token=$decoded_json['access_token'];

				if (empty($access_token)) {
					//$error = "Username or Password is empty";
					echo "access_token is needed"."\n";
				}

				else {
					
					$token_retrieve_sql = 'SELECT * FROM user_auth_token UAT WHERE UAT.access_token = ?';

					$token_retrieve_sql = $db_conn->stmt_init();

					if (!($token_retrieve_stmt = $db_conn->prepare($token_retrieve_sql))) {

						set_error_response( 201, "SQL Error -> " . $token_retrieve_stmt->error);

						break;
					}							

					$token_retrieve_stmt->bind_param("s" , $access_token);
				
					if (!$hash_retrieve_stmt->execute()) {
				
						echo "Error" . " " . $stmt->error;
				
					}

				
					if ($token_retrieve_result = $token_retrieve_stmt->get_result()) {

						/*
						// this has not worked out yet
						if($hash_retrieve_stmt->num_rows != 1) {

							set_error_response( 201, "SQL Error -> " . $hash_retrieve_stmt->error);	

							break;

						}

						echo "only one user record found"."\n";
						*/

						$row = $token_retrieve_result->fetch_array(MYSQLI_NUM);
						
						$result_access_token = $row[1];
						$result_refresh_token = $row[2];
						$result_ps_id = $row[3];
					
						if ($result_access_token == $access_token) {


							$insert_log_sql = "INSERT INTO activity_log (ps_id, ac_type) VALUES ( ? , ?)";								
			
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

								$insert_log_stmt->bind_param("is", $result_ps_id, 'login');

								if($insert_log_stmt->execute()) {

									echo "login activity has been logged"."\n";
								}
								else {

									set_error_response( 201, "SQL Error -> " . $insert_new_person_stmt->error);

								}


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

					$db_conn->close; 
			
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
						}

						$update_token_sql = "UPDATE user_auth_token SET access_token= ? WHERE issued_to=? AND refresh_token= ?";								
		
						if( !$update_token_statement = $db_conn->stmt_init()){
						
							set_error_response( 13, "SQL Error" . $update_token_statement->error);

						}
						
						if(! $update_token_statement->prepare($update_token_sql)){

							set_error_response( 13, "SQL Error" . $update_token_statement->error);


						}
						
						if(! $update_token_statement->bind_param("sss", $random_string, $ps_id_return, $refresh_token)) {

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
						}
						else {
							set_error_response( 13, "SQL Error" . $insert_token_statement->error);
						}
						
					}							
				}

				else {
					set_error_response( 11, "SQL Error");
					break;
				}

				$db_conn->close; 
				
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

	echo "\n\n\n"."everything worked and now its time to close database and everything"."\n";
?>
