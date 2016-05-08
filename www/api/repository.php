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
		debug_echo ("Could not connect to server"."\n");
		break;
	}	

	debug_echo ("server connected successfully"."\n");


	$req_method = $_SERVER['REQUEST_METHOD'];	

		
	switch ($req_method) {
		
		case 'GET':

			debug_echo ("required method is GET "."\n");

			$valid_auth_token = false;
			//$result_ps_id;
			
			if (isset($_GET['auth_token'])) {
				
				//	Check to see if the auth token exists in the database
				$auth_token = $_GET['auth_token'];
				
				debug_echo ("auth token received is ".$auth_token."\n");

				$get_token_sql = "SELECT ps_id, access_token from user_auth_token where access_token = ?";
				
				
				
				if(!($get_token_stmt = $db_conn->prepare($get_token_sql))){
					set_error_response( 21 , "SQL statement could not prepare " . $db_conn->error);
					break;

				}

				if(!($get_token_stmt->bind_param("s", $auth_token)) ) {
					set_error_response( 21 , "SQL statement could not bind param " . $db_conn->error);
					break;
				}

				if(!($get_token_stmt->execute())) {
					set_error_response( 21 , "SQL statement could not execute " . $db_conn->error);
					break;
				}

 				if ($result = $get_token_stmt->get_result()) {

					if ($result->num_rows == 1) {
						debug_echo ("get token succeded..."."\n");
						$valid_auth_token = true;
					}
				
					else 
					{
						set_error_response( 21 , "SQL statement could not prepare " . $db_conn->error);
						debug_echo ("get token error..."."\n");
						break;

					}
								
				}
				else 
				{
					set_error_response( 4, "The auth parameter was not properly set");
					debug_echo ("auth token can not be empty..."."\n");
					break;
				}

			

				if ($valid_auth_token) {
					
					if (isset($_REQUEST['req_type'])) {

						$req_type = $_GET['req_type'];
						
						switch ($req_type) {

							case 'user_repos':

								if(isset($_GET['ps_id'])) {

									$ps_id = $_GET['ps_id'];

									$user_repo_request_sql= "SELECT R.r_id, R.name, R.description, R.date_created FROM user U, repository R, user_repo UR WHERE U.ps_id=UR.ps_id AND UR.r_id=R.r_id AND U.ps_id= ? ";


									if(!($user_repo_request_stmt= $db_conn->prepare($user_repo_request_sql))){
										set_error_response( 0 , $db_conn->error );
										break;	
									}

									if(!($user_repo_request_stmt->bind_param("i", $ps_id))){
										set_error_response( 0 , $db_conn->error );
										break;		
									}

									if(!($user_repo_request_stmt->execute())) {
										set_error_response( 0 , $db_conn->error );
										break;	
									}

									if($result = $user_repo_request_stmt->get_result()) {

										$all_repos = array();

										while($result_row = $result->fetch_array(MYSQLI_ASSOC)){

											array_push($all_repos, $result_row);

										}
										
										http_response_code(200);
										echo json_encode($all_repos);	

									}
									else{
										set_error_response( 0 , "No repo is returned" . $user_repo_request_stmt->error );
										break;
									}

								}

								else{
									set_error_response( 13, "ps_id can not be empty..."."\n");
									debug_echo ("ps_id is empty...."."\n");
									break;

								}

							break;

							case 'all_repos':


								$user_repo_request_sql= "SELECT * FROM repository";


								if(!($user_repo_request_stmt= $db_conn->prepare($user_repo_request_sql))){
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if(!($user_repo_request_stmt->execute())) {
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if($result = $user_repo_request_stmt->get_result()) {

									$all_repos = array();

									while($result_row = $result->fetch_array(MYSQLI_ASSOC)){

										array_push($all_repos, $result_row);

									}
									
									http_response_code(200);
									echo json_encode($all_repos);	

								}
								else{
									set_error_response( 0 , "No repo is returned" . $user_repo_request_stmt->error );
									break;
								}

							break;


							case 'repo_info':

								if(isset($_GET["rid"])) {

									$repo_id=$_GET["rid"]; 

									$get_repo_info_sql= "SELECT * FROM repository R WHERE R.r_id= ?";

									debug_echo ("rid is ".$repo_id."\n");


									
									if(!($get_repo_info_stmt = $db_conn->prepare($get_repo_info_sql))){
										set_error_response( 21 , "SQL statement could not prepare " . $db_conn->error);
										break;

									}

									if(!($get_repo_info_stmt->bind_param("i", $repo_id)) ) {
										set_error_response( 21 , "SQL statement could not bind param " . $db_conn->error);
										break;
									}

									if(!($get_repo_info_stmt->execute())) {
										set_error_response( 21 , "SQL statement could not execute " . $db_conn->error);
										break;
									}

					 				if ($result = $get_repo_info_stmt->get_result()) {

										if ($result->num_rows == 1) {

											debug_echo ("get repo succeded..."."\n");

											if($result_row = $result->fetch_array(MYSQLI_ASSOC)){

												http_response_code(200);
												echo json_encode($result_row);
											}	
										}
										elseif ($result->num_rows == 0){
											set_error_response( 21 , "no repo found " . $db_conn->error);
											debug_echo ("no repo found..."."\n");
											break;
										}
										else 
										{
											set_error_response( 21 , "multiple repo found " . $db_conn->error);
											debug_echo ("multiple repo found..."."\n");
											break;
										}
													
									}
									else 
									{
										set_error_response( 4, "The auth parameter was not properly set");
										debug_echo ("auth token can not be empty..."."\n");
										break;
									}

								}
								else{
									set_error_response( 13, "rid can not be empty..."."\n");
									debug_echo ("rid can not be empty..."."\n");
						
									break;
								}

							break;
							
							case 'top_10_repo'

								$top_repo_request_sql= "SELECT R.name, VIEW_COUNT.view_count
									FROM repository R, (SELECT r_id, COUNT(*) AS view_count 
									            FROM activity_log 
									            WHERE ac_type= 'repo-view' 
									                AND time_logged >= (curdate() -31)
									            GROUP BY r_id
									            ORDER BY view_count DESC
									            LIMIT 10) AS VIEW_COUNT
									WHERE R.r_id=VIEW_COUNT.r_id";


								if(!($top_repo_request_stmt= $db_conn->prepare($top_repo_request_sql))){
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if(!($top_repo_request_stmt->execute())) {
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if($result = $top_repo_request_stmt->get_result()) {

									$top_repos = array();

									while($result_row = $result->fetch_array(MYSQLI_ASSOC)){

										array_push($top_repos, $result_row);

									}
									
									http_response_code(200);
									echo json_encode($top_repos);	

								}
								else{
									set_error_response( 0 , "No repo is returned" . $top_repo_request_stmt->error );
									break;
								}









							break;

							default:

							break;

						}
					}
					else{
						set_error_response( 13, "rid can not be empty..."."\n");
						debug_echo ("req_type error...."."\n");
						break;
					}
				}
				else{
					debug_echo ("auth token does not match to our record ... ");
					break;
				}
			}

		break;
		
		default:

		break;

	}	

	
	function debug_echo( $str ) {
		
		$echo_debug = true;
		
		if ($echo_debug) {
			echo $str;
		}
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

