<?php
ob_start();
			require_once("../../server/bootstrap.php");
                        
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
                       $mensaje = "Actualizacion exitosa!! :D";
                       
                       $descripcion = "El usuario '".$_SERVER["http_user"]."' elimino el metodo ".$nombre_metodo." en la clasificacion ";

                        $sql = "Select nombre from clasificacion where id_clasificacion = ".$_GET["cat"];

                        $row = mysql_fetch_array(mysql_query($sql));

                        $descripcion .= ''.$row[0]." del proyecto ";

                        $sql = "Select name from mantis_project_table where id = ".$_GET["project"];

                        $row = mysql_fetch_array(mysql_query($sql));

                        $descripcion .= ''.$row[0];

                        $sql = "Insert into registro(id_proyecto,id_clasificacion,id_metodo,usuario,fecha,operacion,descripcion) values (".$_GET["project"].",".$_GET["cat"].",".$_GET["m"].",'".$_SERVER["http_user"]."','".  date("Y-m-d H:i:s")."','borrar','".$descripcion."')";

                        $Consulta_ID = mysql_query($sql);
                        
                       
                   }
                   
		header("Location: index.php?mensaje=".$mensaje."&cat=".$_GET["cat"]."&project=".$_GET["project"]);
		
		ob_end_flush();
?>