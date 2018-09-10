<?php require_once("inc/top.inc.php"); ?>

    <p><a href="download.php?what=api/full_api&out_name=full_api">Descargar Todo</a></p>

    <?php
        require_once( "inc/write_api.inc.php" );
        require_once( "inc/write_cs_api.inc.php" );

        Zip("tmp", "tmp/full_api.zip");
        //Zip(GenerateCsharpApi::$tmpPath . "/server/", "tmp/builds/api/full_api.zip");
        //Zip('tmp/out/server/', 'tmp/builds/api/full_api.zip');
    ?>


<?php require_once("inc/bottom.inc.php"); ?>
