<?php

	require_once("../../server/bootstrap.php");

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>

<title>HTTP Testing | Caffeina WebFramework</title>


<link type="text/css" rel="stylesheet" href="../media/f.css"/>

</head>
<body class="safari4 mac Locale_en_US">
<input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;">
</div>
<div class="devsitePage">
	<div class="menu">
		<div class="content">
			<a class="logo" href="../index.php">
				
				<!-- https://s-static.ak.facebook.com/rsrc.php/v1/yW/r/N2f0JA5UPFU.png -->
				<img class="img" src="../media/cwhite.png" alt="Facebook Developers" width="166" height="17"/>
				

				
			</a>


			<a class="l" href="index.php">UnitTester</a>
			<a class="l" href="../apigen/">ApiGen</a>
			


			<div class="clear">
			</div>
		</div>
	</div>
	<div class="body nav">
		<div class="content">
			<div id="bodyMenu" class="bodyMenu">
				<div>
					<h3>Paquete</h3>
				</div>
				<div>
					<h3>Paquete</h3>
				</div>				
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
						<!--
						<div class="breadcrumbs">
							<a href=".">POS ERP</a> 
						</div>
						-->
						<p>Hacer llamadas HTTP al API. </p>

					</div>
				</div>

</head>
				<form action="test.php" method="POST" id="forma" >




<body>
<form>
<span id="selector">
	URL de pruebas
	<select name="cSelect" >
		<?php
			/**
			 * Obtener las urls
			 * 
			 * */
			$res = mysql_query("select * from httptesting_ruta");
			
			while( ($row = mysql_fetch_assoc($res)) != null ){

				if(isset($_GET["url"]) && $_GET["url"] == $row["id_ruta"]){
					echo "<option selected id='". $row["id_ruta"] ."'>" . $row["nombre"] . " | " . $row["ruta"] . "adsfadsf</option>";
				}else{
					echo "<option id='". $row["id_ruta"] ."'>" . $row["nombre"] . " | " . $row["ruta"] . "adsfadsf</option>";
				}
			}
		?>
	</select>
</span>
</form>

<br>

<textarea cols="80"  rows="50" name="tests"  ><?php

	$tests  = mysql_fetch_assoc(mysql_query("select * from http_tests limit 1;")) or die (mysql_error());
	echo $tests["tests"]; 

?>

</textarea>
					<br>
						
					</form>
	 		
				
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

			</div>
			<div class="links">
				<a href="https://www.facebook.com/platform">About</a><a href="/policy/">Platform Policies</a><a href="https://www.facebook.com/policy.php">Privacy Policy</a>
			</div>
		</div>
	</div>



	
</div>

</body>
</html>


