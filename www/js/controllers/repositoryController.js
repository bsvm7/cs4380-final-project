var app = angular.module('photoarchiving_app', [])
  .controller('RepositoryController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url = "http://40.86.85.30/cs4380/";
	
	$scope.repo_info = {
		"title"		: "untitled",
		"description" : "description",
		""
	};
	
	
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