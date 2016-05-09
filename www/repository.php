<?php
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Repository | Photoarchiving</title>

	<!-- Angular -->
	<script type="text/javascript" src="bower_components/angular/angular.min.js"></script>
	
	
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

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
    
    .image-list-container {
	    width: 75%;
	    float: left;
	    background-color: #8c2020;
    }
    
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body ng-app="photoarchiving_app" ng-controller="RepositoryController as repoCtrl">
  	<script type="text/javascript" src="js/controllers/repositoryController.js"></script>
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
	          <li><a href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

	<div class="container">
		<div class="row">
			<h1>{{ repo_info.title }}</h1>
		</div>	
		
		
		<div class="row">
			
			<div class="col-md-6">
			<h1>Search Modifiers</h1>
				<form name="myForm">
					<label for="eraSelect"> Era Select: </label>
					<select name="eraSelect" id="eraSelect" ng-model="eraData.selectedEra">
						<option ng-repeat="era in eraData.availableEras" value="{{era.era_id}}">{{era.name}}</option>
					</select>
					<button type="submit" class="btn btn-primary btn-block btn-sm create-button" ng-click="updatePhotosEra()">Update Photos</button>
				</form>
				<form name="myForm">
					<label for="relativeSelect"> Relative Select: </label>
					<select name="relativeSelect" id="relativeSelect" ng-model="relationsData.selectedRelation">
						<option ng-repeat="relation in relationsData.availableRelations" value="{{relation.related_to}}"> {{relation.relation}}: {{relation.related_to_fname}}</option>
					</select>
					<button type="submit" class="btn btn-primary btn-block btn-sm create-button" ng-click="updatePhotosRelation()">Update Photos</button>
				</form>
			</div>
		</div>
		
		<div class="row">
			<h1>Results</h1>
			<div ng-repeat="photo in photographs">
			<div class="media">
				<div class="media-left">
					<a href="#">
						<img class="media-object" ng-src="{{photo.thumb_url}}">
					</a>
				</div>
				<div class="media-body">
					<h4 class="media-heading"> {{ photo.title }} </h4>
					<a ng-href="http://40.86.85.30/cs4380/photograph.php?p_id={{photo.p_id}}">Link</a>
					<ul>
						<li>Date Taken: {{photo.date_taken}}</li>
						<li>Description: {{photo.description}}</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>
