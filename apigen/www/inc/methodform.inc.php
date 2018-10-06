<form id="form_insercion" method="POST" >
    <table border=0 width="100%">
        <tr>
            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">General</h3></td>
        </tr>
        <tr>
            <td>Clasificacion</td>
            <td>
            <select id="clasificacion_metodo" name="clasificacion_metodo"  >
                <?php
                    $sql="select * from clasificacion where id_proyecto=1 order by nombre";
                    $result = mysql_query($sql);
                    while($row = mysql_fetch_assoc($result))
                    {
                        echo ' <option value="'.$row["id_clasificacion"].'">'.$row["nombre"].'</option> ';
                    }
                ?>
            </select>
            </td>
        </tr>
        <tr>
            <td>Nombre</td>
            <td>
                <input type="text" name="nombre_metodo" id="nombre_metodo" >
            </td>
        </tr>
        <tr>
            <td>Subtitulo</td>
            <td>
                <input type="text" name="subtitulo" id="subtitulo"  >
            </td>
        </tr>
        <tr>
            <td>Descripcion</td>
            <td>
                <textarea rows=12 name="descripcion_metodo" id="descripcion_metodo" ></textarea>
            </td>
        </tr>
        <tr>
            <td>Method</td>
            <td>
            <select id="tipo_metodo" name="tipo_metodo">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="POST/GET">POST/GET</option>
            </select>
            </td>
        </tr>
        <tr>
            <td>Regresa HTML</td>
            <td><input type="checkbox" name="regresa_html" value="false" ></td>
        </tr>
        <tr>
            <td colspan="2" ><h3 style="color: white;">Autenticacion</h3></td>
        </tr>
        <tr>
        <td >Sesion Valida</td>
        <td ><input type="checkbox" name="sesion_valida" value="true" checked > </td>
        </tr>
        <tr><td >Grupo</td>
        <td ><input type="text" name="grupo" id="grupo" ></td>
        </tr>

        <tr><td >Permiso</td>
        <td ><input type="text" disabled ></td>
        </tr>


        <tr>

            <td colspan="2" style="background-color:#0B5394; padding: 5px;">
                <h3 style="color: white;">Argumentos 
                    <a onClick='addParam()' style="color: white;">[+]</a></h3></td>
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
                <textarea rows=12 name="ejemplo_peticion" id="ejemplo_peticion" ></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color:#0B5394; padding: 5px;"><h3 style="color: white;">Ejemplo Respuesta</h3></td>
        </tr>
        <tr>
            <td colspan="2">
                <textarea rows=12 name="ejemplo_respuesta" id="ejemplo_respuesta" ></textarea></td>
        </tr>

        <input type="hidden" name="numero_argumentos" id="numero_argumentos" value=0>
        <input type="hidden" name="numero_respuestas" id="numero_respuestas" value=0>
        <input type="hidden" name="id_metodo" id="id_metodo" >
    </table>
</form>
