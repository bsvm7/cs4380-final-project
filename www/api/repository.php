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
	}		

	$req_method = $_SERVER['REQUEST_METHOD'];	


		
	switch ($req_method) {
		
		case 'GET':

			$valid_auth_token = false;
			$result_ps_id;
			
			if (isset($_GET['auth_token'])) {
				
				debug_echo ("auth_token received..."."\n");

				//	Check to see if the auth token exists in the database
				$auth_token = $_GET['auth_token'];
				
				$get_token_sql = "SELECT ps_id, access_token from user_auth_token where access_token = " . $auth_token;
				
				if ($result = $db_conn->query($get_token_sql))
				{
					if ($result->num_rows == 1) {
						debug_echo ("get token succeded..."."\n");
						$valid_auth_token = true;
						$result_ps_id = ($result->fetch_array(MYSQLI_ASSOC))["ps_id"];
					}
				}
				else 
				{
					set_error_response( 21 , "SQL statement could not prepare " . $db_conn->error);
					debug_echo ("get token error..."."\n");

				}
								
			}
			else 
			{
				set_error_response( 4, "The auth parameter was not properly set");
				debug_echo ("auth token can not be empty..."."\n");
			}

			

			if ($valid_auth_token) {
				
				if (isset($_REQUEST['req_type'])) {

					$req_type = $_GET['req_type'];
					
					switch ($req_type) {

						case: 'user_repos'








						break;

						case: 'all_repos'





						break;


						case: 'repo_info' :

							if(isset($_GET["rid"])) {

								$repo_id=$_GET["rid"]; 

								$get_repo_info_sql= "SELECT * FROM repository R WHERE R.rid= ".$repo_id;

								if($result= $db_conn->query($get_repo_info_sql)) {

									if($result_row = $result->fetch_array(MYSQLI_ASSOC)){

										http_response_code(200);
										echo json_encode($result_row);
									}	
									else{
										set_error_response( 203, "SQL Error -> " . $db_conn->error);
										debug_echo ("SQL Error -> "."\n");
										break;
									}							

								}	
								else{
									set_error_response( 203, "multiple repositories are returned....");
									debug_echo ("multiple repositories are returned...."."\n");
									break;
								}	


							}
							else{
								set_error_response( 13, "rid can not be empty..."."\n");
								debug_echo ("rid can not be empty..."."\n");
					
								break;
							}

						break;

						case: 'analytics'

							if (isset($_GET["graph-type"])) {

								$graph_type = $_GET["graph-type"];
						
								switch ($graph_type) {
									
									case "top-visit-bar-chart":

										// first get the rid of all the repositories





										// then return the visit numbers 





									break;

								

								}	

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
			}

		break;
		
		default:

		break;

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

