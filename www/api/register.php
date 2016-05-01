<?php
	
	
	//	Include reference to sensitive databse information
	include("../../../db_security/security.php");
	
	$db_user = constant("DB_USER");
	$db_host = constant("DB_HOST");
	$db_pass = constant("DB_PASS");
	$db_database = constant("DB_DATABASE");
	
	
	echo "\nThe user for the database is $db_user\nThe host is:\t$db_host\nThe password is:\t$db_pass\nThe database is:\t$db_database\n";
	
?>
