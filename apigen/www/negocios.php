<?php

// Called when creating a new method

ob_start();


//                 metodo
//  nombre              ----------  $_POST["nombre_metodo"]
//  subtitulo           ----------  $_POST["subtitulo"]
//  descripcion         ----------  $_POST["descripcion_metodo"]
//  tipo                ----------  $_POST["tipo_metodo"]
//  sesion_valida       ----------  $_POST["sesion_valida"]
//  grupo               ----------  $_POST["grupo"]
//  peticion            ----------  $_POST["ejemplo_peticion"]
//  respuesta           ----------  $_POST["ejemplo_respuesta"]
//
//                  argumento
//  nombre              ----------  $_POST["nombre_argumento_n"]
//  descripcion         ----------  $_POST["descripcion_argumento_n"]
//  ahuevo              ----------  $_POST["ahuevo_n"]
//  tipo                ----------  $_POST["tipo_argumento_n"]
//  default             ----------  $_POST["default_n"]
//
//                  respuesta
//  nombre              ----------  $_POST["nombre_respuesta_n"]
//  descripcion         ----------  $_POST["descripcion_respuesta_n"]
//  tipo                ----------  $_POST["tipo_respuesta_n"]

require_once("../server/bootstrap.php");

if(!isset ($_POST["clasificacion_metodo"]) || !is_numeric($_POST["clasificacion_metodo"]))
{
    $mensaje = "No se obtuvo clasificacion de metodo";
}
else
{
    $id_metodo=-1;
    $combo=isset($_POST["sesion_valida"]);
    if(!$combo)
        $combo=0;
    $regresa_html=isset($_POST["regresa_html"]);
    if(!$regresa_html)
        $regresa_html=0;
    $sql="Insert into metodo(id_clasificacion,nombre,tipo,sesion_valida,grupo,ejemplo_peticion,ejemplo_respuesta,descripcion,subtitulo,regresa_html) values(".$_POST["clasificacion_metodo"].",'".$_POST["nombre_metodo"]."','".$_POST["tipo_metodo"]."',".$combo.",".$_POST["grupo"].",'".$_POST["ejemplo_peticion"]."','".$_POST["ejemplo_respuesta"]."','".$_POST["descripcion_metodo"]."','".$_POST["subtitulo"]."',".$regresa_html.")";
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
            $mensaje="";
            unset($id_metodo);
            $id_metodo= mysql_fetch_row($Consulta_ID);
            for($i = 0; $i < $_POST["numero_argumentos"]; $i++)
            {
                if(isset($_POST["nombre_argumento_".$i]))
                {
                    $sql="Insert into argumento(id_metodo,nombre,descripcion,ahuevo,tipo,defaults) values(".$id_metodo[0].",'".$_POST["nombre_argumento_".$i]."','".$_POST["descripcion_argumento_".$i]."','".$_POST["ahuevo_".$i]."','".$_POST["tipo_argumento_".$i]."','".$_POST["default_".$i]."')";
                    $Consulta_ID = mysql_query($sql);
                    if (!$Consulta_ID){
                        $mensaje.= $sql."<br>";
                        $mensaje.= mysql_error()."<br>";
                        break;
                    }
                }
            }
            for($i = 0; $i < $_POST["numero_respuestas"]; $i++)
            {
                if(isset($_POST["nombre_respuesta_".$i]))
                {
                    $sql="Insert into respuesta(id_metodo,nombre,descripcion,tipo) values(".$id_metodo[0].",'".$_POST["nombre_respuesta_".$i]."','".$_POST["descripcion_respuesta_".$i]."','".$_POST["tipo_respuesta_".$i]."')";
                    $Consulta_ID = mysql_query($sql);
                    if (!$Consulta_ID){
                        $mensaje.= $sql."<br>";
                        $mensaje.= mysql_error();
                        break;
                    }
                }
            }
            if($mensaje=="")
            {
                $mensaje="Insercion exitosa!! Gracias ". $_SERVER["PHP_AUTH_USER"] . "!";

                $descripcion = "Agrego el metodo ".$_POST["nombre_metodo"]." en la clasificacion";

                $sql = "Select nombre from clasificacion where id_clasificacion = ".$_POST["clasificacion_metodo"];

                $row = mysql_fetch_array(mysql_query($sql));

                $descripcion .= ''.$row[0]." del proyecto ";

                $sql = "Select name from mantis_project_table where id = ".$_POST["id_proyecto"];

                $row = mysql_fetch_array(mysql_query($sql));

                $descripcion .= ''.$row[0];

                $sql = "Insert into registro(id_proyecto,id_clasificacion,id_metodo,usuario,fecha,operacion,descripcion) values(".$_POST["id_proyecto"].",".$_POST["clasificacion_metodo"].",".$id_metodo[0].",'".$_SERVER["PHP_AUTH_USER"]."','".  date("Y-m-d H:i:s",time())."','agregar','".$descripcion."')";

                $Consulta_ID = mysql_query($sql);
            }
        }
    }
}
$location = "Location: index.php?mensaje=".$mensaje."&project=".$_POST["id_proyecto"];
if(isset($id_metodo)&&$id_metodo!=-1)
{
    $location .= "&m=".$id_metodo[0];
}
if(isset($_POST["clasificacion_metodo"]) && is_numeric($_POST["clasificacion_metodo"]))
{
    $location .= "&cat=".$_POST["clasificacion_metodo"];
}
header($location);
ob_end_flush();
?>
