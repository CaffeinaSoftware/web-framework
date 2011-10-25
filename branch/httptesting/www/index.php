<?php

	require_once("../server/bootstrap.php");


	HTTPClient::setUrlBase( "http://127.0.0.1/caffeina/pos/branches/v1_5/www/front_ends/123/api");

	
	$r = HTTPClient::POST( "/personal/rol/nuevo/", array( "asdf" => "asf" ) );

	//var_dump($r);
	//echo $r["header"];



	echo $r["content"];








