<?php
ob_start();
			require_once("../../server/bootstrap.php");
                        
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
                                    $mensaje="Actualizacion exitosa!! :D";
                            }
                    }
                    
		   $sql="delete from metodo where id_metodo=".$_GET["m"];
		   $Consulta_ID = mysql_query($sql);

		   if (!$Consulta_ID){
			$mensaje= $sql."<br>";
			$mensaje.= mysql_error();
		   }
		header("Location: index.php?mensaje=".$mensaje."&cat=".$_GET["cat"]."&project=".$_GET["project"]);
		
		ob_end_flush();
?>