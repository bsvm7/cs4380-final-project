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
	
	
	var colorArr = ["#ffcd00", "#009dd9",  "#ff8300", "#b21dac",  "#d70036", "#707276", "#aaaaaa", "#000000", "#218535", "#92d050","#c4efff"];
// nannv chart
$(function () {
    $('#content1').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        colors: colorArr,
        title: {
            text: 'Age distribution'
        },
        subtitle: {
            text: ''
        },
        plotOptions: {
            pie: {
                 depth: 45
            }
        },
        series: [{
            name: 'number',
            data: [
                ['10~20', <?php echo $data; ?>],
                ['21~30', 10],
                ['31~40', 10],
                ['41~50', 10],
                ['51~60', 10],
                ['60~above', 10]
            ]
        }]
    });
});

        var width = $("#tab2").width();
        
//        line chart
 $(function () {
    $('#content2').highcharts({
        title: {
            text: 'System Traffic Trend Monitor',
            x: -20 //center
        },
        subtitle: {
            text: '(09/03/2016 - 21/03/2016)',
            x: -20
        },
        xAxis: {
            categories: ['day1', 'day2', 'day3', 'day4', 'day5', 'day6',
                'day7', 'day8', 'day9', 'day10', 'day11', 'day12']
        },
        yAxis: {
            title: {
                text: '# of login'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '#'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            color: colorArr[1],
            name: 'Traffic',
            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        },]
    });
});
        
        
//        accumulated column
        $(function () {
    $('#content3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Instructor Activism Monitor'
        },
        xAxis: {
            categories: ['ins1', 'ins2', 'ins3', 'ins4', 'ins5']
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            valueSuffix: '%',
            headerFormat: '<b>{point.x}</b><br/>',
           
            pointFormat: '{series.name}: {point.percentage:.1f}%<br/>总量: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black'
                    }
                }
            }
        },
        series: [{
            name: '# video submitted',
            color: colorArr[0],
            data: [300, 300, 40, 70, 200]
        }, {
            name: '# like&fav',
            color:  colorArr[1],
            data: [200, 400, 300, 200, 10]
        }, {
            name: '# comments',
            color: colorArr[2],
            data: [30, 200, 400, 200, 300]
        }]
    });
});
        
//fourth chart        
$(function () {
    $('#content4').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        colors: colorArr,
        title: {
            text: 'instructor video post distribution(7 day ago)'
        },
        subtitle: {
            text: ''
        },
        plotOptions: {
            pie: {
                 depth: 45
            }
        },
        series: [{
            name: 'number',
            data: [
                ['ins1', <?php echo $data; ?>],
                ['ins2', 10],
                ['ins3', 10],
                ['ins4', 10]
            ]
        }]
    });
});

//fifth chart
   $(function () {
    $('#content5').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Popular video Rank'
        },
        xAxis: {
            categories: ['ins1', 'ins2', 'ins3', 'ins4', 'ins5']
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            }
        },
        legend: {
            align: 'right',
            x: -30,
            verticalAlign: 'top',
            y: 25,
            floating: true,
            backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            valueSuffix: '%',
            headerFormat: '<b>{point.x}</b><br/>',
           
            pointFormat: '{series.name}: {point.percentage:.1f}%<br/>总量: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black'
                    }
                }
            }
        },
        series: [{
            name: '# video submitted',
            color: colorArr[1],
            data: [300, 200, 140, 70, 30]
        }]
    });
});

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