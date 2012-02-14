<?php

	require_once("../../server/bootstrap.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>

<title>POS</title>
<script>

      function Borrar(id){
		 var selection = confirm("Esta seguro de querer borrar el método con todos sus argumentos y repsuestas?");
		 
		 if(selection){
			window.location="../api_creation/delete_method.php?m="+id+"&cat="+<?php echo $_GET["cat"];?>;
		}
			
	  }
</script>

<link type="text/css" rel="stylesheet" href="../media/f.css"/>

</head>
<body class="safari4 mac Locale_en_US">
<input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;">
</div>
<div class="devsitePage">
	<div class="menu">
		<div class="content">
			<a class="logo" href="../">
				<img class="img" src="https://s-static.ak.facebook.com/rsrc.php/v1/yW/r/N2f0JA5UPFU.png" alt="Facebook Developers" width="166" height="17"/>
			</a>
			
			<a class="l" href="../apigen/new_method.php<?php if(isset($_GET["cat"])) echo "?cat=".$_GET["cat"];?>">Nuevo metodo</a>

			<a class="l" href="build.php">Generar Codigo</a>
			
	
			<div class="clear">
			</div>
		</div>
	</div>
	<div class="body nav">
		<div class="content">
			<div id="bodyMenu" class="bodyMenu">
				<div class="toplevelnav">
					<ul>

						<?php
								$query = mysql_query("select * from clasificacion order by nombre;");
								
								while( ($row = mysql_fetch_assoc( $query )) != null )
								{
									if(isset($_GET["cat"]) && ($_GET["cat"] == $row["id_clasificacion"]) ){
										?>
										<li class="active withsubsections">
										<a class="selected" href="index.php?cat=<?php echo $row["id_clasificacion"]; ?>">
										<div class="navSectionTitle">
											<?php echo $row["nombre"]; ?>
										</div>
										</a>
										<ul class="subsections">
											
										<?php
										$argsq = mysql_query("select * from metodo where id_clasificacion = ". $row["id_clasificacion"] ." order by nombre;");

										while(($m = mysql_fetch_assoc($argsq)) != null)
										{
												
												$n = str_replace("api/", "", $m["nombre"] );
												$n = substr(  $n , strpos( $n , "/" ) +1 );
												echo '<li><a href="index.php?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'">' . $n .  '</a></li>';
										}
										?>
										</ul>
										</li>
										<?php

									}else{

										?>
										<li>
										<a href="index.php?cat=<?php echo $row["id_clasificacion"]; ?>">
											<div class="navSectionTitle">
											<?php echo $row["nombre"]; ?>
											</div>
										</a>
										</li>
										<?php	
									}

								}
						?>

						
					</ul>
				</div>

				<ul id="navsubsectionpages">
					
				</ul>
			</div>
			<div id="bodyText" class="bodyText">
				<div class="header">
					<div class="content">

						<h1>Editar metodo</h1>
						
						
						<div class="breadcrumbs">
							<a href="api_doc.php">Regresar</a> 
						</div>
							

					</div>
				</div>


					<?php require_once( "edit_method.php" ); ?>

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
				 &copy; 2011
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
