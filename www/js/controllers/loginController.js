var app = angular.module('photoarchiving_app', [])
  .controller('LoginController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url_main = "http://www.cs4380-group9.tech/final_project/";
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
		
		var auth_url = base_url + "api/authorization.php";
		
		var auth_url = auth_url + "?authtype=initial&" + "username=" + $scope.credentials.username + "&password=" + $scope.credentials.password;
		
		console.log("The auth url was -> " + auth_url);
		
		
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