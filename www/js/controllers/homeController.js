var app = angular.module('photoarchiving_app', [])
  .controller('HomeController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url = "http://40.86.85.30/cs4380/";
	
	$scope.user_info = {
		"ps_id" : "",
		"username" : "",
		"auth_token" : "",
		"expires_in" : 0,
		"user_level" : 0,
		"refresh_token" : ""
	};
	
	
	//checkIfLoggedIn();
	gather_user_information();
	
	console.log( $scope.user_info );
	
	
	

	function gather_user_information() {
		
		$scope.user_info.ps_id = get_value_for_session_key( "ps_id" );
		$scope.user_info.username = get_value_for_session_key( "username" );
		$scope.user_info.auth_token = get_value_for_session_key( "auth_token" );
		
		
		var user_level_val = get_value_for_session_key( "user_level" );
		
		if (user_level_val != -1) {
			$scope.user_info.user_level = user_level_val;
		}
		
		$scope.user_info.refresh_token = get_value_for_session_key( "refresh_token" );
		
	}
	
	function checkIfLoggedIn() {
		
		if (typeof(Storage) !== "undefined") {
			
			if (!sessionStorage.auth_token) {
				$window.location.href = base_url + "login.php";
			}
		}
		
	}
	
	function get_base_url() {
		
		if (use_main_url) {
			return settings.base_url;
		}
		else {
			return settings.dev_base_url;
		}
	}
	
	function get_value_for_session_key( key ) {
		
		if( typeof(Storage) !== "undefined" ) {
			
			 return sessionStorage.getItem( key );	 
		}
		
		return -1;
	}
	
	function store_value_for_key_in_session_storage( key , value ) {
		
		if(typeof(Storage) !== "undefined" ) {
			
			sessionStorage.setItem( key , value );
			
			return true;
		}
		else {
			return false;
		}
		
		
	}
	
  });