var app = angular.module('photoarchiving_app', [])
  .controller('LoginController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url = "http://40.86.85.30/cs4380/";
	
	$scope.credentials = {
		'username' : '',
		'password' : ''
		
	};
	
	checkIfLoggedIn();

	function checkIfLoggedIn() {
		
		if (typeof(Storage) !== "undefined") {
			
			if (sessionStorage.access_token) {
				$window.location.href = base_url + "home.php";
			}
		}
		
	}
	
	

	$scope.authenticate = function() {
		
		var auth_url = base_url + "api/login_function.php";
		
		var post_body = {
			"auth_type"		: "initial",
			"username" 		: $scope.credentials.username,
			"password"		: $scope.credentials.password,
			"refresh_token"	: ""
		};
		
		$http.post( auth_url , post_body ).then( function successCallback( response ) {
						
			console.log(response);
			
			if (response.status == 200 ) {
				
				var res_data = response.data;
				console.log( res_data );
				
				//	Gather the response information
				var res_ps_id 			= res_data.ps_id;
				var res_username 		= res_data.username;
				var res_access_token 	= res_data.access_token;
				var res_expires_in 		= res_data.expires_in;
				var res_refresh_token 	= res_data.refresh_token;
				var res_user_level		= res_data.user_level;
				
				//	Store these values in session storage
				store_value_for_key_in_session_storage( "ps_id" , res_ps_id );
				store_value_for_key_in_session_storage( "username" , res_username );
				store_value_for_key_in_session_storage( "access_token" , res_access_token );
				store_value_for_key_in_session_storage( "expires_in" , res_expires_in );
				store_value_for_key_in_session_storage( "refresh_token" , res_refresh_token );
				store_value_for_key_in_session_storage( "user_level" , res_user_level );
				//	Create the redirect URL
				var redirect_url = base_url + "home.php";
				
				$window.location.href = redirect_url;	
			}
			else {
				
				var	error_message = "I couldn't log you in with the given username and password...";
				
				console.log( error_message );
				
				
			}
			
			
			
		}, function errorCallback( response ) {
			
			var	error_message = "I couldn't log you in with the given username and password...";
				
			console.log( error_message );
				

			
		});
		
	}
	
	function get_base_url() {
		
		if (use_main_url) {
			return settings.base_url;
		}
		else {
			return settings.dev_base_url;
		}
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