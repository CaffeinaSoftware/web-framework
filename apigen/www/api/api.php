<?php

require_once(__DIR__ . "/../../server/bootstrap.php");

class ApiGenApi
{
    static function RemoveCategory($category_id)
    {
        // Deleting a category
        $sql = "select id_metodo from metodo where id_clasificacion = " . $category_id;
        $res = mysql_query($sql);
        while($row = mysql_fetch_array($res))
        {
            // Delete method
            ApiGenApi::DeleteMethod($row[0]);
        }

        $sql = "delete from clasificacion where id_clasificacion = " . $category_id;
        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }
    }

    static function CreateCategory($id_proyecto, $nombre_clasificacion, $descripcion_clasificacion)
    {
        // Create a category
        $id_clasificacion = null;
        $sql = "Insert into clasificacion(id_proyecto,nombre,descripcion) values "
                . "(".$id_proyecto.",'".$nombre_clasificacion."','".$descripcion_clasificacion."')";

        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }

        $sql="select LAST_INSERT_ID()";
        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }

        //$id_clasificacion= mysql_fetch_row($Consulta_ID);
    }

    static function EditCategory($nombre_clasificacion, $descripcion_clasificacion, $id_clasificacion)
    {
        $sql = "update clasificacion set nombre='".$nombre_clasificacion."',descripcion='".$descripcion_clasificacion."' where id_clasificacion=".$id_clasificacion;
        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }
    }

    static function MethodDetails($method_id)
    {
        $info_metodo = "select * from metodo where id_metodo=".$method_id;
        $r = mysql_query($info_metodo) or die(mysql_error());
        $info_metodo = mysql_fetch_assoc($r) or die(mysql_error());

        $query_argumentos = "select * from argumento where id_metodo=".$method_id;
        $r = mysql_query($query_argumentos) or die(mysql_error());
        $argumentos = [];
        while($row=mysql_fetch_assoc($r))
        {
           $argumentos[] = $row;
        }

        $query_respuestas = "select * from respuesta where id_metodo=".$method_id;
        $r=mysql_query($query_respuestas) or die(mysql_error());
        $respuestas = [];
        while($row=mysql_fetch_assoc($r))
        {
           $respuestas[] = $row;
        }

        $info_metodo["argumentos"] = $argumentos;
        $info_metodo["respuestas"] = $respuestas;

        return $info_metodo;
    }

    static function CreateMethod($clasificacion_metodo, $sesion_valida, $regresa_html,
        $nombre_metodo , $tipo_metodo , $combo , $grupo , $ejemplo_peticion , $ejemplo_respuesta , $descripcion_metodo, $subtitulo
        )
    {
        if(!isset ($clasificacion_metodo) || !is_numeric($clasificacion_metodo))
        {
            throw new Exception("No se obtuvo clasificacion de metodo");
        }

        // Defaults
        $combo = isset($sesion_valida);
        if(!$combo) $combo = 0;

        $regresa_html = isset($regresa_html);
        if(!$regresa_html) $regresa_html=0;

        $sql = "insert into metodo (id_clasificacion, nombre, tipo, sesion_valida, grupo, ejemplo_peticion, ejemplo_respuesta, descripcion, subtitulo, regresa_html) "
                . " values ({0}, '{1}', '{2}', {3}, {4}, '{5}', '{6}', '{7}', '{8}', {9})";

        str_replace("{0}", $clasificacion_metodo, $sql);
        str_replace("{1}", $nombre_metodo, $sql);
        str_replace("{2}", $tipo_metodo, $sql);
        str_replace("{3}", $combo, $sql);
        str_replace("{4}", $grupo, $sql);
        str_replace("{5}", $ejemplo_peticion, $sql);
        str_replace("{6}", $ejemplo_respuesta, $sql);
        str_replace("{7}", preg_replace('/\'/','`', $descripcion_metodo), $sql);
        str_replace("{8}", $subtitulo, $sql);
        str_replace("{9}", $regresa_html, $sql);

        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }

        $sql="Select LAST_INSERT_ID()";
        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }

        $id_metodo= mysql_fetch_row($Consulta_ID);
        for($i = 0; $i < $_POST["numero_argumentos"]; $i++)
        {
            if(!isset($_POST["nombre_argumento_".$i]))
            {
                continue;
            }

            $sql = "Insert into argumento(id_metodo,nombre,descripcion,ahuevo,tipo,defaults) ".
                "values({0},'{1}','{2}','{3}','{4}','{5}')";

            str_replace("{0}", $id_metodo[0], $sql);
            str_replace("{1}", $arguments[$i]['nombre_argumento'], $sql);
            str_replace("{2}", $arguments[$i]['descripcion_argumento'], $sql);
            str_replace("{3}", $arguments[$i]['obligatorio'], $sql);
            str_replace("{4}", $arguments[$i]['tipo'], $sql);
            str_replace("{5}", $arguments[$i]['valor_default'], $sql);

            if (!mysql_query($sql)) {
                throw new Exception(mysql_error());
            }
        }

        for($i = 0; $i < $_POST["numero_respuestas"]; $i++)
        {
            if(!isset($_POST["nombre_respuesta_".$i]))
            {
                continue;
            }

            $sql="Insert into respuesta(id_metodo,nombre,descripcion,tipo) values(".$id_metodo[0].",'".$_POST["nombre_respuesta_".$i]."','".$_POST["descripcion_respuesta_".$i]."','".$_POST["tipo_respuesta_".$i]."')";
            if (!mysql_query($sql)) {
                throw new Exception(mysql_error());
            }
        }
        /*
            //$location = "Location: index.php?mensaje=".$mensaje."&project=".$_POST["id_proyecto"];
            //if (isset($id_metodo) &&$ id_metodo!=-1)
            //{
            //    $location .= "&m=".$id_metodo[0];
            //}
            //
            //if(isset($_POST["clasificacion_metodo"]) && is_numeric($_POST["clasificacion_metodo"]))
            //{
            //    $location .= "&cat=".$_POST["clasificacion_metodo"];
            //}
            //
        */
    }

    static function EditMethod()
    {
var_dump($_POST);
        if(!isset ($_POST["id_metodo"]) || !is_numeric($_POST["id_metodo"]))
        {
            throw new Exception("No se envio id_metodo");
        }

        // Defaults
        $combo = isset($_POST["sesion_valida"]);
        if(!$combo) $combo=0;

        $regresa_html=isset($_POST["regresa_html"]);
        if(!$regresa_html) $regresa_html=0;

        $sql = "update metodo set "
            . "id_clasificacion={0}," 
            . "nombre='{1}',"
            . "tipo='{2}',"
            . "sesion_valida={3},"
            . "grupo={4},"
            . "ejemplo_peticion='{5}',"
            . "ejemplo_respuesta='{6}',"
            . "descripcion='{7}',"
            . "subtitulo='{8}',"
            . "regresa_html={9}"
            . " where id_metodo={10}";

        $sql = str_replace("{0}", $_POST["clasificacion_metodo"], $sql);
        $sql = str_replace("{1}", $_POST["nombre_metodo"], $sql);
        $sql = str_replace("{2}", $_POST["tipo_metodo"], $sql);
        $sql = str_replace("{3}", $combo, $sql);
        $sql = str_replace("{4}", $_POST["grupo"], $sql);
        $sql = str_replace("{5}", $_POST["ejemplo_peticion"], $sql);
        $sql = str_replace("{6}", $_POST["ejemplo_respuesta"], $sql);
        $sql = str_replace("{7}", preg_replace('/\'/','`', $_POST["descripcion_metodo"]), $sql);
        $sql = str_replace("{8}", $_POST["subtitulo"], $sql);
        $sql = str_replace("{9}", $regresa_html, $sql);
        $sql = str_replace("{10}", $_POST["id_metodo"], $sql);

        if (mysql_query($sql) === false)
        {
            throw new Exception(mysql_error());
        }

        $id_metodo = $_POST["id_metodo"];
        $id_metodo = $_POST["id_metodo"];
        $sql="delete from argumento where id_metodo=".$_POST["id_metodo"];
        if (!mysql_query($sql)){
            throw new Exception(mysql_error());
        }

        for($i = 0; $i < $_POST["numero_argumentos"]; $i++)
        {
            if(!isset($_POST["nombre_argumento_".$i]))
            {
                continue;
            }

            $sql="Insert into argumento(id_metodo,nombre,descripcion,ahuevo,tipo,defaults) "
                . "values(".$id_metodo.",'".$_POST["nombre_argumento_".$i]."','".$_POST["descripcion_argumento_".$i]."','".$_POST["ahuevo_".$i]."','".$_POST["tipo_argumento_".$i]."','".$_POST["default_".$i]."')";
            if (!mysql_query($sql))
            {
                throw new Exception(mysql_error());
            }
        }

        $sql="delete from respuesta where id_metodo=".$_POST["id_metodo"];
        if (!mysql_query($sql)){
            throw new Exception(mysql_error());
        }

        for($i = 0; $i < $_POST["numero_respuestas"]; $i++)
        {
            if(!isset($_POST["nombre_respuesta_".$i]))
            {
                continue;
            }

            $sql = "Insert into respuesta(id_metodo,nombre,descripcion,tipo) "
                . "values(".$id_metodo.",'".$_POST["nombre_respuesta_".$i]."','".$_POST["descripcion_respuesta_".$i]."','".$_POST["tipo_respuesta_".$i]."')";
            if (!mysql_query($sql))
            {
                throw new Exception(mysql_error());
            }
        }
    }

    static function DeleteMethod($id_metodo)
    {
        $sql="delete from argumento where id_metodo=" . $id_metodo;
        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }

        $sql="delete from respuesta where id_metodo=" . $id_metodo;
        $Consulta_ID = mysql_query($sql);
        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }

        $sql = "delete from metodo where id_metodo=" . $id_metodo;
        if (!mysql_query($sql)) {
            throw new Exception(mysql_error());
        }
    }
}

if (isset($_REQUEST["api"]))
{
    switch($_REQUEST["api"])
    {
        case "CreateCategory":
            ApiGenApi::CreateCategory();
        break;
        case "CreateMethod":
            ApiGenApi::CreateMethod();
        break;
        case "DeleteMethod":
            ApiGenApi::DeleteMethod();
        break;
        case "EditCategory":
            ApiGenApi::EditCategory();
        break;
        case "EditMethod":
            ApiGenApi::EditMethod();
        break;
        case "MethodDetails":
            ApiGenApi::MethodDetails();
        break;
        case "RemoveCategory":
            ApiGenApi::RemoveCategory();
        break;

    }
}
