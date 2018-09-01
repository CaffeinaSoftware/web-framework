<?php require_once("inc/top.inc.php"); ?>

<div class="devsitePage">
    
    <?php require_once("inc/menu.inc.php"); ?>

    <div class="body nav">
        <div class="content">
             <?php require_once("inc/toplevnav.inc.php"); ?>

            </div>
            <div id="bodyText" class="bodyText">
                <div class="header">
                    <div class="content">

                        <h1>Generar codigo</h1>

                        <div class="breadcrumbs">
                            <a href="index.php?project=<?php echo $_GET["project"] ?>">Regresar</a>
                        </div>

                    </div>
                </div>

                <p>
                    <a href="dl.php?what=api/full_api&out_name=full_api">Descargar Todo</a>
                </p>

                    <?php require_once( "inc/write_api.inc.php" ); ?>
                    <?php require_once( "inc/write_cs_api.inc.php" ); ?>

                <hr/>

                <div class="mtm pvm uiBoxWhite topborder">
                    <div class="mbm"> </div>
                </div>

            </div>

            <div class="clear">
            </div>

        </div>
    </div>
    <div class="footer">
        <div class="content">
        </div>
    </div>
</div>

</body>
</html>
