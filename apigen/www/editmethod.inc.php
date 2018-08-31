<?php $mid = $_GET["m"]; ?>
<?php
   if($mid == -1) return;

    $info_metodo="select * from metodo where id_metodo=".$mid;
    $r=mysql_query($info_metodo) or die(mysql_error());
    $info_metodo=mysql_fetch_assoc($r) or die(mysql_error());

    $query_argumentos="select * from argumento where id_metodo=".$mid;
    $r=mysql_query($query_argumentos) or die(mysql_error());
    $i=0;
    $argumentos=-1;
    while($row=mysql_fetch_assoc($r))
    {
        if($argumentos==-1)
           unset($argumentos);
       $argumentos[$i]=$row;
       $i++;
    }
    $respuestas=-1;
    $query_respuestas="select * from respuesta where id_metodo=".$mid;
    $r=mysql_query($query_respuestas) or die(mysql_error());
    $i=0;
    while($row=mysql_fetch_assoc($r))
    {
       if($respuestas==-1)
           unset($respuestas);
       $respuestas[$i]=$row;
       $i++;
    }

    if(isset($_GET["mensaje"])) echo $_GET["mensaje"];
    ?>
<form id="form_insercion" method="POST" action="negocios_editar.php">
    <table border=0>
        <tr >
            <td valign=top>
                <!-- --------------------------------------------------------------------
                        EDITOR
                     -------------------------------------------------------------------- -->
                     <table border=0 width=100%>
                        <tr>
                            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">General</h3></td>
                        </tr>
                        <tr>
                            <td>Clasificacion</td>
                            <td>
                                <select name="clasificacion_metodo" style="width:100%" >
                                    <?php
                                        $sql="select * from clasificacion where id_proyecto = ".$_GET["project"];
                                        $result=mysql_query($sql);
                                        while($row=mysql_fetch_assoc($result))
                                        {
                                            if($row["id_clasificacion"]==(int)$info_metodo["id_clasificacion"])
                                                echo
                                            '
                                                <option value="'.$row["id_clasificacion"].'" selected>'.$row["nombre"].'</option>
                                            ';
                                            else
                                            echo
                                            '
                                                <option value="'.$row["id_clasificacion"].'">'.$row["nombre"].'</option>
                                            ';
                                        }

                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Nombre</td>
                            <td>
                                <input type="text" name="nombre_metodo" id="n_metodo" style="width:100%" value="<?php echo $info_metodo["nombre"];?>" onKeyUp="m.nombre = this.value; m.render()">
                            </td>
                        </tr>
                        <tr>
                            <td>Subtitulo</td>
                            <td>
                                <input type="text" name="subtitulo" id="sub_metodo" style="width:100%" value="<?php echo $info_metodo["subtitulo"];?>" onKeyUp="m.subtitulo = this.value; m.render()">
                            </td>
                        </tr>
                        <tr>
                            <td>Descripcion</td>
                            <td>
                                <textarea
                                    rows=12
                                    name="descripcion_metodo"
                                    id="desc_metodo"
                                    style="width:100%"
                                    onKeyUp="m.desc = this.value; m.render()"><?php
                                        echo str_replace("<br>","\n", $info_metodo["descripcion"])
                                    ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Method</td>
                            <td>
                            <select name="tipo_metodo" id="http_metodo" onChange="m.http = this.value; m.render()">
                            <?php
                                if($info_metodo["tipo"]=="GET")
                                echo '<option value="GET" selected>GET</option> ';
                                else
                                echo '<option value="GET">GET</option> ';
                                if($info_metodo["tipo"]=="POST")
                                echo '<option value="POST" selected>POST</option> ';
                                else
                                echo '<option value="POST">POST</option> ';
                                if($info_metodo["tipo"]=="POST/GET")
                                echo '<option value="POST/GET" selected>POST/GET</option> ';
                                else
                                echo '<option value="POST/GET">POST/GET</option> ';
                            ?>
                            </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Regresa HTML</td>
                            <td><input type="checkbox" name="regresa_html" value="false" <?php if($info_metodo["regresa_html"]) echo "checked"?> id="html_metodo" onChange="m.html = !m.html; m.render()"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Autenticacion</h3></td>
                        </tr>

                        <tr><td >Sesion Valida</td>
                        <td ><input type="checkbox" name="sesion_valida" value="true" <?php if($info_metodo["sesion_valida"]) echo "checked"?> id="auth_session_metodo" onChange="m.auth.sesion = !m.auth.sesion; m.render()"> </td>
                        </tr>

                        <tr><td >Grupo</td>
                        <td ><input type="text" name="grupo" value="<?php echo $info_metodo["grupo"];?>" id="auth_grupo_metodo" onKeyUp="m.auth.grupo = this.value; m.render()"></td>
                        </tr>

                        <tr><td >Permiso</td>
                        <td ><input type="text" disabled ></td>
                        </tr>


                        <tr>

                            <td colspan="2" style="background-color:#0B5394; padding: 5px;">
                                <h3 style="color: white;">Argumentos <a onClick='addParam()' style="color: white;">[+]</a></h3></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table id="param_space">
                                </table>
                            </td>
                        </tr>
                        <tr>

                            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Respuesta <a onClick='addResponse()' style="color: white;">[+]</a></h3></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table id="response_space">
                                </table>
                            </td>
                        </tr>
                        <tr>

                            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Peticion</h3></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea
                                rows=12
                                style="width:100%;<?php

                                    try{
                                        $a = json_decode($info_metodo["ejemplo_peticion"]);
                                    }catch(Exception $e){
                                        echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";
                                    }

                                    if(is_null($a)){
                                        echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";
                                    }

                                ?>"
                                name="ejemplo_peticion"
                                id="entrada_metodo"
                                onKeyUp="m.entrada = this.value; m.render();"><?php echo $info_metodo["ejemplo_peticion"];?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Respuesta</h3></td>
                        </tr>
                        <tr>

                            <td colspan="2">
                                <textarea
                                 rows=12
                                 style="width:100%; <?php

                                    try{
                                        $a = json_decode($info_metodo["ejemplo_respuesta"]);
                                    }catch(Exception $e){
                                        echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";
                                    }

                                    if(is_null($a)){
                                        echo  "box-shadow: 0 0 5px 2px #f00;  -webkit-box-shadow: 0 0 5px 2px #f00;  -moz-box-shadow: 0 0 5px 2px #f00;";
                                    }

                                ?>"
                                 name="ejemplo_respuesta"
                                 id="salida_metodo"
                                 onKeyUp="m.salida = this.value; m.render()"><?php echo $info_metodo["ejemplo_respuesta"];?></textarea></td>
                        </tr>


                     </table>

            </td>

        </tr>

    </table>
    <input type="button" value="Editar" onClick="validarCampos();">
        <input type="button" value="Cancelar" onClick="history.go(-1)">
    <input type="hidden" name="numero_argumentos" id="numero_argumentos" value=0>
    <input type="hidden" name="numero_respuestas" id="numero_respuestas" value=0>
    <input type="hidden" name="id_metodo" id="id_metodo" value=<?php echo $mid;?>>
        <input type="hidden" name="id_proyecto" id="id_proyecto" value="<?php echo $_GET["project"]?>">
</form>
    <script type="text/javascript">
        var a = new ApiMethod();
        a.nombre = "api/hola/say_hola";
        a.metodo = "GET";
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
        if($argumentos!=-1)
        for($i=0;$i<count($argumentos);$i++)
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
        if($respuestas!=-1)
        for($i=0;$i<count($respuestas);$i++)
        {
            echo "addResponseEdit('".$respuestas[$i]["nombre"]."','".$respuestas[$i]["tipo"]."',\"". htmlentities(preg_replace('/[^(\x20-\x7F)]*/','',str_replace("\t","",str_replace("\n","\n",$respuestas[$i]["descripcion"]))))."\");\n";
        }
    ?>
        m.render();
        //a.render();
    </script>
</body>
</html>
