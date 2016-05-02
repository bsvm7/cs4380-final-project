<?php
//session_start(); // Starting Session
include("../../db_security/security.php");
//include('./api/authorizate.php');
//$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {

	if (empty($_POST['username']) || empty($_POST['password'])) {
		//$error = "Username or Password is empty";
		echo "Username or Password is empty"."\n";
	}

	else {

		// Define $username and $password
		$username=$_POST['username'];
		$password=$_POST['password'];

		echo "username is ".$username."\n";
		echo "password is ".$password."\n";

	/*	
		// Establishing Connection with Server by passing server_name, user_id and password as a parameter
		//$connection = mysql_connect("localhost", "root", "");
		$db_conn = new mysqli(constant("DB_HOST"), constant("DB_USERNAME"), constant("DB_PASSWORD"), constant("DB_DATABASE"));
			
			if ($db_conn->error_code) {
				
				//	This should be replace PHP that sets the HTTP status code to 500 and
				//	sets the body to the JSON object that contains the error_code and
				//	error_string as defined by the API
				die("The connection to the database failed: " . $db_conn->connect_error);
			}
		// To protect MySQL injection for Security purpose
		$username = stripslashes($username);
		$password = stripslashes($password);
		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		// Selecting Database
		$db = mysql_select_db("users", $db_conn);
		define('CSV_PATH','../db/input_data/');
		$csv_file = CSV_PATH . "user.csv"; 
		// SQL query to fetch information of registerd users and finds user match.
		$query = mysql_query("select * from login where username='$username'", $db);
		$rows = mysql_num_rows($query);
		if ($rows == 1) {
			$_SESSION['login_user']=$username; // Initializing Session
			header("location: user.php"); // Redirecting To Other Page
		} 
		else {
			$error = "Username or Password is invalid";
		}
		$db_conn->close; // Closing Connection
	*/
	}
}
else {

	echo "no input from user"."\n";
}

?>
