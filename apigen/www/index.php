<?php require_once("inc/top.inc.php"); ?>

<?php
// If a looking at a category or method, expand the methods
if(!isset($_GET["m"]) && !isset($_GET["cat"])){
    $query = mysql_query("select * from clasificacion where id_proyecto = ". $_GET["project"]." order by nombre asc;");
    while( ($row = mysql_fetch_assoc( $query )) != null )
    {
        ?>
        <li class="active withsubsections">
            <a class="selected" href="index.php?cat=<?php echo $row["id_clasificacion"]; echo "&project=".$_GET["project"] ?> ">
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
?>
<p>
    <?php
    // Method description
    if(isset($_GET["m"])){
        $res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
        $metodo = mysql_fetch_assoc($res);
        echo $metodo["descripcion"] ;
    }
    ?>
</p>
<?php
    if (isset($_GET["m"]) && $metodo["regresa_html"])
    {
        echo "<h2>Regresa HTML </h2>";
    }

    if (isset($_GET["m"]))
    {
        $res = mysql_query("select * from metodo where id_metodo = " . $_GET["m"]) or die(mysql_error());
        $metodo = mysql_fetch_assoc($res);

        $argsq = mysql_query("select * from argumento where id_metodo = ". $metodo["id_metodo"] ." order by ahuevo desc,nombre asc;") or die(mysql_error());
        ?>
        <h2>Argumentos</h2>
        <table class="methods" style="margin-left:0; width:100%">
        <tr>
            <th class="bordered"> Nombre </th>
            <th class="bordered"> Tipo </th>
            <th class="bordered"> Default </th>
            <th class="bordered"> Descripcion </th>
        </tr>

        <?php
        while(($argumento = mysql_fetch_assoc($argsq)) != null)
        {
            ?>
            <tr>
            <td class="method bordered">
                <code><?php
                    if($argumento["ahuevo"]) echo "<b>";
                    echo $argumento["nombre"];
                    if($argumento["ahuevo"]) echo "</b>";
                ?></code>
            </td>
            <td class="desc bordered">
                <code><?php echo $argumento["tipo"]; ?></code>
            </td>
            <td class="args bordered">
                <?php //echo $argumento["ahuevo"];
                 echo $argumento["defaults"];
                ?>
            </td>
            <td class="args bordered">
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
            <th  class="bordered">
                Nombre
            </th>
            <th class="bordered">
                Tipo
            </th>
            <th class="bordered">
                Desc
            </th>
        </tr>

        <?php
        while(($respuesta = mysql_fetch_assoc($argsr)) != null)
        {
            ?>
            <tr>
            <td class="method bordered">
                <code><?php echo $respuesta["nombre"]; ?></code>
            </td>
            <td class="desc bordered">
                <code><?php echo $respuesta["tipo"]; ?></code>
            </td>
            <td class="args bordered">
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

    } else if(isset($_GET["cat"])) {

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

<?php require_once("inc/bottom.inc.php"); ?>

