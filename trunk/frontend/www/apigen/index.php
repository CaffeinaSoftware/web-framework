<?php

	require_once("../../server/bootstrap.php");
        
//        if(!isset($_SERVER["http_user"]))
//        {
//            $_SERVER["http_user"] = "andres";
//        }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>
<script type="text/javascript" src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js"></script>
<title>Web Framework</title>
<script>
    
    var new_category_form_visible = false;
    
    function showNewCategoryForm()
    {
        if(!new_category_form_visible)
        {
            
            new_category_form_visible = true;
            
            var html = '';
            html += '<div id="nc">'
            html += '<label> Nombre </label>';
            html += '<input type="text" name="nombre_clasificacion" id="nombre_clasificacion">';
            html += '<label> Descripcion </label>';
            html += '<textarea name="descripcion_clasificacion" id="descripcion_clasificacion"></textarea>';
            html += '<input type="hidden" name="id_proyecto" value="<?php if(isset($_GET["project"]) && is_numeric($_GET["project"])) echo $_GET["project"]; ?>">';
            html += '<input type="submit">';
            html += '<a onClick="hideNewCategoryForm()">Hide</a>';
            html += '</div>';

            $("#nueva_categoria").append(html);
            
        }
    }
    
    function hideNewCategoryForm()
    {
        if(new_category_form_visible)
            {
                new_category_form_visible = false;
                
                $("#nc").remove();
            }
    }
    
      function Borrar(id)
	  {
		 var selection = confirm("Esta seguro de querer borrar el método con todos sus argumentos y repsuestas?");
		 
		 if(selection)
			window.location="delete_method.php?m="+id<?php if(isset($_GET["cat"])) echo '+"&cat='.$_GET["cat"].'"'; if(isset($_GET["project"])) echo '+"&project='.$_GET["project"].'"'?>;
	  }
      
      function ProjectChange(val)
      {
          window.location = "index.php?project="+val;
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
			<a class="logo" href="../index.php">
				<img class="img" src="../media/cwhite.png" alt="Facebook Developers" width="166" height="17"/>
			</a>

			<?php
                        $proyecto = null;
                        if(isset($_GET["project"]) && is_numeric($_GET["project"]))
                        {
                            $proyecto = mysql_fetch_assoc(mysql_query(" Select * from mantis_project_table where id =".$_GET["project"]));
                        }
                        
			if(isset($_GET["m"])){
				echo '<a class="l" href="em.php?m='. $_GET["m"] .'&cat='.$_GET["cat"].'&project='.$_GET["project"].'">Editar</a>';
				echo '<a class="l" onClick="Borrar('. $_GET["m"] .')">Borrar</a>';

			}
			

			if(isset($_GET["project"])&&  is_numeric($_GET["project"]))
                        {
                            echo '<a class="l" href="nm.php?project='.$_GET["project"];
                                if(isset($_GET["cat"])) 
                                    echo "&cat=".$_GET["cat"]; 
                            echo '">Nuevo metodo</a> ';
                        }
                            ?>
                    
			
                        <?php
                        
                        if(isset($_GET["project"])&&  is_numeric($_GET["project"]))
                        {
                            ?>
                        <a class="l" href="build.php<?php echo '?project='.$_GET["project"] ?>">Generar</a>
                        <?php
                        }
                        ?>
			
			<a class="l" href="../httptesting/">Tester</a>
                        
                        <a class="l">Proyecto: 
                            
                        <select name="project" id="project" onChange = "ProjectChange(this.value)" >
                            <option value = "null"> ------------ </option>
                            <?php
                            
                            $query = "select id as id_proyecto,name as nombre from mantis_project_table";
                            $res = mysql_query($query);
                            while($row = mysql_fetch_assoc($res))
                            {
                                if(isset($_GET["project"]) && $_GET["project"] == $row["id_proyecto"])
                                {
                                    echo "<option value = ".$row["id_proyecto"]." selected>".$row["nombre"]."</option>";
                                }
                                else
                                {
                                    echo "<option value = ".$row["id_proyecto"].">".$row["nombre"]."</option>";
                                }
                            }
                            
                            ?>
                        </select>
			
                        </a>
                        
			
			
			<div class="clear">
			</div>
		</div>
	</div>
	<div class="body nav">
		<div class="content">
			<div id="bodyMenu" class="bodyMenu">
				<div class="toplevelnav">
                                    
                                    <?php if(isset($_GET["project"]) && is_numeric($_GET["project"]))
                                    {
                                    ?>
					<div id="form_nueva_categoria">
                                            <a onClick="showNewCategoryForm()">Nueva categoria</a>
                                            <form id="nueva_categoria" method="POST" action="negocios_clasificacion.php">
                                                
                                            </form>
                                        </div>
                                    <?php } ?>
                                    
                                    <ul>
						<?php
                                                
                                                            if(isset($_GET["project"]) && is_numeric($_GET["project"]) )
                                                            {
                                                                    $query = mysql_query("select * from clasificacion where id_proyecto=".$_GET["project"]." order by nombre ;");
                                                                
								
								while( ($row = mysql_fetch_assoc( $query )) != null )
								{
									if(isset($_GET["cat"]) && ($_GET["cat"] == $row["id_clasificacion"]) ){
										?>
										<li class="active withsubsections">
										<a class="selected" href="index.php?cat=<?php echo $row["id_clasificacion"]; ?>&project=<?php echo $_GET["project"]?>">
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
												echo '<li><a href="?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'&project='.$_GET["project"].'">' . $n .  '</a></li>';
										}
										?>
										</ul>
										</li>
										<?php

									}else{

										?>
										<li>
										<a href="index.php?cat=<?php echo $row["id_clasificacion"]; ?>&project=<?php echo $_GET["project"] ?>">
											<div class="navSectionTitle">
											<?php echo $row["nombre"]; ?>
											</div>
										</a>
										</li>
										<?php	
									}

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
                                                    <?php
                                                    if(!is_null($proyecto))
                                                    {
							echo '<a href=".?project='.$_GET["project"].'">'.$proyecto["name"].'</a><br> ';
                                                        echo $proyecto["description"];
                                                    }
                                                            
							if(isset($_GET["cat"])){
								$res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
								$metodo = mysql_fetch_assoc($res);

								echo'<br>&rsaquo; <a href=".?project='.$_GET["project"].'">'  . $metodo["nombre"] .  '</a>';
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
                                                if(isset($_GET["project"]) && is_numeric($_GET["project"]))
                                                {
							if(!isset($_GET["m"]) && !isset($_GET["cat"])){
								$query = mysql_query("select * from clasificacion where id_proyecto = ".$_GET["project"]." order by nombre;");
								
								while( ($row = mysql_fetch_assoc( $query )) != null )
								{

									?>
									<li class="active withsubsections">
									<a class="selected" href="index.php?cat=
                                                                        <?php echo $row["id_clasificacion"]; 
                                                                            echo "&project=".$_GET["project"] 
                                                                        ?>
                                                                           ">
									<div class="navSectionTitle">
										<?php echo $row["nombre"]; ?>
									</div>
									</a>
									<ul class="subsections">
										
									<?php
									$argsq = mysql_query("select * from metodo where id_clasificacion = ". $row["id_clasificacion"] ." order by nombre;");

									while(($m = mysql_fetch_assoc($argsq)) != null)
									{
											
											
										echo '<li><a href="?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'&project='.$_GET["project"].'">' . $m["nombre"] .  '</a></li>';
									}
									?>
									</ul>
									</li>
									<?php

								}
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
						


						$argsq = mysql_query("select * from argumento where id_metodo = ". $metodo["id_metodo"] ." order by ahuevo desc,nombre desc;") or die(mysql_error());


						?>
						<h2>Argumentos</h2>
						<table class="methods" style="margin-left:0; width:100%">
						<tr>
							<th style="border:1px solid #ddd;">
								Nombre
							</th>
							<th style="border:1px solid #ddd;">
								Tipo
							</th>
							<th style="border:1px solid #ddd;">
								Default
							</th>
							<th style="border:1px solid #ddd;">
								Descripcion
							</th>
						</tr>

						<?php
						while(($argumento = mysql_fetch_assoc($argsq)) != null)
						{
							
							?>
							<tr>
							<td class="method"  style="border:1px solid #ddd;">
								<code><?php 
									if($argumento["ahuevo"]) echo "<b>";
									echo $argumento["nombre"]; 
									if($argumento["ahuevo"]) echo "</b>";
								?></code>
							</td>
							<td class="desc"  style="border:1px solid #ddd;">
								<code><?php echo $argumento["tipo"]; ?></code>
							</td>
							<td class="args"  style="border:1px solid #ddd;">
								<?php //echo $argumento["ahuevo"];
								 echo $argumento["defaults"]; 
								?>
							</td>
							<td class="args"  style="border:1px solid #ddd;">
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
							<th  style="border:1px solid #ddd;">
								Nombre
							</th>
							<th style="border:1px solid #ddd;">
								Tipo
							</th>
							<th style="border:1px solid #ddd;">
								Desc
							</th>
						</tr>

						<?php
						while(($respuesta = mysql_fetch_assoc($argsr)) != null)
						{
							
							?>
							<tr>
							<td class="method" style="border:1px solid #ddd;">
								<code><?php echo $respuesta["nombre"]; ?></code>
							</td>
							<td class="desc" style="border:1px solid #ddd;">
								<code><?php echo $respuesta["tipo"]; ?></code>
							</td>
							<td class="args" style="border:1px solid #ddd;">
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
							echo "<h3><a href='index.php?cat=". $_GET["cat"] ."&m=". $row["id_metodo"] ."&project=".$_GET["project"]."'>" . $row["tipo"] . " " . $row["nombre"] . "</a></h3>";
							echo "<p>" . $row["subtitulo"] . "</p>";
						}
					}
				?>
				

				<hr/>

				<div class="mtm pvm uiBoxWhite topborder">
					<div class="mbm">
						
					</div>
					<abbr title="Monday, September 5, 2011 at 8:28pm" data-date="Mon, 05 Sep 2011 18:28:49 -0700" class="timestamp">
                                            Ultima modificacion 
                                        <?php 
                                            if(isset($_GET["m"]))
                                            {
                                                $registro = mysql_fetch_assoc(mysql_query("Select * from registro where id_metodo = ".$_GET["m"]));
                                                $time = strtotime($registro["fecha"]);
                                                echo " ".date("l",$time).", ".date("F",$time)." ".date("j",$time).", ".date("Y",$time)." at ".date("H:i:s",$time).". <br> Por ".$registro["usuario"];
                                            }
                                        ?>
                                        </abbr>
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
                            <a href="build_bd.php?project=<?php echo ( isset($_GET["project"]) && is_numeric($_GET["project"]) )? $_GET["project"] : "null" ; ?>">Respaldar Base de Datos</a>
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
