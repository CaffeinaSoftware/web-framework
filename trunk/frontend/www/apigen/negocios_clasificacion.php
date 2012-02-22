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
                $mensaje="Clasificacion creada exitosamente";
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
?>
