
$(document).ready(function() {
    $("#saveedit").on("click", SaveValuesInEditForm);
});

var new_category_form_visible = false;
var edit_category_form_visible = false;

function cambioMetodo(id)
{
    window.location="cambio_metodo.php?id="+id;
}

function showEditCategoryForm(nombre,descripcion,id)
{
    if(!edit_category_form_visible)
    {
        edit_category_form_visible = true;

        var html = '';
        html += '<div id="ec">'
            html += '<label> Nombre </label>';
        html += '<input type="text" name="nombre_clasificacion" id="nombre_clasificacion_edit" value="'+nombre+'">';
        html += '<label> Descripcion </label>';
        html += '<textarea name="descripcion_clasificacion" id="descripcion_clasificacion_edit">'+descripcion+'</textarea>';

        html += '<input type="hidden" name="id_proyecto" value="1">';
        html += '<input type="hidden" name="id_clasificacion" value='+id+'>';
        html += '<input type="button" onClick="validarCamposCategoriaEditar()" value="Enviar">';
        html += '<a onClick="hideEditCategoryForm()">Hide</a>';
        html += '</div>';

        $("#editar_categoria").append(html);
    }
}

function validarCamposCategoriaEditar()
{
    if($.trim($("#nombre_clasificacion_edit").val())=="")
    {
        alert("Falta el nombre de la clasificacion");
        return;
    }
    if($.trim($("#nombre_clasificacion_edit").val()).search(/['"]+/)>=0)
    {
        alert("El nombre de la clasificacion tiene comillas simples o dobles, use en su lugar `");
        return;
    }
    if($.trim($("#descripcion_clasificacion_edit").val()).search(/['"]+/)>=0)
    {
        alert("La descripcion de la clasificacion tiene comillas simples o dobles, use en su lugar `");
        return;
    }
    $("#editar_categoria").submit();
}

function hideEditCategoryForm()
{
    if(edit_category_form_visible)
    {
        edit_category_form_visible = false;

        $("#ec").remove();
    }
}

function showNewCategoryForm()
{
    if(!new_category_form_visible)
    {

        new_category_form_visible = true;

        var html = '';
        html += '<div id="nc">'
            html += '<label> Nombre </label>';
        html += '<input type="text" name="nombre_clasificacion" id="nombre_clasificacion">';
        html += '<label> Descripcion </label>';
        html += '<textarea name="descripcion_clasificacion" id="descripcion_clasificacion"></textarea>';
        html += '<input type="hidden" name="id_proyecto" value="1">';
        html += '<input type="button" onClick="validarCamposCategoria()" value="Enviar">';
        html += '<a onClick="hideNewCategoryForm()">Hide</a>';
        html += '</div>';

        $("#nueva_categoria").append(html);

    }
}

function validarCamposCategoria()
{
    if($.trim($("#nombre_clasificacion").val())=="")
    {
        alert("Falta el nombre de la clasificacion");
        return;
    }
    if($.trim($("#nombre_clasificacion").val()).search(/['"]+/)>=0)
    {
        alert("El nombre de la clasificacion tiene comillas simples o dobles, use en su lugar `");
        return;
    }
    if($.trim($("#descripcion_clasificacion").val()).search(/['"]+/)>=0)
    {
        alert("La descripcion de la clasificacion tiene comillas simples o dobles, use en su lugar `");
        return;
    }
    $("#nueva_categoria").submit();
}

function hideNewCategoryForm()
{
    if(new_category_form_visible)
    {
        new_category_form_visible = false;

        $("#nc").remove();
    }
}

function Borrar(id)
{
    var selection = confirm("Esta seguro de querer borrar el método con todos sus argumentos y repsuestas?");

    //if(selection)
        // TODO: php here
    //   window.location="delete_method.php?m="+id<?php if(isset($_GET["cat"])) echo '+"&cat='.$_GET["cat"].'"'; if(isset($_GET["project"])) echo '+"&project='.$_GET["project"].'"'?>;
}

function Borrar_categoria()
{
    var selection = confirm("Esta seguro de querer borrar la categoria con todos su metodos?");

    //if(selection)
        // TODO: php here
    //   window.location="delete_cat.php?"<?php if(isset($_GET["cat"])) echo '+"&cat='.$_GET["cat"].'"'; if(isset($_GET["project"])) echo '+"&project='.$_GET["project"].'"'?>;
}


function eliminarArg(id)
{
    $("#argumento_"+id).remove();
}

function eliminarResp(id)
{
    $("#respuesta_"+id).remove();
}

function validarCampos()
{
    if($.trim($("#n_metodo").val())=="")
    {
        alert("Falta el nombre del metodo");
        return;
    }
    else if($.trim($("#n_metodo").val()).search(/['"]+/)>=0)
    {
        alert("El nombre contiene comillas simples o dobles, use en su lugar ` ");
        return;
    }
    if($.trim($("#sub_metodo").val())=="")
    {
        alert("Falta el subtitulo");
        return;
    }
    else if($.trim($("#sub_metodo").val()).search(/['"]+/)>=0)
    {
        alert("El subtitulo contiene comillas simples o dobles, use en su lugar ` ");
        return;
    }
    if($.trim($("#desc_metodo").val())=="")
    {
        alert("Falta la descripcion del metodo");
        return;
    }
    else if($.trim($("#desc_metodo").val()).search(/['"]+/)>=0)
    {
        alert("La descripcion contiene comillas simples o dobles, use en su lugar ` ");
        return;
    }
    if($.trim($("#auth_grupo_metodo").val())=="")
    {
        alert("Falta el grupo del metodo");
        return;
    }
    else if($.trim($("#auth_grupo_metodo").val()).search(/\D+/)>=0)
    {
        alert("El grupo solo puede ser un numero");
        return;
    }
    for(var i = 0; i<= param_count; i++)
    {
        if($("#argumento_"+i).length>0)
        {
            if($.trim($("#args_nombre_"+i).val())=="")
            {
                alert("Falta el nombre del argumento "+(i+1));
                return;
            }
            else if($.trim($("#args_nombre_"+i).val()).search(/['"]+/)>=0)
            {
                alert("El nombre del argumento "+ (i+1) +" contiene comillas simples o dobles, use en su lugar ` ");
                return;
            }
            if($.trim($("#args_desc_"+i).val()).search(/'+/)>=0)
            {
                alert("La descripcion del argumento "+ (i+1) +" contiene comillas simples, use en su lugar ` ");
                return;
            }
            if($.trim($("#args_default_"+i).val()).search(/'+/)>=0)
            {
                alert("El valor default del argumento "+ (i+1) +" contiene comillas simples, use en su lugar ` ");
                return;
            }
        }
    }
    for(var i = 0; i<= response_count; i++)
    {
        if($("#respuesta_"+i).length>0)
        {
            if($.trim($("#response_nombre_"+i).val())=="")
            {
                alert("Falta el nombre de la respuesta "+(i+1));
                return;
            }
            else if($.trim($("#response_nombre_"+i).val()).search(/['"]+/)>=0)
            {
                alert("El nombre de la respuesta "+ (i+1) +" contiene comillas simples o dobles, use en su lugar ` ");
                return;
            }
            if($.trim($("#response_desc_"+i).val()).search(/'+/)>=0)
            {
                alert("La descripcion de la respuesta "+ (i+1) +" contiene comillas simples, use en su lugar ` ");
                return;
            }
        }
    }
    if($.trim($("#ejemplo_peticion").val()).search(/'+/)>=0)
    {
        alert("El ejemplo de la peticion contiene comillas simples, use en su lugar ` ");
        return;
    }
    if($.trim($("#ejemplo_respuesta").val()).search(/'+/)>=0)
    {
        alert("El ejemplo de la respuesta contiene comillas simples, use en su lugar ` ");
        return;
    }
    $("#form_insercion").submit()
}

var param_count = -1;

function addParam()
{
    param_count ++;
    document.getElementById("numero_argumentos").value=param_count+1;
    var html = '';

    html +=     '<tr valign="top" style="border-bottom:1px solid #ddd;" id="argumento_'+param_count+'">';
    html +=     '<td class="bordered">';
    html +=         '<input type="text" name="nombre_argumento_'+param_count+'" placeholder="nombre" id="args_nombre_'+param_count+'" >';
    html +=     '</td>';

    html +=     '<td class="bordered">';
    html +=         '<select name="tipo_argumento_'+param_count+'" id="args_tipo_'+param_count+'" onChange="m.render()">';
    html +=             '<option value="string">string</option>';
    html +=             '<option value="bool">bool</option>';
    html +=             '<option value="int">int</option>';
    html +=             '<option value="float">float</option>';
    html +=             '<option value="json">json</option>';
    html +=         '</select>';
    html +=     '</td>';

    html +=     '<td class="bordered">';
    html +=         '<select name="ahuevo_'+param_count+'" id="args_ahuevo_'+param_count+'" onChange="m.render()">';
    html +=             '<option value=1>Obligatorio</option>';
    html +=             '<option value=0>Opcional</option>';
    html +=         '</select>';
    html +=     '</td>'; 

    html +=     '<td class="bordered">';
    html +=         '<textarea name="descripcion_argumento_'+param_count+'""placeholder="descripcion" id="args_desc_'+param_count+'" rows=6 ></textarea>';
    html +=     '</td>';

    html +=     '<td class="bordered"><input name="default_'+param_count+'" id="args_default_'+param_count+'" value="null" ></td>';
    html +=     '<td ><a onClick="eliminarArg('+param_count+')">Quitar</a></td>';
    html +=     '</tr>' ;

    $("#param_space").append(html);

    m.render();
}

function addParamEdit(nombre,tipo,ahuevo,descripcion,Default)
{
    param_count ++;
    document.getElementById("numero_argumentos").value = param_count+1;
    var html = '';

    html +=     '<tr valign=top class="bordered" id="argumento_'+param_count+'">';
    html +=     '<td>';
    html +=         '<input type="text" name="nombre_argumento_'+param_count+'" value="'+nombre+'" id="args_nombre_'+param_count+'" >';
    html +=     '</td>';

    html +=     '<td>';
    html +=         '<select name="tipo_argumento_'+param_count+'" id="args_tipo_'+param_count+'" onChange="m.render()">';

    html += '         <option value="string">string</option>';
    html += '         <option value="bool">bool</option>';
    html += '         <option value="int">int</option>';
    html += '         <option value="float">float</option>';
    html += '         <option value="json">json</option>';

    html +=         '</select>'; 
    html +=     '</td>';
    html +=     '<td>';
    html +=         '<select name="ahuevo_'+param_count+'" id="args_ahuevo_'+param_count+'" onChange="m.render()">';
    if(ahuevo)
    {
        html +=             '<option value=1 selected>Obligatorio</option>';
        html +=             '<option value=0>Opcional</option>';
    }
    else
    {
        html +=             '<option value=1>Obligatorio</option>';
        html +=             '<option value=0 selected>Opcional</option>';
    }
    html +=         '</select>';
    html +=     '</td>'; 

    html +=     '<td>';
    html +=         '<textarea rows=6 name="descripcion_argumento_'+param_count+'""placeholder="descripcion" id="args_desc_'+param_count+'" >'+descripcion+'</textarea>';
    html +=     '</td>';

    html +=     '<td><input name="default_'+param_count+'" id="args_default_'+param_count+'" value="'+Default+'" ></td>';
    html +=     '<td ><a onClick="eliminarArg('+param_count+')">Quitar</a></td>';
    html +=     '</tr>' ;

    $("#param_space").append(html);
    $("#args_tipo_"+param_count).val(tipo);

}

var response_count = -1;
function addResponse()
{
    response_count++;
    document.getElementById("numero_respuestas").value=response_count+1;
    var html = '';

    html +=     '<tr valign=top class="bordered" id="respuesta_'+response_count+'">';
    html +=     '<td>';
    html +=         '<input type="text" name="nombre_respuesta_'+response_count+'" placeholder="nombre" id="response_nombre_'+response_count+'" >';
    html +=     '</td>';

    html +=     '<td>';
    html +=         '<select name="tipo_respuesta_'+response_count+'" id="response_tipo_'+response_count+'" onChange="m.render()">';
    html +=             '<option value="string">string</option>';
    html +=             '<option value="bool">bool</option>';
    html +=             '<option value="int">int</option>';
    html +=             '<option value="float">float</option>';
    html +=             '<option value="json">json</option>';
    html +=         '</select>';
    html +=     '</td>';

    html +=     '<td>';
    html +=         '<textarea name="descripcion_respuesta_'+response_count+'" placeholder="descripcion" id="response_desc_'+response_count+'" ></textarea>';
    html +=     '</td>';

    html +=     '<td ><a onClick="eliminarResp('+response_count+')">Quitar</a></td>';

    html +=     '</tr>' ;

    $("#response_space").append(html);
    m.render();
}

function SetValuesInEditForm(methodDetails)
{
    let arguments = methodDetails['argumentos'];
    let responses = methodDetails['respuestas'];

    for (let arg of arguments)
    {
        addParamEdit(arg.nombre, arg.tipo, arg.ahuevo == 1, arg.descripcion, arg.defaults);
    }

    for (let resp of responses)
    {
        addResponseEdit(resp.nombre, resp.tipo, resp.descripcion);
    }

    $("#nombre_metodo").val(methodDetails['nombre']);
    $("#subtitulo").val(methodDetails['subtitulo']);
    $("#descripcion_metodo").val(methodDetails['descripcion']);
    $("#tipo_metodo").val(methodDetails['tipo']);
    $("#regresa_html").val(methodDetails['regresa_html']);
    $("#sesion_valida").val(methodDetails['sesion_valida']);
    $("#grupo").val(methodDetails['grupo']);
    $("#ejemplo_peticion").val(methodDetails['ejemplo_peticion']);
    $("#ejemplo_respuesta").val(methodDetails['ejemplo_respuesta']);
    $("#id_metodo").val(methodDetails['id_metodo']);
}

function SaveValuesInEditForm()
{
    let values = $("#form_insercion").serialize();
    $.ajax({
            type: "POST",
            url: "api/api.php?api=EditMethod",
            data: values,
        }).done(function(){
            window.location = "editmethod.php?m=" + $("#id_metodo").val();
        });
}

function addResponseEdit(nombre, tipo, descripcion)
{
    response_count++;
    document.getElementById("numero_respuestas").value=response_count+1;

    var html = '';
    html += '<tr valign="top" class="bordered" id="respuesta_'+response_count+'">';
    html += '   <td>';
    html += '      <input type="text" name="nombre_respuesta_'+response_count+'" value="'+nombre+'" id="response_nombre_'+response_count+'" >';
    html += '   </td>';

    html += '   <td>';
    html += '      <select name="tipo_respuesta_'+response_count+'" id="response_tipo_'+response_count+'">';
    html += '         <option value="string">string</option>';
    html += '         <option value="bool">bool</option>';
    html += '         <option value="int">int</option>';
    html += '         <option value="float">float</option>';
    html += '         <option value="json">json</option>';
    html += '      </select>';
    html += '   </td>';

    // TODO seleccionar el tipo apropiado
    // mismo que en linea 307

    html += '   <td>';
    html += '      <textarea name="descripcion_respuesta_'+response_count+'" placeholder="Descripcion" id="response_desc_'+response_count+'" >'+descripcion+'</textarea>';
    html += '   </td>';

    html += '   <td ><a onClick="eliminarResp('+response_count+')">Quitar</a></td>';

    html += '</tr>' ;
    $("#response_space").append(html);
    $("#response_tipo_"+response_count).val(tipo);
}

