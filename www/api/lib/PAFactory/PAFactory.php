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
		
		$valid_keys = $this->valid_keys;
		
		if(!($this->validate_input_array( $valid_keys , $input_array ))) {
			$this->isValid = false;
		}
		else {
			
			$this->first_name = 	$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 0);
			$this->middle_name = 	$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 2);
			$this->last_name = 	$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 1);
			$this->maiden_name = 	$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 3);
			$this->birthdate = 	$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 4);
			$this->gender = 		$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 5);
			$this->password = 		$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 6);
			$this->email = 		$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 7);
			$this->username = 		$this->get_dictionary_value_with_key_index( $input_array, $valid_keys , 8);
			$this->isValid = true;
		}
	}
	
	public function print_values() {
		
		$this->print_single_value( "First Name" , $this->first_name );
		$this->print_single_value( "Middle Name" , $this->middle_name );
		$this->print_single_value( "Last Name" , $this->last_name );
		$this->print_single_value( "Birthdate" , $this->birthdate );
		$this->print_single_value( "Gender" , $this->gender );
		$this->print_single_value( "Password" , $this->password );
		$this->print_single_value( "Username" , $this->username );
		
	}
	
	
	private function print_single_value( $title , $val ) {
		echo "\n" . $title . ":\t" . $val . "\n";
	}
	
	
	private function get_dictionary_value_with_key_index( $dict, $keys , $key_index ) {
		$key_val = $keys[$key_index];
		
		$dict_val = $dict[$key_val];
		
		return $dict_val;
	}
	
	private function validate_input_array( $keys , $input_array ) {
		
		$valid = true;
		
		return $valid;
	}
}	
	
?>