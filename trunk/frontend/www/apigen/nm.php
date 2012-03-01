<?php

	require_once("../../server/bootstrap.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
<head>
<script type="text/javascript" src="http://api.caffeina.mx/jquery/jquery-1.4.2.min.js"></script>
<title>Web Framework</title>
<script>

       var new_category_form_visible = false;
       
       var edit_category_form_visible = false;
    
    function showEditCategoryForm(nombre,descripcion,id)
    {
        if(!edit_category_form_visible)
        {
            
            edit_category_form_visible = true;
            
            var html = '';
            html += '<div id="ec">'
            html += '<label> Nombre </label>';
            html += '<input type="text" name="nombre_clasificacion" id="nombre_clasificacion_edit" value="'+nombre+'">';
            html += '<label> Descripcion </label>';
            html += '<textarea name="descripcion_clasificacion" id="descripcion_clasificacion_edit">'+descripcion+'</textarea>';
            html += '<input type="hidden" name="id_proyecto" value="<?php if(isset($_GET["project"]) && is_numeric($_GET["project"])) echo $_GET["project"]; ?>">';
            html += '<input type="hidden" name="id_clasificacion" value='+id+'>';
            html += '<input type="button" onClick="validarCamposCategoriaEditar()" value="Enviar">';
            html += '<a onClick="hideEditCategoryForm()">Hide</a>';
            html += '</div>';

            $("#editar_categoria").append(html);
            
        }
    }
    
    function validarCamposCategoriaEditar()
    {
            if($.trim($("#nombre_clasificacion_edit").val())=="")
            {
                alert("Falta el nombre de la clasificacion");
                return;
            }
            if($.trim($("#nombre_clasificacion_edit").val()).search(/['"]+/)>=0)
            {
                alert("El nombre de la clasificacion tiene comillas simples o dobles, use en su lugar `");
                return;
            }
            if($.trim($("#descripcion_clasificacion_edit").val()).search(/['"]+/)>=0)
            {
                alert("La descripcion de la clasificacion tiene comillas simples o dobles, use en su lugar `");
                return;
            }
            $("#editar_categoria").submit();
    }
    
    function hideEditCategoryForm()
    {
        if(edit_category_form_visible)
            {
                edit_category_form_visible = false;
                
                $("#ec").remove();
            }
    }
    
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
            html += '<input type="button" onClick="validarCamposCategoria()" value="Enviar">';
            html += '<a onClick="hideNewCategoryForm()">Hide</a>';
            html += '</div>';

            $("#nueva_categoria").append(html);
            
        }
    }
    
    function validarCamposCategoria()
    {
            if($.trim($("#nombre_clasificacion").val())=="")
            {
                alert("Falta el nombre de la clasificacion");
                return;
            }
            if($.trim($("#nombre_clasificacion").val()).search(/['"]+/)>=0)
            {
                alert("El nombre de la clasificacion tiene comillas simples o dobles, use en su lugar `");
                return;
            }
            if($.trim($("#descripcion_clasificacion").val()).search(/['"]+/)>=0)
            {
                alert("La descripcion de la clasificacion tiene comillas simples o dobles, use en su lugar `");
                return;
            }
            $("#nueva_categoria").submit();
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
       
      function Borrar_categoria()
	  {
		 var selection = confirm("Esta seguro de querer borrar la categoria con todos su metodos?");
		 
		 if(selection)
			window.location="delete_cat.php?"<?php if(isset($_GET["cat"])) echo '+"&cat='.$_GET["cat"].'"'; if(isset($_GET["project"])) echo '+"&project='.$_GET["project"].'"'?>;
	  }
</script>

<link type="text/css" rel="stylesheet" href="../media/f.css"/>

</head>
<body class="safari4 mac Locale_en_US">
<input type="hidden" autocomplete="off" id="post_form_id" name="post_form_id" value="d8f38124ed9e31ef3947198c6d26bff1"/>
<div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;">
</div>
<div class="devsitePage">
    <div id="top-bar">
            <div id="header-content">
                <p class="logo">
                    <a href="">caffeina</a>
                </p>
                <ul class="nav">
                    <li><a href="https://labs.caffeina.mx/oficina/">Home</a></li>
                    <li><a href="https://labs.caffeina.mx/oficina/mantis">Bugs</a></li>
                    <li><a href="https://webframework.labs.caffeina.mx/">Web Framework</a></li>
                    <li class="last"><a href="https://labs.caffeina.mx/oficina/websvn/">WebSVN</a></li>
                </ul>
            </div><!-- header-content -->
        </div><!-- top-bar -->
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
                        
			

			if(isset($_GET["project"])&&  is_numeric($_GET["project"]))
                        {
                            echo '<a class="l" href="nm.php?project='.$_GET["project"];
                                if(isset($_GET["cat"])) 
                                    echo "&cat=".$_GET["cat"]; 
                            echo '">Nuevo metodo</a> ';
                        }
                            ?>
                    
			
                        
                        <a class="l" href="build.php<?php if(isset($_GET["project"])) echo '?project='.$_GET["project"] ?>">Generar</a>
			
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
                                    <div id="form_nueva_categoria">
                                            <a onClick="showNewCategoryForm()">Nueva categoria</a>
                                            <form id="nueva_categoria" method="POST" action="negocios_clasificacion.php">
                                                
                                            </form>
                                        </div>
					<ul>

						<?php
								$query = mysql_query("select * from clasificacion where id_proyecto=".$_GET["project"]." order by nombre;");
								
								while( ($row = mysql_fetch_assoc( $query )) != null )
								{
									if(isset($_GET["cat"]) && ($_GET["cat"] == $row["id_clasificacion"]) ){
										?>
										<li class="active withsubsections">
										<a class="selected" href="index.php?cat=<?php echo ''.$row["id_clasificacion"].'&project='.$_GET["project"]; ?>">
										<div class="navSectionTitle">
											<?php echo $row["nombre"]; ?>
										</div>
										</a>
                                                                                <div id="form_editar_categoria">
                                                                                    <a onClick="showEditCategoryForm(<?php echo "'".$row["nombre"]."','".$row["descripcion"]."',".$row["id_clasificacion"]?>)">Editar categoria</a>
                                                                                    
                                                                                    <form id="editar_categoria" method="POST" action="negocios_clasificacion_editar.php">

                                                                                    </form>
                                                                                </div>
                                                                                    <div id="borrar_categoria">
                                                                                        <a onClick="Borrar_categoria();">Borrar categoria</a>
                                                                                    </div>
										<ul class="subsections">
											
										<?php
										$argsq = mysql_query("select * from metodo where id_clasificacion = ". $row["id_clasificacion"] ." order by nombre;");

										while(($m = mysql_fetch_assoc($argsq)) != null)
										{
												
												$n = str_replace("api/", "", $m["nombre"] );
												$n = substr(  $n , strpos( $n , "/" ) +1 );
												echo '<li><a href="index.php?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'&project='.$_GET["project"].'">' . $n .  '</a></li>';
										}
										?>
										</ul>
										</li>
										<?php

									}else{

										?>
										<li>
										<a href="index.php?cat=<?php echo ''.$row["id_clasificacion"].'&project='.$_GET["project"]; ?>">
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

						<h1>Nuevo metodo</h1>
						
						
						<div class="breadcrumbs">
							<a href="apigen.php?project=<?php $_GET["project"] ?>">Regresar</a> 
						</div>
							

					</div>
				</div>


					<?php require_once( "new_method.php" ); ?>

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
