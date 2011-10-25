<?php

	require_once("../server/bootstrap.php");



	#########################################################
	## retrive tests
	#########################################################
	if(!isset($_POST["tests"])) die(header("Location: ."));


	#########################################################
	## parse tests
	#########################################################
	$tparser = new TestParser( $_POST["tests"] );

	try{
		$tparser->parse();

	}catch(Exception $e){
		exit;

	}

	#########################################################
	## bit of configuration
	#########################################################	
	HTTPClient::setUrlBase( "http://127.0.0.1/caffeina/pos/branches/v1_5/www/front_ends/123/api");
	
	

	#########################################################
	## start testing
	#########################################################
	while($tparser->hasNextTest())
	{
		
		$tester = new Tester( $tparser->nextTest() );
		
		$tester->test();
					
	}


	

	
	








