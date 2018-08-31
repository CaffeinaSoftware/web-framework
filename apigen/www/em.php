<?php require_once("top.inc.php"); ?>

<div class="devsitePage">

    <?php require_once("menu.inc.php"); ?>

    <div class="body nav">
        <div class="content">
            <div id="bodyMenu" class="bodyMenu">
            <?php require_once("toplevnav.inc.php"); ?>
            </div>
            <div id="bodyText" class="bodyText">
                <div class="header">
                    <div class="content">
                        <h1>Editar metodo</h1>

                        <div class="breadcrumbs">
                            <a href="apigen.php?project=<?php $_GET["project"] ?>">Regresar</a> 
                        </div>

                    </div>
                </div>

                <?php require_once( "editmethod.inc.php" ); ?>

                <hr/>

                <div class="mtm pvm uiBoxWhite topborder">
                    <div class="mbm">

                    </div>
                    <abbr title="Monday, September 5, 2011 at 8:28pm" data-date="Mon, 05 Sep 2011 18:28:49 -0700" class="timestamp">
                    Ultima modificacion 
                    <?php 
                        if(isset($_GET["m"]))
                        {
                            $registro = mysql_fetch_assoc(mysql_query("Select * from registro where id_metodo = ".$_GET["m"]." order by fecha desc"));
                            $time = strtotime($registro["fecha"]);
                            echo " ".date("l",$time).", ".date("F",$time)." ".date("j",$time).", ".date("Y",$time)." at ".date("H:i:s",$time).". <br> Por ".$registro["usuario"];
                        }
                    ?>
                    </abbr>
                </div>
            </div>

            <div class="clear"> </div>
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
</div>

</body>
</html>
