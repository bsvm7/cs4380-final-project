var app = angular.module('photoarchiving_app', [])
  .controller('RepositoryController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url = "http://40.86.85.30/cs4380/";
	
	$scope.repo_info = {
		"title"		: "untitled",
		"description" : "description",
		"date_created" : ""
	};
	
	$scope.eraData = {
		"selectedEra" : -1,
		"availableEras" : []
	};
	
	$scope.query_params_set = false;
	$scope.query_params = {};
	$scope.user_auth_info_set = false;
	$scope.user_auth_info = {};
	
	$scope.repository_id = -1;

	load_query_params();
	load_user_authentication_information();
	get_repo_information();

	/*
		Initializaiton Functions
	*/
	function load_query_params() {
		
		$scope.query_params = pull_query_params();
		$scope.query_params_set = true;
	}
	
	function load_user_authentication_information() {
		$scope.user_auth_info = get_session_information();
		$scope.user_auth_info_set = true;
	}
	
	function get_repo_information() {
		
		if ($scope.query_params_set && $scope.user_auth_info_set) {
			
			var auth_info = $scope.user_auth_info;
			var query_params = $scope.query_params;
			
			
			var req_url = 	base_url + "api/repository.php?"
							+ "req_type=repo_info&"
							+ "rid=" + query_params["r_id"] + "&"
							+ "auth_token=" + auth_info["access_token"];
							
			console.log(req_url);
							
			
			$http({
				method	:	'GET',
				url		:	req_url
			}).then( function successCallback( response ) {
				
				var res_data = response.data;
				
				$scope.repo_info.title 			= res_data.name;
				$scope.repo_info.description 	= res_data.description;
				$scope.repo_info.date_created 	= res_data.date_created;
				
				
			}, function errorCallback( response ) {
				
				var error_string = "There was an error processing the request";
				console.log( error_string );
			});
		}
	}
	
	function get_eras() {
		
		if ($scope.query_params_set && $scope.user_auth_info_set) {
			
			var auth_info = $scope.user_auth_info;
			var query_params = $scope.query_params;
			
			
			var req_url = 	base_url + "api/era.php?"
							+ "ps_id=" + auth_info["ps_id"] + "&"
							+ "auth_token=" + auth_info["access_token"];
							
			console.log(req_url);
							
			
			$http({
				method	:	'GET',
				url		:	req_url
			}).then( function successCallback( response ) {
				
				var res_data = response.data;
				
				$scope.eraData.availableEras = res_data;
				
				
			}, function errorCallback( response ) {
				
				var error_string = "There was an error processing the request";
				console.log( error_string );
			});
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

	function store_value_for_key_in_session_storage( key , value ) {
		
		if(typeof(Storage) !== "undefined" ) {
			
			sessionStorage.setItem( key , value );
			
			return true;
		}
		else {
			return false;
		}
		
		
	}
	
	function pull_query_params() {
		
		var url_string = $window.location.href;
		
		//	Check to make sure there are query parameters
		
		var quest_mark_pos = url_string.indexOf('?');
		
		var params = {};
		
		if (quest_mark_pos !== -1 ) {
			//	There are some parameters
			
			
			
			var amp_mark_pos = url_string.indexOf('&');
			
			if (amp_mark_pos !== -1 ) {
				
				params = url_string.split('?')[1].split('&');
				
				var arr_len = params.length;
				
				for( var i = 0; i < arr_len; i++ ) {
					
					var param_value = params[i];
					
					var param_value_split = param_value.split('=');
					
					var key_val = param_value_split[0];
					var val_val = param_value_split[1];
					
					params[key_val] = val_val;
					
					
					
				}
			}
			else {
				
				var params_str = url_string.split('?')[1];
				var param_value_split = params_str.split('=');
				
				var key_val = param_value_split[0];
				var val_val = param_value_split[1];
				
				params[key_val] = val_val;
				
			}
		}
		
		return params;
		
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