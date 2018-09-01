<div class="toplevelnav">
<?php 
                if(isset($_GET["project"]) && is_numeric($_GET["project"]))
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
