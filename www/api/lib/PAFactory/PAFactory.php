<?php
	
//	Make an enum 'abstract class' for the different types of information objects
abstract class InfoType 
{
	const Registration 	= 0;
	const Login			= 1;
	const PhotoRequest 	= 2;
}

/*
	Information Objects
*/
class PARegistrationInfo {
	
	public $first_name;
	public $middle_name;
	public $last_name;
	public $maiden_name;
	public $birthdate;
	public $gender;
	public $email;
	public $username;
	public $password;
	public $isValid;
	
	private $valid_keys = [ 	"firstname",	//	0
								"lastname",		//	1
								"middlename",	//	2
								"maiden_name",	//	3
								"birthdate",	//	4
								"gender",		//	5
								"password",		//	6
								"email",		//	7
								"username"		//	8
							];

	function __construct( $input_array ) {
		
		if(!(validate_input_array( $input_array ))) {
			$this->$isValid = false;
		}
		else {
			
			//	Set all the values
			$this->$first_name = 	get_dictionary_value_with_key_index( $input_array, 0);
			$this->$middle_name = 	get_dictionary_value_with_key_index( $input_array, 2);
			$this->$last_name = 	get_dictionary_value_with_key_index( $input_array, 1);
			$this->$maiden_name = 	get_dictionary_value_with_key_index( $input_array, 3);
			$this->$birthdate = 	get_dictionary_value_with_key_index( $input_array, 4);
			$this->$gender = 		get_dictionary_value_with_key_index( $input_array, 5);
			$this->$password = 		get_dictionary_value_with_key_index( $input_array, 6);
			$this->$email = 		get_dictionary_value_with_key_index( $input_array, 7);
			$this->$username = 		get_dictionary_value_with_key_index( $input_array, 8);
			$this->$isValid = true;
		}
	}
	
	public function print_values() {
		
		print_single_value( "First Name" , $this->$first_name );
		print_single_value( "Middle Name" , $this->$middle_name );
		print_single_value( "Last Name" , $this->$last_name );
		print_single_value( "Birthdate" , $this->$birthdate );
		print_single_value( "Gender" , $this->$gender );
		print_single_value( "Password" , $this->$password );
		print_single_value( "Username" , $this->$username );
		
	}
	
	
	
	
	
	
	
	
	
	
	private function print_single_value( $title , $val ) {
		echo "\n" . $title . ":\t" . $val . "\n";
	}
	
	
	private function get_dictionary_value_with_key_index( $dict , $key_index ) {
		return $dict[$this->valid_keys[$key_index]];
	}
	
	private function validate_input_array( $input_array ) {
		
		$valid = true;
		
		foreach ($valid_keys as $key_name) {
			
			if (!(array_key_exists($key_name, $input_array))) {
				$valid = false;
				break;
			}
		}
		
		
		
		return $valid;
	}
}

/*
	The main factory class
*/	
	
	
?>