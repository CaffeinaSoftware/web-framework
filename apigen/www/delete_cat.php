<?php
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
?>
