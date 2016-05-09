<?php

	//	Include reference to sensitive databse information
	include("../../../db_security/security.php");
	
	$db_user = constant("DB_USER");
	$db_host = constant("DB_HOST");
	$db_pass = constant("DB_PASS");
	$db_database = constant("DB_DATABASE");
	
	//	First connect to the database using values from the included file
	$db_conn = new mysqli($db_host, $db_user, $db_pass, $db_database);
	
	if ($db_conn->error_code) {
			
		debug_echo( "database connection error ..." . "\n" );
		set_error_response( 400 , "I couldn't connect to the database -> " . $db_conn->connect_error);
		die("The connection to the database failed: " . $db_conn->connect_error);
	}
		
	debug_echo( "database connected" . "\n" );
	

	/*
	$req_method = $_SERVER['REQUEST_METHOD'];		

	switch ($req_method) {
		
		case 'POST':

			//	Get the raw post data
			//$json_raw = file_get_contents("php://input");
			
 			//echo $json_raw;
			
			//if ($decoded_json = json_decode($json_raw, true)) {	

				//$fileToUpload = $decoded_json['image'];

				//$target_dir= "http://40.86.85.30/cs4380/content/images/";
				//$target_file = $target_dir.basename($_FILES['your_photo']['name']);
*/
				if(!isset($_FILES['your_photo'])) {
				    debug_echo ('Please select an Image');
				    break;				
				}

				else{
					
					$image= $_FILES['your_photo']['name'];

					debug_echo ('Your image is '.$image);

					$image_check = getimagesize($image);
					/*
					if($image_check==false){
						debug_echo ('Not a valid Image...');
				  	 	break;	
					}
					else{
	*/
						define ("MAX_SIZE","100"); 

			            $file_name= stripslashes($_FILES['your_photo']['name']);
			            $extension = getExtension($file_name);
			            $extension = strtolower($extension);

			            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {           
		                    
		                    debug_echo ("Sorry! Unknown extension. Please JPG,JPEG,PNG and GIF only ");
		                    break;
		                }

		                else {

		                	debug_echo ("Image extension is good at ".$extension);
		                	$size=filesize($_FILES['your_photo']['tmp_name']);      
							debug_echo ("Image size is ".$size);

		                	if ($size < MAX_SIZE*1024) {

		                        //we will give an unique name, for example the time in unix time format
		                        $image_name=time().'.'.$extension;
		                        debug_echo ("temp Image name is ".$image_name);	

		                        //the new name will be containing the full path where will be stored (images folder)                                                        
		                        $newname="http://40.86.85.30/cs4380/content/images/".$image_name;                                                     
		                        debug_echo ("new Image name url ".$newname);	

		                        //we verify if the image has been uploaded, and print error instead                                                     
		                        //$copied = copy($_FILES['your_photo']['tmp_name'], $newname);                                                        

		                        $copied = copy($image, $newname);

		                        if (!$copied)                                                       
		                        {                                                       
		                            debug_echo ("Sorry, The Photo Upload was unsuccessfull!");                                                          
		                            break;                                                         
		                        }
		                        else{

									//Insert into database.Just use this particular variable "$image_name" when you are inserting into database
	    						    $insert_image_sql="INSERT INTO photograph (large_url) VALUES ( ? )"; 

	    						    if(!($insert_image_stmt=$db_conn->prepare($insert_image_sql))) {

	    						    	debug_echo ("Sorry, insert image stmt prepare failed ... ");                                                          
		                            	break;  
	    						    }

	    						    if(!($insert_image_stmt->bind_param("s", $newname))) {
	    						    	debug_echo ("Sorry, insert image stmt bind param failed ...!");                                                          
		                           		 break;  

	    						    }

	    						    if(!($insert_image_stmt->execute()) ) {
	    						    	debug_echo ("Sorry, insert image stmt execute failed ...!");                                                          
		                            	break;  
									
									}
									else{
										debug_echo ("photo has been successfully uploaded... ");                                                          
		                            	break;  
									}

		                        }

		                    }                                               
		                    else {       
		                        debug_echo ("You Have Exceeded The Photo Size Limit");          
		                      	break;                           
		                    }   
		                }
		           // }
		            $db_conn->close(); 
					debug_echo ("database has been closed successfully.....");
		        }
		    /*
		    }
			else{
				set_error_response( 201, "SQL Error -> " . $hash_retrieve_stmt->error);	
				debug_echo ("input data can not be decoded.....");
				break;
			}				
*/
			
	      
/*
		break;

		default:

		break;

	}	
*/


    function getExtension($str) {   
        $i = strrpos($str,".");
        if (!$i) { 
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);
        return $ext; 
    }

	function debug_echo( $str ) {
		
		$echo_debug = true;
		
		if ($echo_debug) {
			echo $str;
		}
	}

?>