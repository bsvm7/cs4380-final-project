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
						
							case 'top_viewed_repos':

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


							case 'top_active_repos':

								$top_active_repos_sql= "SELECT R.name, ACTIVITY_COUNT.ac_count
									FROM repository R, (SELECT r_id, COUNT(*) AS ac_count 
									            FROM activity_log 
									            WHERE time_logged >= (curdate() -31)
									            GROUP BY r_id
									            ORDER BY view_count DESC
									            LIMIT 5) AS ACTIVITY_COUNT
									WHERE R.r_id = ACTIVITY_COUNT.r_id";


								if(!($top_active_repos_stmt= $db_conn->prepare($top_active_repos_sql))){
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if(!($top_active_repos_stmt->execute())) {
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if($result = $top_active_repos_stmt->get_result()) {

									$top_active_repos = array();

									while($result_row = $result->fetch_array(MYSQLI_ASSOC)){

										array_push($top_active_repos, $result_row);

									}
									
									http_response_code(200);
									echo json_encode($top_active_repos);	

								}
								else{
									set_error_response( 0 , "No repo is returned" . $top_active_repos_stmt->error );
									break;
								}
								
							break;

							case 'count_new_users':

								$count_new_users_sql= "SELECT PS.gender, COUNT(*) AS num_registered
								    FROM person PS, (SELECT ps_id
								                   	 FROM activity_log
								                     WHERE ac_type= 'user-register' 
								                	AND time_logged >= (curdate()-31)) AS REG
								    WHERE PS.ps_id=REG.ps_id
								    GROUP BY PS.gender"; 


								if(!($count_new_users_stmt= $db_conn->prepare($count_new_users_sql))){
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if(!($count_new_users_stmt->execute())) {
									set_error_response( 0 , $db_conn->error );
									break;	
								}

								if($result = $count_new_users_stmt->get_result()) {

									$count_new_users = array();

									while($result_row = $result->fetch_array(MYSQLI_ASSOC)){

										array_push($count_new_users, $result_row);

									}
									
									http_response_code(200);
									echo json_encode($count_new_users);	

								}
								else{
									set_error_response( 0 , "No user is returned" . $count_new_users_stmt->error );
									break;
								}

							break;


							case 'user_age_range':

								if(isset($_GET["rid"])) {

									$repo_id=$_GET["rid"]; 

									if(isset($_GET['start_age']) && isset($_GET['end_age']) ){

										$start_age = $_GET['start_age'];
										$end_age = $_GET['end_age'];

										if($start_age < $end_age) {

											$user_age_range_sql= "SELECT Count(*) AS age_range
																  FROM   user U, 
																         person P 
																  WHERE  (Floor(( Cast (Getdate() AS INTEGER) - Cast(P.birthdate AS INTEGER))/365.25) ) BETWEEN ? AND ? 
																  AND U.ps_id IN (SELECT UR.ps_id 
														                          FROM   user_repo UR 
														                          WHERE  UR.r_id = ?)"; 


											if(!($user_age_range_stmt= $db_conn->prepare($user_age_range_sql))){
												set_error_response( 0 , $db_conn->error );
												debug_echo ("user age range sql prepare failed ..."."\n");
												break;	
											}

											if(!($user_age_range_stmt->bind_param("iii",$$repo_id) ) ){
												set_error_response( 0 , $db_conn->error );
												debug_echo ("user age range stmt param bind failed ..."."\n");
												break;	
											}


											if(!($user_age_range_stmt->execute())) {
												set_error_response( 0 , $db_conn->error );
												debug_echo ("user age range stmt execute failed ..."."\n");
												break;	
											}

											if($result = $user_age_range_stmt->get_result()) {

												$user_age_range = array();

												while($result_row = $result->fetch_array(MYSQLI_ASSOC)){

													array_push($user_age_range, $result_row);

												}
												
												http_response_code(200);
												echo json_encode($user_age_range);	
											}
											else{
												set_error_response( 0 , "No user age range is returned..." . $user_age_range_stmt->error );
												debug_echo ("No user age range is returned ..."."\n");
												break;
											}

										}
										else{
											set_error_response( 0 , "start age is not smaller than end age..." . $user_age_range_stmt->error );
											debug_echo ("start age is not smaller than end age ..."."\n");
											break;
										}
									}
									else{
										set_error_response( 0 , "you need to provide start age and end age..." . $user_age_range_stmt->error );
										debug_echo ("you need to provide start age and end age ..."."\n");
										break;
									}

								}		
								else{
									set_error_response( 13, "rid can not be empty..."."\n");
									debug_echo ("rid can not be empty..."."\n");						
									break;
								}		

							break;

							case 'count_family_login':

								if(isset($_GET["lname"])) {

									$lname =$_GET["lname"]; 

									if(isset($_GET['login_date'])){

										$login_date = $_GET['login_date'];											

										$count_family_login_sql= "SELECT Count(*) 
											FROM   person PS, 
											       user U, 
											       activity_log AL 
											WHERE  PS.ps_id = U.ps_id 
											AND    PS.lname = 'forsythe' 
											AND    AL.ps_id =PS.ps_id 
											AND    AL.ac_type = 'login' 
											AND    Date_format(From_unixtime(`AL.time_logged`), '%e %b %Y') = 'xxxx-xx-xx'"; 


										if(!($count_family_login_stmt= $db_conn->prepare($count_family_login_sql))){
											set_error_response( 0 , $db_conn->error );
											debug_echo ("count family stmt prepare failed ..."."\n");
											break;	
										}

										if(!($count_family_login_stmt->bind_param("ss", $lname, $login_date) ) ){
											set_error_response( 0 , $db_conn->error );
											debug_echo ("count family stmt param bind failed ..."."\n");
											break;	
										}


										if(!($count_family_login_stmt->execute())) {
											set_error_response( 0 , $db_conn->error );
											debug_echo ("count family stmt execute failed ..."."\n");
											break;	
										}

										if($result = $count_family_login_stmt->get_result()) {

											$count_family_login = array();

											while($result_row = $result->fetch_array(MYSQLI_ASSOC)){

												array_push($count_family_login, $result_row);

											}
											
											http_response_code(200);
											echo json_encode($count_family_login);	
										}
										else{
											set_error_response( 0 , "No family login count returned..." . $user_age_range_stmt->error );
											debug_echo ("No family login count returned..."."\n");
											break;
										}
										
									}
									else{
										set_error_response( 0 , "you need to provide a log in date..." . $user_age_range_stmt->error );
										debug_echo ("you need to provide a log in date ..."."\n");
										break;
									}

								}		
								else{
									set_error_response( 13, "last name can not be empty..."."\n");
									debug_echo ("last name can not be empty..."."\n");						
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

