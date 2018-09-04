<?php require_once("inc/top.inc.php"); ?>

<?php require_once("inc/editmethod.inc.php"); ?>

<?php require_once("inc/bottom.inc.php"); ?>
<!--
    <script type="text/javascript">
        var m = new ApiMethod();
        m.nombre = document.getElementById('n_metodo').value;
        m.subtitulo = document.getElementById('sub_metodo').value;
        m.desc = document.getElementById('desc_metodo').value;
        m.http = document.getElementById('http_metodo').value;
        m.auth.session = document.getElementById('auth_session_metodo').value;
        m.auth.grupo = document.getElementById('auth_grupo_metodo').value;
        m.entrada = document.getElementById('entrada_metodo').value;
        m.salida = document.getElementById('salida_metodo').value;
        m.html = document.getElementById('html_metodo').value;

    <?php
        if ($argumentos!=-1)
        {
            for ($i=0;$i<count($argumentos);$i++)
            {
                echo "addParamEdit('"
                . $argumentos[$i]["nombre"]
                . "','"
                .$argumentos[$i]["tipo"]
                ."',"
                .htmlspecialchars(str_replace("\n","<br>",$argumentos[$i]["ahuevo"]))
                .",'"
                . preg_replace('/[^(\x20-\x7F)]*/','', $argumentos[$i]["descripcion"] )
                ."','"
                .$argumentos[$i]["defaults"]."');\n";
            }
        }

        if ($respuestas!=-1)
        {
            for ($i=0;$i<count($respuestas);$i++)
            {
                echo "addResponseEdit('".$respuestas[$i]["nombre"]."','".$respuestas[$i]["tipo"]."',\"". htmlentities(preg_replace('/[^(\x20-\x7F)]*/','',str_replace("\t","",str_replace("\n","\n",$respuestas[$i]["descripcion"]))))."\");\n";
            }
        }
    ?>
        //m.render();
    </script>
-->
