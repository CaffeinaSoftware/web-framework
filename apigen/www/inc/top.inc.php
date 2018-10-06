<?php
    $_GET["project"] = 1;
    require_once("../server/bootstrap.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" >
    <head>
        <script src="//code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        <title>Web Framework</title>
        <script src="js.js"></script>
        <link type="text/css" rel="stylesheet" href="media/f.css"/>
    </head>
    <body class="safari4 mac Locale_en_US">

        <div class="devsitePage">

        <div class="menu">
            <div class="content">
                <?php
                $idMetodo = isset($_GET["m"]) ? $_GET["m"] : null;
                $idCategoria = isset($_GET["cat"]) ? $_GET["cat"] : null;
                $idProyecto = isset($_GET["project"]) ? $_GET["project"] : null;

                if (isset($_GET["m"]))
                {
                    // Top menu
                    echo "<a class=\"l\" href=\"editmethod.php?m=$idMetodo&cat=$idCategoria&project=$idProyecto\">Editar</a>";
                    echo "<a class=\"l\" onClick=\"Borrar($idMetodo)\">Borrar</a>";
                }

                if(isset($_GET["project"]) && is_numeric($_GET["project"]))
                {
                    $cat = isset($_GET["cat"]) ? "&cat=$idCategoria" : "";
                    echo "<a class=\"l\" href=\"newmethod.php?project=$idProyecto$cat\">Nuevo metodo</a>";
                }

                if(isset($_GET["project"])&&  is_numeric($_GET["project"]))
                {
                    echo "<a class=\"l\" href=\"build.php?project=$idProyecto\">Generar</a>";
                }
                ?>

                <div class="clear"> </div>
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
                            $query = mysql_query("select * from clasificacion where id_proyecto=".$_GET["project"]." order by nombre ;");
                            while (($row = mysql_fetch_assoc( $query )) != null)
                            {
                                // Mostrar todas las categorias y expandir la que esta seleccinada
                                if(isset($_GET["cat"]) && ($_GET["cat"] == $row["id_clasificacion"]) )
                                {
                                ?>
                                    <li class="active withsubsections">
                                        <a class="selected" href="index.php?cat=<?php echo $row["id_clasificacion"]; ?>&project=<?php echo $_GET["project"]?>">
                                            <div class="navSectionTitle">
                                                <?php echo $row["nombre"]; ?>
                                            </div>
                                        </a>

                                        <div id="form_editar_categoria">
                                            <a onClick="showEditCategoryForm(<?php echo "'".$row["nombre"]."','".$row["descripcion"]."',".$row["id_clasificacion"]?>)">Editar categoria</a>
                                            <form id="editar_categoria" method="POST" action="negocios_clasificacion_editar.php"> </form>
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
                                                echo '<li><a href="?&cat='.$row["id_clasificacion"].'&m='.$m["id_metodo"].'&project='.$_GET["project"].'">' . $n .  '</a></li>';
                                            }
                                         ?>
                                        </ul>
                                        </li>
                                        <?php
                                } else {
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
                        ?>
                        </ul>
                    </div>
                </div>

            <div id="bodyText" class="bodyText">

                <div class="header">
                    <div class="content">
                        <?php
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
                           if(isset($_GET["cat"]) && !empty($_GET["cat"])){
                                $res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
                                $metodo = mysql_fetch_assoc($res);
                                echo'<br>&rsaquo; <a href=".?project='.$_GET["project"].'">'  . $metodo["nombre"] .  '</a>';
                           }
                           ?>
                        </div>

                        <?php
                        if (isset($_GET["cat"]) && !isset($_GET["m"])) {
                             $res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
                             $metodo = mysql_fetch_assoc($res);
                             echo "<p>" . $metodo["descripcion"] . "</p>";
                        }
                        ?>
                    </div>
                </div>

