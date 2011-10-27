<?php

	require_once("../server/bootstrap.php");
	if(!isset($_POST["tests"])) die(header("Location: ."));

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>

<title>POS</title>


<link type="text/css" rel="stylesheet" href="css/f.css"/>

</head>
<body class="safari4 mac Locale_en_US">
<input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;">
</div>
<div class="devsitePage">
	<div class="menu">
		<div class="content">
			<a class="logo" href="/">
				
				<!-- https://s-static.ak.facebook.com/rsrc.php/v1/yW/r/N2f0JA5UPFU.png -->
				<img class="img" src="cwhite.png" alt="Facebook Developers" width="166" height="17"/>
				

				
			</a>


			<a class="l" href="test.php">Iniciar Pruebas</a>
			<a class="l" href="Salir">Salir</a>

			<div class="search">
				<form method="get" action="/search">
					<div class="uiTypeahead" id="u272751_1">
						<div class="wrap">
							<input type="hidden" autocomplete="off" class="hiddenInput" name="path" value=""/>
							<div class="innerWrap">
								<span class="uiSearchInput textInput">
								<span>						
								<input 
									type="text" 
									class="inputtext DOMControl_placeholder" 
									name="selection" 
									placeholder="Buscar" 
									autocomplete="off" 
									onfocus="" 
									spellcheck="false"
									title="Search Documentation / Apps"/>
								<button type="submit" title="Search Documentation / Apps">
								<span class="hidden_elem">
								</span>
								</button>
								</span>
								</span>
							</div>
						</div>
									
						


					</div>
				</form>
			</div>
			<div class="clear">
			</div>
		</div>
	</div>
	<div class="body nav">
		<div class="content">
			<div id="bodyMenu" class="bodyMenu">
				<div class="toplevelnav">
					<ul>

					</ul>
				</div>

				<ul id="navsubsectionpages">
					
				</ul>
			</div>
			<div id="bodyText" class="bodyText">
				<div class="header">
					<div class="content">
						
						
						<h1>HTTP REST API Test Suite</h1>
						<div class="breadcrumbs">
							<a href=".">POS ERP</a> 
							<?php
							if(isset($_GET["cat"])){
								$res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);

								echo'&rsaquo; <a href=".">'  . $metodo["nombre"] .  '</a>';
							}
							?>
							
						</div>


					</div>
				</div>

				<!-- ----------------------------------------------------------------------
					 ---------------------------------------------------------------------- -->


<?php



?><pre><?php

	



	#########################################################
	## retrive tests
	#########################################################
	


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
	HTTPClient::setUrlBase( $_POST["url_base"] );
	
	

	#########################################################
	## start testing
	#########################################################
	while($tparser->hasNextTest())
	{
		
		$tester = new Tester( $tparser->nextTest() );
		


		if($tester->test() === true){
			//passed
			

		}else{
			//valio madres
			
			
		}
					
	}


?></pre>	
	 		
				
				<!-- ----------------------------------------------------------------------
					 ---------------------------------------------------------------------- -->
				<hr/>

				<div class="mtm pvm uiBoxWhite topborder">
					<div class="mbm">
						
					</div>
					<abbr title="Monday, September 5, 2011 at 8:28pm" data-date="Mon, 05 Sep 2011 18:28:49 -0700" class="timestamp">Ultima modifiacion</abbr>
				</div>

			</div>

			<div class="clear">
			</div>

		</div>
	</div>
	<div class="footer">
		<div class="content">
			<div class="copyright">
				x © 2011
			</div>
			<div class="links">
				<a href="https://www.facebook.com/platform">About</a><a href="/policy/">Platform Policies</a><a href="https://www.facebook.com/policy.php">Privacy Policy</a>
			</div>
		</div>
	</div>
	<div id="fb-root">
	</div>
	<input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
	<div id="fb-root">
	</div>
	
</div>

</body>
</html>



	
	








