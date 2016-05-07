var app = angular.module('photoarchiving_app', ["highcharts-ng"])
  .controller('AdminController', function($scope, $http, $window) {
  	
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
	//gather_user_information();
	
	//console.log( $scope.user_info );
	
	$scope.chartConfig = {
        options: {
            subtitle: {
                text: 'Click and drag to zoom in.'
            },
            chart: {
                backgroundColor: 'transparent',
                zoomType: 'xy',
                resetZoomButton: {
                    position: {
                        x: 0,
                        y: -35
                    },
                    theme: {
                        fill: 'white',
                        stroke: 'silver',
                        r: 0,
                        states: {
                            hover: {
                                fill: '#41739D',
                                style: {
                                    color: 'white'
                                }
                            }
                        }
                    }
                }
            },
            navigator: {
                enabled: true,
                series: {
                    data: []
                }
            },
            rangeSelector: {
                enabled: false
            },
            plotOptions: {
                series: {
                    lineWidth: 1,
                    fillOpacity: 0.5

                },
                column: {
                    stacking: 'normal'
                },
                area: {
                    stacking: 'normal',
                    marker: {
                        enabled: false
                    }
                }

            },
            exporting: false,
            xAxis: [{
                type: 'datetime'
            }],
            yAxis: [

                { // Primary yAxis

                    min: 0,
                    allowDecimals: false,
                    title: {
                        text: 'number of notification',
                        style: {
                            color: '#80a3ca'
                        }
                    },
                    labels: {
                        format: '{value}',
                        style: {
                            color: '#80a3ca'
                        }
                    }


                },
                { // Secondary yAxis
                    min: 0,
                    allowDecimals: false,
                    title: {
                        text: 'usage time',
                        style: {
                            color: '#c680ca'
                        }
                    },
                    labels: {
                        format: '{value}',
                        style: {
                            color: '#c680ca'
                        }
                    },
                    opposite: true

                }
            ],

            legend: {
                enabled: false
            },
            title: {
                text: ' '
            },
            credits: {
                enabled: false
            },

            loading: false,
            tooltip: {
                crosshairs: [
                    {
                        width: 1,
                        dashStyle: 'dash',
                        color: '#898989'
                    },
                    {
                        width: 1,
                        dashStyle: 'dash',
                        color: '#898989'
                    }
                ],
                headerFormat: '<div class="header">{point.key}</div>',
                pointFormat: '<div class="line"><div class="circle" style="background-color:{series.color};float:left;margin-left:10px!important;clear:left;"></div><p class="country" style="float:left;">{series.name}</p><p>{point.y:,.0f} {series.tooltipOptions.valueSuffix} </p></div>',
                borderWidth: 1,
                borderRadius: 5,
                borderColor: '#a4a4a4',
                shadow: false,
                useHTML: true,
                percentageDecimals: 2,
                backgroundColor: "rgba(255,255,255,.7)",
                style: {
                    padding: 0
                },
                shared: true

            },
            useHighStocks: true

        },
        series: [
            {
                id: 'iphoneNotificationData',
                name: 'Notifications',
                data: [[1426204800000,12],[1426464000000,6],[1426550400000,10],[1426636800000,3]],
                type: 'column',
                yAxis: 0,
                color: '#80a3ca'
            },
            {
                id: 'iphoneUsageData',
                name: 'Usage Time',
                data: [[1426291200000,5],[1426809600000,26]],
                type: 'line',
                yAxis: 1,
                tooltip: {
                    valueSuffix: ' sec'
                },
                color: '#c680ca'
            }
        ],

        func: function (chart) {
            console.log(chart);
            $scope.chartData = chart;
            $scope.chartExport = $.proxy(chart.exportChart, chart);
        }


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