var app = angular.module('photoarchiving_app', [])
  .controller('LoginController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url_main = "http://40.86.85.30/cs4380/";
	var base_url = base_url_main;


	
	$scope.credentials = {
		'username' : '',
		'password' : ''
		
	};
	
	checkIfLoggedIn();

	function checkIfLoggedIn() {
		
		if (typeof(Storage) !== "undefined") {
			
			if (sessionStorage.auth_token) {
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
						
			if (response.status == 200 ) {
				
				console.log(response);
				var res_data = response.data;
				
				console.log( res_data );
				console.log( res_data["ps_id"] );
				
				//	Gather the response information
				var res_ps_id 			= response.data.ps_id;
				var res_username 		= response.data.username;
				var res_access_token 	= response.data.access_token;
				var res_expires_in 		= response.data.expires_in;
				var res_refresh_token 	= response.data.refresh_token;
				
				
				var debug_array = [ res_ps_id , res_username , res_access_token , res_expires_in , res_refresh_token ];

				console.log( debug_array );
			}
			else {
				
				var error_message = "I got a bad status code " + response.status;
				
				alert( error_message );
				console.log( error_message );
				
			}
			
			
			
		}, function errorCallback( response ) {
			
			var error_string = "There was some error posting the registration data";
			
			alert( error_string );
			console.log( error_string );
			
		});

		
		
		
		
		
		
		
		
		
		/*
		$http({
			method : 'GET',
			url : auth_url
		}).then(function successCallBack(response) {
			
			//	Pull auth_token data from the response
			var auth_token = response.data.auth_token;
			var expires_in = response.data.expires_in;
			
			console.log( "The auth_token (1) is -> " + auth_token);
			//	Set the auth token data in storage
			if (typeof(Storage) !== "undefined") {
				sessionStorage.auth_token = auth_token;
				sessionStorage.expires_in = expires_in;
			}
			
			//	Redirect the user to the home page
			var redirect_url = base_url + "company.php?cid=2";
			
			$window.location.href = redirect_url;
			
		}, function errorCallback(response) {
			
			console.log("There was an error");
			
		});
		
		*/
		
	}
	
	function get_base_url() {
		
		if (use_main_url) {
			return settings.base_url;
		}
		else {
			return settings.dev_base_url;
		}
	}

	
  });