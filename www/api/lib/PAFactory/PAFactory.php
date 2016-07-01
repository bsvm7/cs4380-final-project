<?php
	
//	Make an enum 'abstract class' for the different types of information objects
abstract class InfoType 
{
	const Registration 	= 0;
	const Login			= 1;
	const PhotoRequest 	= 2;
}

class PAKeyValuePair {
	
	public $key;
	public $value;
	
	function __construct( $key , $value ) {
		
		$this->key = $key;
		$this->value = $value;
	}
	
	public function getJSONString() {
		
		$ret_string = '"' . $this->key . '"' . ':';
		
		//	Check if the value is numeric
		$is_num = is_numeric($this->value);
		
		if($is_num) {
			
			$ret_string = $ret_string . $this->value;
			
		}
		else {
			$ret_string = $ret_string . '"' . $this->value . '"';
		}
		
		return $ret_string;
		
	}
}
class PAKeyValueStore {
	
	private $keyVals;
	
	function __construct() {
		
		$this->keyVals = array();	
		
	}
	
	public function getKeyValuePair( $k ) {
		
		$pairVal;
		
		$kv_count = count($this->keyVals);
		
		for($i = 0; $i < $kv_count;$i++) {
			
			$p = $this->keyVals[$i];
			
			$key = $p->key;
			
			if ($key == $k) {
				$pairVal = $p;
			}
		}
		
		return $pairVal;
	}
	public function addKeyValuePair( $k , $v ) {
		
		$pair = new PAKeyValuePair( $k , $v );
		
		array_push($this->keyVals, $pair );
	}
	
	public function addPAKeyValuePair( $kv ) {
		
		if ($kv instanceof PAKeyValuePair) {
			
			array_push($this->keyVals, $kv);
		}
	}
	
	public function getJSONString() {
		
		$ret_string = "{";
		
		$kv_count = count($this->keyVals);
		
		for ($i = 0; $i < $kv_count; $i++) {
			
			$kv = $this->keyVals[$i];
			
			$kv_string = $kv->getJSONString();
			
			$ret_string = $ret_string . $kv_string;
			
			if ($i != $kv_count - 1) {
				$ret_string = $ret_string . ",";	
			}
		}
		
		$ret_string = $ret_string . "}";
		
		return $ret_string;
	}
}
/*
	Information Objects
*/
class PARegistrationInfo {
	
	private $kvStore;
	

	public $isValid;
	public $error;
	
	private $shouldBreak;
	
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
		$this->shouldBreak = false;
		$this->isValid = true;
		
		if(!($this->validate_input_array( $valid_keys , $input_array ))) {
			$this->isValid = false;
			$this->error = "The keys I have didn't match up the keys in the input array";
		}
		else {
			
			$this->kvStore = new PAKeyValueStore();
			$this->pull_and_clean_values( $input_array );
			
		}
	}
	
	public function first_name() {
		
		$k = "firstname";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}

	public function maiden_name() {
		
		$k = "maiden_name";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	public function birth_date() {
		
		$k = "birthdate";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	public function last_name() {
		
		$k = "lastname";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	
	public function middle_name() {
		
		$k = "middlename";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	public function gender() {
		
		$k = "gender";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	public function password() {
		
		$k = "password";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	public function email() {
		
		$k = "email";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	public function username() {
		
		$k = "username";
		
		$p = $this->kvStore->getKeyValuePair( $k );
		
		if(!isset($p)) {
			$this->error = "I couldn't find the kv pair for " . $k ;
			return "";
		}
		
		return $p->value;
		
	}
	
	
	
	public function getJSONString() {
		
		return $this->kvStore->getJSONString();	
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
	
	private function pull_and_clean_values( $values ) {
		
		foreach( $values as $k => $v ) {
			
			if ($k == "birthdate") {
				
				$clean_birthdate = $this->clean_date( $v );
				
				if(!$clean_birthdate["isValidDate"]) {
					$this->shouldBreak = true;
					$this->isValid = false;
					$this->error = "Not a valid date string";
				}
				
				$this->kvStore->addKeyValuePair( $k , $clean_birthdate["validDateString"]);
				
			}
			else {
				$this->kvStore->addKeyValuePair( $k , $v );	
			}
			
			
			
		}
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
	
	private function clean_date( $date_string ) {
		
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
}	
/*

$json_string = '{"firstname":"Anthony","middlename":"Robert","lastname":"Forsythe","birthdate":"06-24-1993","gender":"male","password":"defaultpass","email":"forsythetony@gmail.com","username":"forsythe.236tony"}';

$json_array = json_decode($json_string , true );

$reg_info = new PARegistrationInfo( $json_array );

echo $reg_info->getJSONString();
*/

?>