<?php
?>
<html lang="en">
	
	<head>
		<script type="text/javascript" src="bower_components/angular/angular.min.js"></script>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>
	
	<body>
		
		 <!-- NAVBAR -->
	    <nav class="navbar navbar-inverse navbar-static-top">
	      <div class="container">
	        <div class="navbar-header">
	          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	            <span class="sr-only">Toggle navigation</span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </button>
	          <a class="navbar-brand" href="/index.php">LinkedIn</a>
	        </div>
	        <div id="navbar" class="navbar-collapse collapse">
	          <ul class="nav navbar-nav">
	          </ul>
	          <ul class="nav navbar-nav navbar-right">
	          </ul>
	        </div>
	      </div>
	    </nav>
	    
	    
	    <script>
		    
		 	//	First remove the 'auth_token' & 'expires_in' values from the session storage
		 	sessionStorage.removeItem("auth_token");
		    sessionStorage.removeItem("expires_in");
		    sessionStorage.removeItem("ps_id");
		    
		    //	Next redirect to the login page
	
		    var redirect_url = "/login.php";
		    
		    window.location.replace( redirect_url );
		    
		    
		</script>
	    
	</body>
</html>
