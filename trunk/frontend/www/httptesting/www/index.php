<?php

	require_once("../server/bootstrap.php");

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


			<a class="l" href="index.php">UnitTester</a>
			<a class="l" href="index.php">ApiGen</a>
			

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
						<!--
						<div class="breadcrumbs">
							<a href=".">POS ERP</a> 
						</div>
						-->
						<p>Hacer llamadas HTTP al API. </p>

					</div>
				</div>

				<!-- ----------------------------------------------------------------------
					 ---------------------------------------------------------------------- -->
<script type="text/javascript">

	function save(){

		document.getElementById("forma").action = "save_tests.php";
		document.getElementById("forma").submit();
	}

</script>

<form action="test.php" method="POST" id="forma" >

<input type="submit" value="Iniciar pruebas">
<input type="button" onClick="save()" value="Guardar estas pruebas"><br>

<textarea
	name="url_base" 
	placeholder="Base url"
	cols="80"
>http://<?php echo $_SERVER["REMOTE_ADDR"]; ?>/caffeina/pos/branches/v1_5/www/front_ends/123/</textarea>


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


