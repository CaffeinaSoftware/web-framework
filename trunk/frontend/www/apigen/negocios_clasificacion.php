<?php
ob_start();
    require_once("../../server/bootstrap.php");

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
                $mensaje="";
                $id_clasificacion= mysql_fetch_row($Consulta_ID);
        }
    }
    
    $location = "Location: index.php?mensaje=".$mensaje."&project=".$_POST["id_proyecto"];
    
    if(!is_null($id_clasificacion))
    {
        $location .= "&cat=".$id_clasificacion[0];
    }
    
    header($location);
    
    ob_end_flush();
?>
