<?php
	
	
	//	Include reference to sensitive databse information
	include("../../../db_security/security.php");
	
	$db_user = constant("DB_USER");
	
	echo "\nThe user for the database is $db_user\n";
	
?>
