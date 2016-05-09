<?php
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Home | Photoarchiving</title>
	
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

	<!-- Highcharts -->
	<script src="js/highcharts/highcharts.js"></script>
    <script src="js/highcharts/exporting.js"></script>
	<script src="js/highcharts/highcharts-more.js"></script>
    <!-- CUSTOM STYLES
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    
    <link rel="stylesheet" href="css/custom.css">
    
    <style type="text/css">
    
    body, html {
      background-color: #e7e9ec;
      font-family: Helvetica, Arial, sans-serif;
    }
    .box {
      margin-top: 10px;
      padding-top: 40px;
      padding-bottom: 40px;
      background-color: white;
      -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
      -moz-box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
      box-shadow: 0 1px 1px rgba(0,0,0,0.15),-1px 0 0 rgba(0,0,0,0.03),1px 0 0 rgba(0,0,0,0.03),0 1px 0 rgba(0,0,0,0.12);
    }
    .location {
      margin-top: 0px;
      margin-bottom: 10px;
    }
    .company-name {
      margin-bottom: 0px;
      font-size: 60px;
    }
    .company-desc {
      text-align: left;
    }
    .footer p {
      margin: 15px 0px;
    }
    .form-group label {
      color: #66696A;
      font-weight: 500;
      margin-bottom: 0px;
    }
    .create-button {
      margin-top: 30px;
    }
    
	.highcharts-container{
		width:100%;   
	}

	.well {
		min-height: 20px;
		padding: 0;
		margin-bottom: 20px;
		background-color: #f5f5f5;
		border: 1px solid #e3e3e3;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
	}
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="photoarchiving_app" ng-controller="AdminController as adminCtrl">
	  
  	
    <!-- NAVBAR
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <nav class="navbar navbar-inverse navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home.php">Photoarchiving</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          </ul>
          <ul class="nav navbar-nav navbar-right">
	          <li ng-show="{{ user_info.user_level == 1 }}"><a href="admin.php">Admin</a></li>
            <li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>  

<script>
$(function () {
    $('#content1').highcharts({
		chart: {
            type: 'column'
        },
        title: {
            text: 'Forsythe Family Logins (April)'
        },
        subtitle: {
            text: 'Logins by all Forsythe Family Members'
        },
        xAxis: {
            categories: [
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
                '12',
                '13',
                '14',
                '15',
                '16',
                '17',
                '18',
                '19',
                '20',
                '21',
                '22',
                '23',
                '24',
                '25',
                '26',
                '27',
                '28',
                '29',
                '30',
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Views'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} views</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Forsythe Repository',
            data: [23,64,28,12,10,75,56,55,51,31,8,7,22,83,3,17,45,30,18,64,24,68,22,14,11,10,8,9,3,2]

        }]
    });
});

        var width = $("#tab2").width();
        
//        line chart
 $(function () {
		$('#content2').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Top 10 most frequently visited repositories'
        },
        xAxis: {
            categories: ['Mizzou','Health Care Center', 'Reacreation Center','Tom','Columbia','Jefferson','Ashland','Database','CSBA','Jingo'],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Visitors',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ''
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Past Month',
            data: [107, 31,635,203,2,70,95,1250,345,503]
        }]
    });
});
        
        
//        accumulated column
        $(function () {
    $('#content3').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top 5 active repositories for the past month'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Total percent activities participated'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },

        series: [{
            name: 'Repositories',
            colorByPoint: true,
            data: [{
                name: 'Columbia',
                y: 56.33,
            }, {
                name: 'Community',
                y: 24.03,
            }, {
                name: 'Jefferson',
                y: 10.38,
            }, {
                name: 'Health Care Center',
                y: 4.77,
            }, {
                name: 'Mizzou',
                y: 0.91,
            }]
        }],
    });
});
        
//fourth chart        
$(function () {
    $('#content4').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'count of new users broken down by gender'
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
        series: [{
            name: 'gender',
            data: [
                { name: 'Female', y: 453 },
                { name: 'Male', y: 547 }
            ]
        }]
    });
});
//fifth chart
   $(function () {
	   $('#content5').highcharts({
		chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Age Distribution'
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
        series: [{
            name: 'Age Period',
            colorByPoint: true,
            data: [{
                name: 'Age 60+',
                y: 13.3
            }, {
                name: 'Age 50-60',
                y: 10.5
            }, {
                name: 'Age 35-50',
                y: 18.4
            }, {
                name: 'Age 25-35',
                y: 36.8
            }, {
                name: 'Age 15-25',
                y: 13.9
            }, {
                name: 'Age 0-15',
                y: 7.1
            }]
        }]
    });
});
 </script>

        <div class="col-sm-9 main_content" style = "padding-right:7%;">    
            <div class="main_body" >
                <div class="container" style="padding-top:30px;margin-left: 30px" id = "body">
                       <div class="btn-pref btn-group btn-group-justified btn-group-lg top-btn-group" role="group" aria-label="..." style="margin-top: 10px;">
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-primary" href="#tab1" data-toggle="tab">
                            <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                                <div class="hidden-xs">Forsythe Family Logins</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-primary" href="#tab2" data-toggle="tab">
                            <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                                <div class="hidden-xs">Top 10 Popular Repositories</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-primary" href="#tab3" data-toggle="tab">
                            <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                                <div class="hidden-xs">Top 5 Active Repositories</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-primary" href="#tab4" data-toggle="tab">
                            <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                                <div class="hidden-xs">New User Counts</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-primary" href="#tab5" data-toggle="tab">
                            <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
                                <div class="hidden-xs">Age Distribution</div>
                        </button>
                    </div>
                </div>
            
                <div class="well">
                    <div class="tab-content">
                 <!--          start of tab1-->
                        <div class="tab-pane fade in active" id="tab1">
                            <div class="panel panel-default info_panel" >
                                 <div class="container"  style="height: 60vh;" id = "content1">
                                
                                </div>
                          </div> 
                        </div>
                        
                <!-- end tab1-->
                        
                        <div class="tab-pane fade in active" id="tab2">
                             <div class="panel panel-default info_panel container">
                                     <div id = "content2" style="height: 60vh;">
                                     </div>
                             </div> 
                          </div>

                        <div class="tab-pane fade in active" id="tab3">
                             <div class="panel panel-default info_panel">
                                 <div class="container" >
                                    <div id = "content3" style="height: 60vh;">
                                     </div>
                                </div>
                            </div> 
                         </div>
                        
                         <div class="tab-pane fade in active" id="tab4">
                             <div class="panel panel-default info_panel">
                                 <div class="container" >
                                    <div id = "content4" style="height: 60vh;">
                                     </div>
                                </div>
                            </div> 
                         </div>
                        
                         <div class="tab-pane fade in active" id="tab5">
                             <div class="panel panel-default info_panel">
                                 <div class="container" >
                                    <div id = "content5" style="height: 60vh;">
                                     </div>
                                </div>
                            </div> 
                         </div>

                      </div>

                </div> <!--/* main_body-->
             </div>
        </div>
        



   
  </body>
</html>
