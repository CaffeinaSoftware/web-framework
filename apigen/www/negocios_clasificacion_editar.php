<?php
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
