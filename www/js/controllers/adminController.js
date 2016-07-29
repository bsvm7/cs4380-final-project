var app = angular.module('photoarchiving_app', ["highcharts-ng"])
  .controller('AdminController', function($scope, $http, $window) {
  	
	//	CONSTANTS
	var base_url = "http://13.89.34.220/photoarchiving/";
	
	$scope.user_info = {
		"ps_id" : "",
		"username" : "",
		"auth_token" : "",
		"expires_in" : 0,
		"user_level" : 0,
		"refresh_token" : ""
	};
	
	
	//checkIfLoggedIn();
	//gather_user_information();
	
	
	$scope.chartConfig = {

	    options: {
	        //This is the Main Highcharts chart config. Any Highchart options are valid here.
	        //will be overriden by values specified below.
	        chart: {
	            type: 'pie'
	        },
	        tooltip: {
	            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
	    //The below properties are watched separately for changes.
	
	    //Series object (optional) - a list of series using normal Highcharts series options.
	    series: [{
	        data: [
				{ name: 'Female', y: 136 },
                { name: 'Male', y: 250 }
			]
	    }],
	    //Title configuration (optional)
	    title: {
	        text: 'count of new users broken down by gender'
	    },
	    //Boolean to control showing loading status on chart (optional)
	    //Could be a string if you want to show specific loading text.
	    loading: false,
	    //Configuration for the xAxis (optional). Currently only one x axis can be dynamically controlled.
	    //properties currentMin and currentMax provided 2-way binding to the chart's maximum and minimum
	    //function (optional)
	    func: function(chart) {
	        //setup some logic for the chart
	    },
	};






	load_dummy_data();






	function load_dummy_data() {
		
		var dummy_series = [ 90 , 80 , 20 , 11 , 2 , 33 , 5 ];
		var dummy_max = 100;
		var dummy_min = 0;
		
		$scope.chartConfig.series[0].data = dummy_series;
		$scope.chartConfig.xAxis.currentMin = 0;
		$scope.chartConfig.xAxis.currentMax = 100;
		
		$scope.chartConfig.yAxis.currentMin = 0;
		$scope.chartConfig.yAxis.currentMin = dummy_series.length;
		
	}



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