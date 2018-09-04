
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
        if (isset($_GET["cat"]) && !isset($_GET["m"])) {
             $res = mysql_query("select * from clasificacion where id_clasificacion = " . $_GET["cat"]) or die(mysql_error());
             $metodo = mysql_fetch_assoc($res);
             echo "<p>" . $metodo["descripcion"] . "</p>";
        }
        ?>
    </div>
</div>
