<?php

	require_once("../../server/bootstrap.php");

?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<script type="text/javascript" charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<title>HTTP Testing | Caffeina WebFramework</title>
	<link type="text/css" rel="stylesheet" href="../media/f.css"/>
	<link rel="stylesheet" href="http://api.facebook.com/facebox/facebox.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript" src="http://api.facebook.com/facebox/facebox.js" charset="utf-8"></script>
	
	<script type="text/javascript" charset="utf-8">
	
		$.extend({
		  getUrlVars: function(){
		    var vars = [], hash;
		    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		    for(var i = 0; i < hashes.length; i++)
		    {
		      hash = hashes[i].split('=');
		      vars.push(hash[0]);
		      vars[hash[0]] = hash[1];
		    }
		    return vars;
		  },
		  getUrlVar: function(name){
		    return $.getUrlVars()[name];
		  }
		});
		
		
		
		var httptesting = {
			
			ajax : function(p, c){
				
				$.ajax({
					url: "api.php",
					type : "POST",
					data: p,
					success: function(a,b,parseOnReturn){
						var r;
						if(parseOnReturn !== true){
							if(c === undefined){
								alert("ok");
							}else{
								c.call(null, a);							
							}
							return;
						}
						try{
				    		r = $.parseJSON(a);
						}catch( e){
							console.error(e);
							alert("Error");
							return;
						}
						
						if(r.status != "ok"){
							alert("error");
							return;
						}
						
						if(c === undefined){
							alert("ok");
						}else{
							c.call(null, r);							
						}


				  	}
				});
			},
			
			test : function(){
				
				//Test params
				var url 	= $.getUrlVar('url'),
					test 	= $.getUrlVar('test');
				
				if(
					url === undefined 
					|| test === undefined
				){
					alert("Faltan parametros");
					return;
				}
				
				var callback = function( res ){
					$("#response").html(res).show();
				}
				
				$("textarea").hide();
				
				$("#response").html("<div align=center><img src='l.gif'> Realizando pruebas...</div>").show();
				
				this.ajax({
					metodo	: "test",
					url 	: url,
					test_id	: test
				},callback, false);
			} 
			
		}
	</script>
</head>
<body class="mac Locale_en_US">
	<div class="devsitePage">
		<div class="menu">
			<div class="content">
				<a class="logo" href="../index.php">
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
					<?php
						$s = mysql_query("select * from httptesting_paquete_de_pruebas");
						while( ($row = mysql_fetch_assoc($s))  != null){
							if(isset($_GET["test"]) && $_GET["test"] == $row["id_paquete_de_pruebas"]){
								$style = "background: gray; ";							
							}else{
								$style = "";
							}
							
							echo "<div style='". $style ."'>"; 
							$hop = "?test=" . $row["id_paquete_de_pruebas"] . "&" ; 
							if(isset( $_GET["url"] )){ 
								$hop .= "url=" . $_GET["url"]; 
							} 
							echo "<a href='".$hop."'><h3 style='margin-bottom:0px'>" . $row["nombre"] . "</h3></a>"; 
							echo "<p>" . $row["descripcion"] . "</p>"; 
							echo "</div>"; 
						} 
						?>
			</div>
			<div id="bodyText" class="bodyText">
				<div class="header">
					<div class="content">
						<h1>HTTP REST API Test Suite</h1>
						<p>
							Hacer llamadas HTTP al API.
						</p>
					</div>
				</div>
				<span id="selector">
				URL de pruebas
				<select name="cSelect">
					<?php
					/**
					 * Obtener las urls
					 * 
					 * */
					$res = mysql_query("select * from httptesting_ruta");
					while( ($row = mysql_fetch_assoc($res)) != null ){
						if(isset($_GET["url"]) && $_GET["url"] == $row["id_ruta"]){
							echo "<option selected id='". $row["id_ruta"] ."'>" . $row["nombre"] . " | " . $row["ruta"] . "</option>"; 
						}else{ 
							echo "<option id='". $row["id_ruta"] ."'>" . $row["nombre"] . " | " . $row["ruta"] . "</option>"; 
						} 
					} 
					
					?>
				</select>
				<input type="button" name="" value="Iniciar pruebas" onClick="httptesting.test()" >
				
				</span>
				<hr/>
				
				<div id="response"></div>
				
				<?php
				$s = mysql_query("select * from httptesting_paquete_de_pruebas");
				while( ($row = mysql_fetch_assoc($s))  != null){
					if(isset($_GET["test"]) && $_GET["test"] == $row["id_paquete_de_pruebas"]){
						echo "<div style=''>"; 
					}else{ 
						echo "<div style='display:none'>"; 
					} 
					
					echo '<textarea disabled cols="80" rows="20" name="tests">' . $row["pruebas"] . "</textarea>"; 
					echo "</div>"; 
				} 
				?>
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
				<a href="">About</a>
				<a href="">Platform Policies</a>
				<a href="">Privacy Policy</a>
			</div>
		</div>
	</div>
	</div>
</body>
</html>


