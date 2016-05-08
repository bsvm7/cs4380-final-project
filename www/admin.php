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
          <a class="navbar-brand" href="cs4380/home.php">Photoarchiving</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          </ul>
          <ul class="nav navbar-nav navbar-right">
	          <li ng-show="{{ user_info.user_level == 1 }}"><a href="cs4380/admin.php">Admin</a></li>
            <li><a href="cs4380/logout.php">Logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>  

<script>
   var colorArr = ["#ffcd00", "#009dd9",  "#ff8300", "#b21dac",  "#d70036", "#707276", "#aaaaaa", "#000000", "#218535", "#92d050","#c4efff"];

$(function () {
    $('#content1').highcharts({
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
                ['ins1',20],
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
 </script>

        <div class="col-sm-9 main_content" style = "padding-right:7%;">    
            <div class="main_body" >
                <div class="container" style="padding-top:30px;margin-left: 0px;" id = "body">
                       <div class="btn-pref btn-group btn-group-justified btn-group-lg top-btn-group" role="group" aria-label="..." style="margin-top: 10px;">
                    <div class="btn-group" role="group">
                        <button type="button" id="stars" class="btn btn-primary" href="#tab1" data-toggle="tab">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <div class="hidden-xs">Forsythe Family Logins</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="favorites" class="btn btn-default" href="#tab2" data-toggle="tab">
                            <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                                <div class="hidden-xs">Top 10 Popular Repositories</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-default" href="#tab3" data-toggle="tab">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                                <div class="hidden-xs">Top 5 Active Repositories</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-default" href="#tab4" data-toggle="tab">
                            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                                <div class="hidden-xs">New User Counts</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-default" href="#tab5" data-toggle="tab">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
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
