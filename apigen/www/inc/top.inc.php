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
        <div id="FB_HiddenContainer" style="position:absolute; top:-10000px; width:0px; height:0px;"></div>

        <div class="devsitePage">

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
                    echo '<a class="l" href="editmethod.php?m='. $_GET["m"] .'&cat='.$_GET["cat"].'&project='.$_GET["project"].'">Editar</a>';
                    echo '<a class="l" onClick="Borrar('. $_GET["m"] .')">Borrar</a>';

                }

                if(isset($_GET["project"]) &&  is_numeric($_GET["project"]))
                {
                    echo '<a class="l" href="newmethod.php?project='.$_GET["project"];
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

        <div class="body nav">
            <div class="content">

            <?php require_once("inc/toplevnav.inc.php"); ?>

            <div id="bodyText" class="bodyText">

            <?php require_once("inc/titleheader.inc.php"); ?>

