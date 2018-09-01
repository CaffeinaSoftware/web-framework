
    <div class="menu">
        <div class="content">
            <?php
            $proyecto = null;
            if(isset($_GET["project"]) && is_numeric($_GET["project"]))
            {
                $proyecto = mysql_fetch_assoc(mysql_query(" Select * from mantis_project_table where id =".$_GET["project"]));
            }

            if (isset($_GET["m"])) {
                // Top menu
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

            if(isset($_GET["project"])&&  is_numeric($_GET["project"]))
            {
                ?>
                <a class="l" href="build.php<?php echo '?project='.$_GET["project"] ?>">Generar</a>
                <?php
            }
            ?>

            <div class="clear"> </div>
        </div>
    </div>
