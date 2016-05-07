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
		
		case 'POST':

			//	Get the raw post database
			$json_raw = file_get_contents("php://input");

			if ($decoded_json = json_decode($json_raw, true)) {	

				$access_token=$decoded_json['access_token'];
				$search_method=$decoded_json['search_method'];
				$keyword = $decoded_json['keyword'];

				if(!empty($search_method)) {

					if($search_method = 'repository') {

						$repo_search = ''




					}








				}
				else{
					header ("Location: ../search.php");
				}







?>