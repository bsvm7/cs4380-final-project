<?php
?>
	

<html lang="en">
	
	<head>
		<!--	LOAD ANGULAR	-->
		<script src="bower_components/angular/angular.min.js" type="text/javascript"></script>
		<script src="bower_components/spin.js/spin.js"></script>
		<script type="text/javascript" src="bower_components/angular-spinner/angular-spinner.min.js"></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		
		<script type="text/javascript" src="js/controllers/registerController.js"></script>

		 <!-- CUSTOM STYLES
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <style type="text/css">
    
    body, html {
      background-color: #e7e9ec;
      font-family: Helvetica, Arial, sans-serif;
    }
    .box {
      margin-top: 10px;
      margin-bottom: 100px;
      padding-top: 40px;
      padding-bottom: 65px;
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
      font-size: 31px;
      font-weight: 300;
    }
    input[type=text] {
      margin-top: 0px;
    }
    input[type=password] {
      margin-top: 0px;
    }
    
    </style>
	</head>
	
	<body ng-app="linkedinApp">
		
		 <!-- NAVBAR
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <nav class="navbar navbar-inverse navbar-static-top" style="margin-bottom:0px">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">LinkedIn</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="login.php">Login</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <!-- REGISTER
    –––––––––––––––––––––––––––––––––––––––––––––––––– -->
    <div class="container-fluid" style="background-image: url('http://www.hdwallpapera.com/wp-content/uploads/wallpaper/dmhdwallpapers/wallpapers-005/Beautiful%20City%20Background%20Hd.jpg');
    background-size: cover;padding-top:20px;" ng-controller="RegistrationController as regCtrl">
      <div class="row">
        <div class="col-md-6 text-center col-md-offset-3 box">
          <h1 class="company-name">LinkedIn</h1>
          <h2 class="lead location">Register</h2>
          
          <div class="col-md-10 col-md-offset-1 text-left">
            <form name="reg_form">
	            <div class="form-group">
	                <label for="fname">First Name</label>
	                <input type="text" class="form-control" ng-model="reg_info.fname">
				</div>
	            
	            <div class="form-group">
	                <label for="mname">Middle Name</label>
	                <input type="text" class="form-control" ng-model="reg_info.mname">
				</div>
				<div class="form-group">
					<label for="lname">Last Name</label>
					<input type="text" class="form-control" ng-model="reg_info.lname">
				</div>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" ng-model="reg_info.username">
				</div>
				<div class="form-group">
					<label for="birthdate">Birth Date</label>
					<input type="text" class="form-control" ng-model="reg_info.birthdate">
					<small class="text-muted">Must be in the format -> mm/dd/yyyy</small>
				</div>
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" class="form-control" ng-model="reg_info.email">
				</div>
				<div class="form-group">
					<label for="gender">Gender</label>
					<input type="text" class="form-control" ng-model="reg_info.gender">
					<small class="text-muted">This response is private and optional</small>
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" ng-model="reg_info.password">
					<small class="text-muted">Must be more than 6 characters</small>
				</div>
				<div class="form-group">
					<label for="password">Confirm Password</label>
					<input type="password" class="form-control" id="password" name="confirm_password" ng-model="reg_info.confirm_password" compare-to="reg_info.password">
				</div>
				<button type="submit" class="btn btn-primary btn-block btn-lg create-button" ng-click="registerUser()" ng-disabled="isButtonDisabled()">Register</button>
            </form>
            <div ng-show="showLoadSpinner">
	            <span us-spinner spinner-key="reg-user-request-spinner"></span>
            </div>
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