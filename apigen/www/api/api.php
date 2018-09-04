<?php

require_once("../server/bootstrap.php");

class ApiGenApi
{
    static function RemoveCategory()
    {

// Deleting a category
ob_start();
require_once("../server/bootstrap.php");

$sql = "Select nombre from clasificacion where id_clasificacion = ".$_GET["cat"];

$row = mysql_fetch_array(mysql_query($sql));

$nombre_clasificacion = $row[0];

$sql = "Select id_metodo from metodo where id_clasificacion = ".$_GET["cat"];

$res = mysql_query($sql);

while($row = mysql_fetch_array($res))
{
    var_dump($row);
    $sql="delete from argumento where id_metodo=".$row[0];
    $Consulta_ID = mysql_query($sql);
    if (!$Consulta_ID){
        $mensaje= $sql."<br>";
        $mensaje.= mysql_error();
    }
    else
    {
        $sql="delete from respuesta where id_metodo=".$row[0];
        $Consulta_ID = mysql_query($sql);
        if (!$Consulta_ID){
            $mensaje= $sql."<br>";
            $mensaje.= mysql_error();
            break;
        }
        else
        {
            $mensaje="";
        }
    }

    $sql="delete from metodo where id_metodo=".$row[0];
    $Consulta_ID = mysql_query($sql);

    if (!$Consulta_ID){
        $mensaje= $sql."<br>";
        $mensaje.= mysql_error();
        break;
    }
}
$sql = "delete from clasificacion where id_clasificacion = ".$_GET["cat"];
$Consulta_ID = mysql_query($sql);

if (!$Consulta_ID){
    $mensaje= $sql."<br>";
    $mensaje.= mysql_error();
}
else
{
    $mensaje = "Actualizacion exitosa!! Gracias ". $_SERVER["PHP_AUTH_USER"] . "!";

    $descripcion = "elimino la clasificacion ".$nombre_clasificacion;

    $descripcion .= ''.$row[0]." del proyecto ";

    $sql = "Select name from mantis_project_table where id = ".$_GET["project"];

    $row = mysql_fetch_array(mysql_query($sql));

    $descripcion .= ''.$row[0];

    $sql = "Insert into registro(id_proyecto,id_clasificacion,usuario,fecha,operacion,descripcion) values (".$_GET["project"].",".$_GET["cat"].",'".$_SERVER["PHP_AUTH_USER"]."','".  date("Y-m-d H:i:s")."','borrar','".$descripcion."')";

    $Consulta_ID = mysql_query($sql);


}

header("Location: index.php?mensaje=".$mensaje."&project=".$_GET["project"]);

ob_end_flush();
    }


    static function CreateCategory()
    {
// Create a category
ob_start();
require_once("../server/bootstrap.php");

$id_clasificacion = null;

$sql = "Insert into clasificacion(id_proyecto,nombre,descripcion) values(".$_POST["id_proyecto"].",'".$_POST["nombre_clasificacion"]."','".$_POST["descripcion_clasificacion"]."')";

$Consulta_ID = mysql_query($sql);

if (!$Consulta_ID){
    $mensaje= $sql."<br>";
    $mensaje.= mysql_error();
}

else
{
    $sql="Select LAST_INSERT_ID()";
    $Consulta_ID = mysql_query($sql);
    if (!$Consulta_ID){
        $mensaje= $sql."<br>";
        $mensaje.= mysql_error();
    }
    else
    {
        $mensaje="Clasificacion creada exitosamente! Gracias ". $_SERVER["PHP_AUTH_USER"] . "!";
        $id_clasificacion= mysql_fetch_row($Consulta_ID);

        $descripcion = "El usuario ".$_SERVER["PHP_AUTH_USER"]." agrego la clasificacion ".$_POST["nombre_clasificacion"]." en el proyecto ";
        $sql = "Select name from mantis_project_table where id = ".$_POST["id_proyecto"];
        $row = mysql_fetch_array(mysql_query($sql));
        $descripcion .= ''.$row[0];
        $sql = "Insert into registro(id_proyecto,id_clasificacion,usuario,fecha,operacion,descripcion) values(".$_POST["id_proyecto"].",".$id_clasificacion[0].",'".$_SERVER["PHP_AUTH_USER"]."','".  date("Y-m-d H:i:s")."','agregar','".$descripcion."')";
        $Consulta_ID = mysql_query($sql);
    }
}

$location = "Location: index.php?mensaje=".$mensaje."&project=".$_POST["id_proyecto"];

if(!is_null($id_clasificacion))
{
    $location .= "&cat=".$id_clasificacion[0];
}

header($location);

ob_end_flush();
    }

    static function EditCategory()
    {
// Edit a category

require_once("../server/bootstrap.php");

$id_clasificacion = null;

$sql = "Update clasificacion set nombre='".$_POST["nombre_clasificacion"]."',descripcion='".$_POST["descripcion_clasificacion"]."' where id_clasificacion=".$_POST["id_clasificacion"];

$Consulta_ID = mysql_query($sql);

if (!$Consulta_ID) {
    $mensaje= $sql."<br>";
    $mensaje.= mysql_error();
}
else
{
    $mensaje="Clasificacion editada exitosamente! Gracias ". $_SERVER["PHP_AUTH_USER"] . "!";
    $id_clasificacion= $_POST["id_clasificacion"];
    $descripcion = "El usuario ".$_SERVER["PHP_AUTH_USER"]." edito la clasificacion ".$_POST["nombre_clasificacion"]." en el proyecto ";
    $sql = "Select name from mantis_project_table where id = ".$_POST["id_proyecto"];
    $row = mysql_fetch_array(mysql_query($sql));
    $descripcion .= ''.$row[0];

    $sql = "Insert into registro(id_proyecto,id_clasificacion,usuario,fecha,operacion,descripcion) values(".$_POST["id_proyecto"].",".$id_clasificacion.",'".$_SERVER["PHP_AUTH_USER"]."','".  date("Y-m-d H:i:s")."','editar','".$descripcion."')";

    $Consulta_ID = mysql_query($sql);
}

$location = "Location: index.php?mensaje=".$mensaje."&project=".$_POST["id_proyecto"];
if (!is_null($id_clasificacion))
{
    $location .= "&cat=".$id_clasificacion;
}

header($location);
    }

    static function MethodDetails()
    {
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

        $respuestas = -1;
        $query_respuestas = "select * from respuesta where id_metodo=".$mid;
        $r=mysql_query($query_respuestas) or die(mysql_error());
        $i=0;

        while($row=mysql_fetch_assoc($r))
        {
           if($respuestas==-1)
               unset($respuestas);
           $respuestas[$i]=$row;
           $i++;
        }
    }

    static function CreateMethod()
    {
        if(!isset ($_POST["clasificacion_metodo"]) || !is_numeric($_POST["clasificacion_metodo"]))
        {
            throw new Exception("No se obtuvo clasificacion de metodo");
        }

        // Defaults
        $combo = isset($_POST["sesion_valida"]);
        if(!$combo) $combo=0;

        $regresa_html = isset($_POST["regresa_html"]);
        if(!$regresa_html) $regresa_html=0;

        $sql = "insert into metodo (id_clasificacion, nombre, tipo, sesion_valida, grupo, ejemplo_peticion, ejemplo_respuesta, descripcion, subtitulo, regresa_html) "
                . " values ({0}, '{1}', '{2}', {3}, {4}, '{5}', '{6}', '{7}', '{8}', {9})";

        str_replace("{0}", $_POST["clasificacion_metodo"], $sql);
        str_replace("{1}", $_POST["nombre_metodo"], $sql);
        str_replace("{2}", $_POST["tipo_metodo"], $sql);
        str_replace("{3}", $combo, $sql);
        str_replace("{4}", $_POST["grupo"], $sql);
        str_replace("{5}", $_POST["ejemplo_peticion"], $sql);
        str_replace("{6}", $_POST["ejemplo_respuesta"], $sql);
        str_replace("{7}", preg_replace('/\'/','`', $_POST["descripcion_metodo"]), $sql);
        str_replace("{8}", $_POST["subtitulo"], $sql);
        str_replace("{9}", $regresa_html, $sql);

        $Consulta_ID = mysql_query($sql);

        $sql="Select LAST_INSERT_ID()";
        $Consulta_ID = mysql_query($sql);
        if (!$Consulta_ID)
        {
            throw new Exception(mysql_error());
        }

        $id_metodo= mysql_fetch_row($Consulta_ID);
        for($i = 0; $i < $_POST["numero_argumentos"]; $i++)
        {
            if(!isset($_POST["nombre_argumento_".$i]))
            {
                continue;
            }

            $sql="Insert into argumento(id_metodo,nombre,descripcion,ahuevo,tipo,defaults) ".
                "values(".$id_metodo[0].",'".$_POST["nombre_argumento_".$i]."','".$_POST["descripcion_argumento_".$i]."','".$_POST["ahuevo_".$i]."','".$_POST["tipo_argumento_".$i]."','".$_POST["default_".$i]."')";
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
            "id_clasificacion={0}," 
            "nombre='{1}',"
            "tipo='{2}',"
            "sesion_valida={3},"
            "grupo={4},"
            "ejemplo_peticion='{5}',"
            "ejemplo_respuesta='{6}',"
            "descripcion='{7}',"
            "subtitulo='{8}',"
            "regresa_html={9}"
            " where id_metodo={10}";

        str_replace("{0}", $_POST["clasificacion_metodo"], $sql);
        str_replace("{1}", $_POST["nombre_metodo"], $sql);
        str_replace("{2}", $_POST["tipo_metodo"], $sql);
        str_replace("{3}", $combo, $sql);
        str_replace("{4}", $_POST["grupo"], $sql);
        str_replace("{5}", $_POST["ejemplo_peticion"], $sql);
        str_replace("{6}", $_POST["ejemplo_respuesta"], $sql);
        str_replace("{7}", preg_replace('/\'/','`', $_POST["descripcion_metodo"]), $sql);
        str_replace("{8}", $_POST["subtitulo"], $sql);
        str_replace("{9}", $regresa_html, $sql);
        str_replace("{10}", $_POST["id_metodo"], $sql);

        $Consulta_ID = mysql_query($sql);
        $id_metodo=$_POST["id_metodo"];

        $sql="delete from argumento where id_metodo=".$_POST["id_metodo"];
        if (!mysql_query($sql)){
            throw new Exception(mysql_error());
        }

        $sql="delete from respuesta where id_metodo=".$_POST["id_metodo"];
        if (!mysql_query($sql)){
            throw new Exception(mysql_error());
        }

        $id_metodo = $_POST["id_metodo"];
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

    static function EditMethod()
    {
// Delete method
ob_start();
require_once("../server/bootstrap.php");

$sql = "Select nombre from metodo where id_metodo = ".$_GET["m"];

$row = mysql_fetch_array(mysql_query($sql));

$nombre_metodo = $row[0];

$sql="delete from argumento where id_metodo=".$_GET["m"];
$Consulta_ID = mysql_query($sql);
if (!$Consulta_ID){
    $mensaje= $sql."<br>";
    $mensaje.= mysql_error();
}
else
{
    $sql="delete from respuesta where id_metodo=".$_GET["m"];
    $Consulta_ID = mysql_query($sql);
    if (!$Consulta_ID){
        $mensaje= $sql."<br>";
        $mensaje.= mysql_error();
    }
    else
    {
        $mensaje="";
    }
}

$sql="delete from metodo where id_metodo=".$_GET["m"];
$Consulta_ID = mysql_query($sql);

if (!$Consulta_ID){
    $mensaje= $sql."<br>";
    $mensaje.= mysql_error();
}

else
{
    $mensaje = "Actualizacion exitosa!! Gracias ". $_SERVER["PHP_AUTH_USER"] . "!";

    $descripcion = "elimino el metodo ".$nombre_metodo." en la clasificacion ";

    $sql = "Select nombre from clasificacion where id_clasificacion = ".$_GET["cat"];

    $row = mysql_fetch_array(mysql_query($sql));

    $descripcion .= ''.$row[0]." del proyecto ";

    $sql = "Select name from mantis_project_table where id = ".$_GET["project"];

    $row = mysql_fetch_array(mysql_query($sql));

    $descripcion .= ''.$row[0];

    $sql = "Insert into registro(id_proyecto,id_clasificacion,id_metodo,usuario,fecha,operacion,descripcion) values (".$_GET["project"].",".$_GET["cat"].",".$_GET["m"].",'".$_SERVER["PHP_AUTH_USER"]."','".  date("Y-m-d H:i:s")."','borrar','".$descripcion."')";

    $Consulta_ID = mysql_query($sql);


}

//header("Location: index.php?mensaje=".$mensaje."&cat=".$_GET["cat"]."&project=".$_GET["project"]);

ob_end_flush();

    }
}

