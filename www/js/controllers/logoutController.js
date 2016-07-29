var app = angular.module('photoarchiving_app', [])
  .controller('LogoutController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url = "http://13.89.34.220/photoarchiving/";
	
	
	//	Log the user out
	logout_user();
	
	
	
	
	
	function logout_user() {
		
		var session_dict = get_session_information();
		
		
		var post_body = {
			"access_token" : session_dict.access_token
		};
		
		var logout_api_url = base_url + "api/logout_function.php";
		
		$http.post( logout_api_url , post_body ).then( function successCallback( response ) {
						
			if (response.status == 200 ) {
				
				var res_data = response.data;
				console.log( res_data );
				
				sessionStorage.clear();
				
				//	Create the redirect URL
				var redirect_url = base_url + "login.php";
				
				$window.location.href = redirect_url;	
			}
			else {
				
				//	THIS SHOULD BE FIXED
				
				var res_data = response.data;
				console.log("This is the else statement ");
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
			
			
			
		}, function errorCallback( response ) {
			
			console.log( response );
			
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
	
	function get_session_information() {
		
		var ret_dict;
		
		if (typeof(Storage) !== "undefined") {
			
			var auth_token 		= sessionStorage.access_token;
			var expires_in 		= sessionStorage.expires_in;
			var ps_id			= sessionStorage.ps_id;
			var refresh_token 	= sessionStorage.refresh_token;
			var user_level 		= sessionStorage.user_level;
			var username 		= sessionStorage.username;
			
			
			ret_dict = {
				"access_token" 		: auth_token,
				"expires_in" 		: expires_in,
				"ps_id"				: ps_id,
				"user_level"		: user_level,
				"username"			: username
			};
			
		}
		
		return ret_dict;
	}
	
  });