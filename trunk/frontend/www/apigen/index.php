<?php

	require_once("../../server/bootstrap.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>

<title>POS</title>
<script>
      function Borrar(id)
	  {
		 var selection = confirm("Esta seguro de querer borrar el método con todos sus argumentos y repsuestas?");
		 
		 if(selection)
			window.location="delete_method.php?m="+id+"&cat="+<?php echo $_GET["cat"];?>;
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
			<a class="logo" href="/">
				<img class="img" src="https://s-static.ak.facebook.com/rsrc.php/v1/yW/r/N2f0JA5UPFU.png" alt="Facebook Developers" width="166" height="17"/>
			</a>

			<?php
			if(isset($_GET["m"])){
				echo '<a class="l" href="edit_method.php?m='. $_GET["m"] .'&cat='.$_GET["cat"].'">Editar este metodo</a>';
				echo '<a class="l" onClick="Borrar('. $_GET["m"] .')">Borrar este metodo</a>';

			}
			?>

			
			<a class="l" href="new_method.php<?php if(isset($_GET["cat"])) echo "?cat=".$_GET["cat"];?>">Nuevo metodo</a>

			<!--
			<a class="l" href="/support/">Support</a>
			<a class="l" href="/blog/">Blog</a>
			<a class="l" href="">Apps</a>
			-->
			<a class="l" href="build.php">Generar</a>
			
			<div class="search">
				<form method="get" action="/search">
					<div class="uiTypeahead" id="u272751_1">
						<div class="wrap">
							<input type="hidden" autocomplete="off" class="hiddenInput" name="path" value=""/>
							<div class="innerWrap">
								<span class="uiSearchInput textInput">
								<span>
<?php

	//------------------------
	//	BUSCAR
	//------------------------
	class buscable{
			
	}
?>								
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
												echo '<li><a href="?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'">' . $n .  '</a></li>';
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
						
						<?php
							if(isset($_GET["mensaje"])){
								echo "<h3>" . $_GET["mensaje"] . "</h3>";
							}
							if(isset($_GET["m"])){
								$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);
								echo "<h1>" .$metodo["tipo"] . " " . $metodo["nombre"] . "</h1>";

							}else if(isset($_GET["cat"])){
								$res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);
								echo "<h1>" . $metodo["nombre"] . "</h1>";
								
							}
						?>
						
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
							<?php
							if(isset($_GET["cat"]) && !isset($_GET["m"])){
								$res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);
								
								echo "<p>" . $metodo["descripcion"] . "</p>";
							}
							?>

					</div>
				</div>

<!-- ----------------------------------------------------------------------
	 ---------------------------------------------------------------------- -->

						<?php
							if(!isset($_GET["m"]) && !isset($_GET["cat"])){
								$query = mysql_query("select * from clasificacion order by nombre;");
								
								while( ($row = mysql_fetch_assoc( $query )) != null )
								{

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
											
											
										echo '<li><a href="?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'">' . $m["nombre"] .  '</a></li>';
									}
									?>
									</ul>
									</li>
									<?php

								}
							}
						?>




				<p>
					<?php
					if(isset($_GET["m"])){
						$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
						$metodo = mysql_fetch_assoc($res);
						echo   $metodo["descripcion"] ;

					}
					?>
				</p>

				<?php
					if(isset($_GET["m"]))
					{
						
						if($metodo["regresa_html"]) echo "<h2>Regresa HTML </h2>";

					}
				?>

				<?php
					if(isset($_GET["m"])){
						$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
						$metodo = mysql_fetch_assoc($res);
						


						$argsq = mysql_query("select * from argumento where id_metodo = ". $metodo["id_metodo"] ." order by ahuevo desc,id_argumento asc;") or die(mysql_error());


						?>
						<h2>Argumentos</h2>
						<table class="methods" style="margin-left:0; width:100%">
						<tr>
							<th>
								Nombre
							</th>
							<th>
								Tipo
							</th>
							<th>
								Defaults
							</th>
							<th>
								Desc
							</th>
						</tr>

						<?php
						while(($argumento = mysql_fetch_assoc($argsq)) != null)
						{
							
							?>
							<tr>
							<td class="method">
								<code><?php 
									if($argumento["ahuevo"]) echo "<b>";
									echo $argumento["nombre"]; 
									if($argumento["ahuevo"]) echo "</b>";
								?></code>
							</td>
							<td class="desc">
								<code><?php echo $argumento["tipo"]; ?></code>
							</td>
							<td class="args">
								<?php //echo $argumento["ahuevo"];
								 echo $argumento["defaults"]; 
								?>
							</td>
							<td class="args">
								<?php echo $argumento["descripcion"]; ?>
							</td>							
							</tr>
							<?php
						}

						?>


						</table>
						<?php

						$argsr = mysql_query("select * from respuesta where id_metodo = ". $metodo["id_metodo"] .";") or die(mysql_error());


						?>
						<h2>Respuesta</h2>
						<table class="methods" style="margin-left:0; width:100%">
						<tr>
							<th>
								Nombre
							</th>
							<th>
								Tipo
							</th>
							<th>
								Desc
							</th>
						</tr>

						<?php
						while(($respuesta = mysql_fetch_assoc($argsr)) != null)
						{
							
							?>
							<tr>
							<td class="method">
								<code><?php echo $respuesta["nombre"]; ?></code>
							</td>
							<td class="desc">
								<code><?php echo $respuesta["tipo"]; ?></code>
							</td>
							<td class="args">
								<?php echo $respuesta["descripcion"]; ?>
							</td>							
							</tr>
							<?php
						}

						?>


						</table>


						<h2>Ejemplo peticion</h2>
						<pre style="margin-left:0"><code><?php echo $metodo["ejemplo_peticion"]; ?></code></pre>


						<h2>Ejemplo respuesta</h2>
						<pre style="margin-left:0"><code><?php echo $metodo["ejemplo_respuesta"]; ?></code></pre>

						<?php


					}else if(isset($_GET["cat"])){
		
						

						$q = "select * from metodo where id_clasificacion = " . $_GET["cat"] ."  order by nombre";
						$res = mysql_query( $q ) or die(mysql_error());

						while( ($row = mysql_fetch_assoc( $res )) != null )
						{
							echo "<h3><a href='index.php?cat=". $_GET["cat"] ."&m=". $row["id_metodo"] ."'>" . $row["tipo"] . " " . $row["nombre"] . "</a></h3>";
							echo "<p>" . $row["subtitulo"] . "</p>";
						}
					}
				?>
				

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
