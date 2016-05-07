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
	
	echo "database connected" . "\n";


	if ($db_conn->error_code) {
		
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
		
	
	$req_method = $_SERVER['REQUEST_METHOD'];		
		
	switch ($req_method) {
		
		case 'GET':

			if(isset($_GET["username"])) {

				$username = $_GET["username"];

				if(isset($_GET["access_token"])) {

					$access_token= $_GET["access_token"]; 

					if(isset($_GET["repo_id"])) {


					}
					else{
						set_error_response( 13, "repo id can not be empty..."."\n");
						break;
					}	


				}
				else{
					set_error_response( 13, "access token can not be empty..."."\n");
					break;
				}

			}
			else{
				set_error_response( 13, "username can not be empty..."."\n");
				break;
			}












		break;

		default:

		break;



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