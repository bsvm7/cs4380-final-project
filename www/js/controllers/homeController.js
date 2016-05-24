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
	
	$scope.raw_user_repos		= [];
	$scope.clean_user_repos		= [];
	
	
	//checkIfLoggedIn();
	gather_user_information();
	get_user_repos();
	
	
	
	
	/*
		REST API -> GET Functions
	*/
	function get_user_repos() {
		
		//	Form the request URL
		var req_url = 	base_url + "api/repository.php?"
						+ "req_type=user_repos&"
						+ "ps_id=" + $scope.user_info.ps_id + "&"
						+ "auth_token=" + $scope.user_info.auth_token;
						
		
		$http({
				method	:	'GET',
				url		:	req_url
			}).then( function successCallback( response ) {
				
				var res_data = response.data;
				
				console.log(res_data);
				
			}, function errorCallback( response ) {
				
				var error_string = "There was an error processing the request";
				console.log( error_string );
			});

		
		clean_user_repos();
		
		console.log($scope.clean_user_repos);
	}
	
	
	
	
	
	/*
		Utility Functions
	*/
	function clean_user_repos() {
		
		//	First empty the 'clean_user_repos' array
		$scope.clean_user_repos = [];
		
		
		//	Now process the 'raw_user_repos' array and load into 'clean_user_repos'
		var raw_user_repo_lenth = $scope.raw_user_repos.length;
		
		for( var i = 0 ; i < raw_user_repo_length ; i++ ) {
			
			var curr = $scope.raw_user_repos[i];
			
			//	Variable Declaration
			var r_title;
			var r_url;
			var r_id;
			var r_description;
			var r_date_created;
			var r_name;
			
			r_title 		= curr.title;
			r_id 			= curr.r_id;
			r_name 			= curr.name;
			r_description 	= curr.description;
			r_date_created 	= curr.date_created;
			
			//	Create the url for the repository
			r_url =	base_url + "repository.php?"
					+ "r_id=" + r_id;
					
			//	Set all in object
			var clean_user_repo = {
				"title"			: r_title,
				"r_id" 			: r_id,
				"name" 			: r_name,
				"description" 	: r_description,
				"date_created" 	: r_date_created,
				"url"			: r_url
			};
			
			$scope.clean_user_repos.push(clean_user_repo);
		}
		
	}
	
	function gather_user_information() {
		
		$scope.user_info.ps_id		= get_value_for_session_key( "ps_id" );
		$scope.user_info.username 	= get_value_for_session_key( "username" );
		$scope.user_info.auth_token = get_value_for_session_key( "access_token" );
		
		
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