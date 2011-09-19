<?php

	require_once("../../server/bootstrap.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>
<meta charset="utf-8"/>
<title>POS</title>


<link type="text/css" rel="stylesheet" href="../css/f.css"/>

<head>
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
				echo '<a class="l" href="../api_creation/edit_method.php?m='. $_GET["m"] .'">Editar este metodo</a>';

			}
			?>
			<a class="l" href="/support/">Support</a>
			<a class="l" href="/blog/">Blog</a>
			<a class="l" href="">Apps</a>

			<div class="search">
				<form method="get" action="/search">
					<div class="uiTypeahead" id="u272751_1">
						<div class="wrap">
							<input type="hidden" autocomplete="off" class="hiddenInput" name="path" value=""/>
							<div class="innerWrap">
								<span class="uiSearchInput textInput"><span><input type="text" class="inputtext DOMControl_placeholder" name="selection" placeholder="Search Documentation / Apps" autocomplete="off" onfocus="return wait_for_load(this, event, function() &#123;window.ArbiterMonitor &amp;&amp; ArbiterMonitor.pause();;JSCC.get(&#039;j4e77526729eb92f593566592&#039;).init([&quot;submitOnSelect&quot;]);;;window.ArbiterMonitor &amp;&amp; ArbiterMonitor.resume();;&#125;);" spellcheck="false" value="Search Documentation / Apps" title="Search Documentation / Apps"/><button type="submit" title="Search Documentation / Apps"><span class="hidden_elem"></span></button></span></span>
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
										<a class="selected" href="api_doc.php?cat=<?php echo $row["id_clasificacion"]; ?>">
										<div class="navSectionTitle">
											<?php echo $row["nombre"]; ?>
										</div>
										</a>
										<ul class="subsections">
											
										<?php
										$argsq = mysql_query("select * from metodo where id_clasificacion = ". $row["id_clasificacion"] .";");

										while(($m = mysql_fetch_assoc($argsq)) != null)
										{
												
												$n = str_replace("api/", "", $m["nombre"] );
												echo '<li><a href="?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'">' . $n .  '</a></li>';
										}
										?>
										</ul>
										</li>
										<?php

									}else{

										?>
										<li>
										<a href="api_doc.php?cat=<?php echo $row["id_clasificacion"]; ?>">
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
							if(isset($_GET["m"])){
								$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);
								echo "<h1>" . $metodo["nombre"] . "</h1>";

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
						
					</div>
				</div>

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
					if(isset($_GET["m"])){
						$res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
						$metodo = mysql_fetch_assoc($res);
						
						?>

						<h2>Ejemplo peticion</h2>
						<pre><code><?php echo $metodo["ejemplo_peticion"]; ?></code></pre>


						<h2>Ejemplo respuesta</h2>
						<pre><code><?php echo $metodo["ejemplo_respuesta"]; ?></code></pre>

						<?php

						$argsq = mysql_query("select * from argumento where id_metodo = ". $metodo["id_metodo"] ." order by ahuevo desc;") or die(mysql_error());


						?>
						<h2>Argumentos</h2>
						<table class="methods" style="margin-left:auto; margin-right:auto">
						<tr>
							<th>
								Nombre
							</th>
							<th>
								Tipo
							</th>
							<th>
								A huevo
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
								<?php echo $argumento["ahuevo"]; ?>
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
						<table class="methods" style="margin-left:auto; margin-right:auto">
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
						<?php
					}
				?>
				

				<hr/>

				<div class="mtm pvm uiBoxWhite topborder">
					<div class="mbm">
						
					</div>
					<abbr title="Monday, September 5, 2011 at 8:28pm" data-date="Mon, 05 Sep 2011 18:28:49 -0700" class="timestamp">Updated about a week ago</abbr>
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