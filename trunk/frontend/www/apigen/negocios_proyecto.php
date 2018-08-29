<?php
ob_start();
require_once("../../server/bootstrap.php");

$id_proyecto = null;

$sql = "Insert into proyecto(nombre,descripcion) values('".$_POST["nombre_proyecto"]."','')";

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
        $id_proyecto= mysql_fetch_row($Consulta_ID);
    }
}

$location = "Location: index.php?mensaje=".$mensaje;

if(!is_null($id_proyecto))
{
    $location .= "&project=".$id_proyecto[0];
}

header($location);

ob_end_flush();

?>
