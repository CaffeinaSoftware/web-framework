<form id="form_insercion" method="POST" >
    <table border=0 width="100%">
        <tr>
            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">General</h3></td>
        </tr>
        <tr>
            <td>Clasificacion</td>
            <td>
            <select name="clasificacion_metodo"  >
                <?php
                    // Read all project categories and pre-select
                    if(isset($_GET["cat"])) $cat=$_GET["cat"];
                    else $cat=-1;

                    $sql="select * from clasificacion where id_proyecto=1";
                    $result = mysql_query($sql);
                    while($row = mysql_fetch_assoc($result))
                    {
                        if($cat==$row["id_clasificacion"])
                            echo ' <option value="'.$row["id_clasificacion"].'" selected>'.$row["nombre"].'</option> ';
                        else
                            echo ' <option value="'.$row["id_clasificacion"].'">'.$row["nombre"].'</option> ';
                    }
                ?>
            </select>
            </td>
        </tr>
        <tr>
            <td>Nombre</td>
            <td>
                <input type="text" name="nombre_metodo" id="nombre_metodo"  onKeyUp="m.nombre = this.value; m.render()">
            </td>
        </tr>
        <tr>
            <td>Subtitulo</td>
            <td>
                <input type="text" name="subtitulo" id="subtitulo"  onKeyUp="m.subtitulo = this.value; m.render()">
            </td>
        </tr>
        <tr>
            <td>Descripcion</td>
            <td>
                <textarea
                    rows=12
                    name="descripcion_metodo"
                    id="descripcion_metodo"
                    onKeyUp="m.desc = this.value; m.render()"></textarea>
            </td>
        </tr>
        <tr>
            <td>Method</td>
            <td>
            <select name="tipo_metodo" onChange="m.http = this.value; m.render()">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="POST/GET">POST/GET</option>
            </select>
            </td>
        </tr>
        <tr>
            <td>Regresa HTML</td>
            <td><input type="checkbox" name="regresa_html" value="false" onChange="m.html = !m.html; m.render()"></td>
        </tr>
        <tr>
            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Autenticacion</h3></td>
        </tr>
        <tr>
        <td >Sesion Valida</td>
        <td ><input type="checkbox" name="sesion_valida" value="true" checked onChange="m.auth.sesion = !m.auth.sesion; m.render()"> </td>
        </tr>
        <tr><td >Grupo</td>
        <td ><input type="text" name="grupo" id="grupo" onKeyUp="m.auth.grupo = this.value; m.render()"></td>
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

            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Respuesta <a onClick='addResponse()' style="color: white;" >[+]</a></h3></td>
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
                name="ejemplo_peticion"
                id="ejemplo_peticion"
                onKeyUp="m.entrada = this.value; m.render()"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Respuesta</h3></td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea
                 rows=12
                 name="ejemplo_respuesta" 
                 id="ejemplo_respuesta" 
                 onKeyUp="m.salida = this.value; m.render()"></textarea></td>
        </tr>

            <!-- "render_preview" -->
            <table cellpadding="0" cellspacing="0" class="c0">
            <tbody>
            <tr class="c1">
                <td class="c25 c41">
                    <p class="c3">
                        <span class="c19" id="preview_nombre"></span>
                    </p>
                    <p class="c3">
                        <span class="c31" id="preview_subtitle"></span>
                    </p>
                </td>
            </tr>
            </tbody>
            </table>
            <!-- "render_preview" -->

    <p class="c22 c15" id="preview_desc"></p>

    <br>
    <h4 class="c15"><span>Regresa HTML</span></h4><span class="c7" id="preview_regresa_html">No</span>
    <h4 class="c15"><span>Autenticaci&oacute;n</span></h4>
    <table cellpadding="0" cellspacing="0" class="c0">
    <tbody>
    <tr>
        <td class="c9">
            <p class="c3">
                <span class="c6">Sesion valida</span>
            </p>
        </td>
        <td class="c16">
            <p class="c3">
                <span class="c7" id="preview_auth_sesion">Si</span>
            </p>
        </td>
    </tr>
    <tr class="c169">
        <td class="c9">
            <p class="c3">
                <span class="c6">Grupo</span>
            </p>
        </td>
        <td class="c16">
            <p class="c3">
                <span class="c7" id="preview_auth_grupo"></span>
            </p>
        </td>
    </tr>
    <tr class="c1">
        <td class="c9">
            <p class="c3">
                <span class="c6">Permiso</span>
            </p>
        </td>
        <td class="c16">
            <p class="c22 c15">
                <span class="c8 c7" id="preview_auth_permiso"></span>
            </p>
        </td>
    </tr>
    </tbody>
    </table>
    <h4 class="c15"><a name="h.fmkw97a0ehmh"></a><span>Argumentos</span></h4>


     <table cellpadding="0" cellspacing="0" class="c0">
        <tbody id="preview_arg_table">
    </table>

    <input type="button" value="Insertar" onClick="validarCampos();">
    <input type="button" value="Cancelar" onClick="history.go(-1)">
    <input type="hidden" name="numero_argumentos" id="numero_argumentos" value=0>
    <input type="hidden" name="numero_respuestas" id="numero_respuestas" value=0>
    <input type="hidden" name="id_metodo" id="id_metodo" value=<?php echo $mid;?>>
</form>
