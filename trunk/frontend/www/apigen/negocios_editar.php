<?php
ob_start();
			require_once("../../server/bootstrap.php");
                        
			$combo=isset($_POST["sesion_valida"]);
			if(!$combo)
			$combo=0;
			$regresa_html=isset($_POST["regresa_html"]);
			if(!$regresa_html)
			$regresa_html=0;
		   $sql="update metodo set id_clasificacion=".$_POST["clasificacion_metodo"].",nombre='".$_POST["nombre_metodo"]."',tipo='".$_POST["tipo_metodo"]."',sesion_valida=".$combo.",grupo=".$_POST["grupo"].",ejemplo_peticion='".$_POST["ejemplo_peticion"]."',ejemplo_respuesta='".$_POST["ejemplo_respuesta"]."',descripcion='".preg_replace('/\'/','`', $_POST["descripcion_metodo"] )."',subtitulo='".$_POST["subtitulo"]."',regresa_html=".$regresa_html." where id_metodo=".$_POST["id_metodo"];
		   $Consulta_ID = mysql_query($sql);
			$id_metodo=$_POST["id_metodo"];
		   if (!$Consulta_ID){
			$mensaje= $sql."<br>";
			$mensaje.= mysql_error();
		   }
			else
			{
				$sql="delete from argumento where id_metodo=".$_POST["id_metodo"];
				$Consulta_ID = mysql_query($sql);
				if (!$Consulta_ID){
				$mensaje= $sql."<br>";
				$mensaje.= mysql_error();
				}
				else
				{
				$sql="delete from respuesta where id_metodo=".$_POST["id_metodo"];
				$Consulta_ID = mysql_query($sql);
					if (!$Consulta_ID){
					$mensaje= $sql."<br>";
					$mensaje.= mysql_error();
					}
					else
					{
						$mensaje="";
						$id_metodo= $_POST["id_metodo"];
						for($i = 0; $i < $_POST["numero_argumentos"]; $i++)
						{
							$combo=isset($_POST["borrar_argumento_".$i]);
							if(!$combo)
								$combo=0;
							if($combo)
								continue;
							$sql="Insert into argumento(id_metodo,nombre,descripcion,ahuevo,tipo,defaults) values(".$id_metodo.",'".$_POST["nombre_argumento_".$i]."','".$_POST["descripcion_argumento_".$i]."','".$_POST["ahuevo_".$i]."','".$_POST["tipo_argumento_".$i]."','".$_POST["default_".$i]."')";
							$Consulta_ID = mysql_query($sql);
							if (!$Consulta_ID){
							$mensaje.= $sql."<br>";
							$mensaje.= mysql_error()."<br>";
							break;
							}
						}
						for($i = 0; $i < $_POST["numero_respuestas"]; $i++)
						{
							$combo=isset($_POST["borrar_respuesta_".$i]);
							if(!$combo)
								$combo=0;
							if($combo)
								continue;
							$sql="Insert into respuesta(id_metodo,nombre,descripcion,tipo) values(".$id_metodo.",'".$_POST["nombre_respuesta_".$i]."','".$_POST["descripcion_respuesta_".$i]."','".$_POST["tipo_respuesta_".$i]."')";
							$Consulta_ID = mysql_query($sql);
							if (!$Consulta_ID){
							$mensaje.= $sql."<br>";
							$mensaje.= mysql_error();
							break;
							}
						}
						if($mensaje=="")
						{
							$mensaje="Actualizacion exitosa!! :D";
                                                        
                                                        $descripcion = "El usuario '".$_SERVER["http_user"]."' edito el metodo ".$_POST["nombre_metodo"]." en la clasificacion ";
                                                        
                                                        $sql = "Select nombre from clasificacion where id_clasificacion = ".$_POST["clasificacion_metodo"];
                                                        
                                                        $row = mysql_fetch_array(mysql_query($sql));
                                                        
                                                        $descripcion .= ''.$row[0]." del proyecto ";
                                                        
                                                        $sql = "Select name from mantis_project_table where id = ".$_POST["id_proyecto"];
                                                        
                                                        $row = mysql_fetch_array(mysql_query($sql));
                                                        
                                                        $descripcion .= ''.$row[0];
                                                        
                                                        $sql = "Insert into registro(id_proyecto,id_clasificacion,id_metodo,usuario,fecha,operacion,descripcion) values (".$_POST["id_proyecto"].",".$_POST["clasificacion_metodo"].",".$_POST["id_metodo"].",'".$_SERVER["http_user"]."','".  date("Y-m-d H:i:s")."','editar','".$descripcion."')";
                                                        
                                                        $Consulta_ID = mysql_query($sql);
                                                        
						}
					}
				}
			}
			
		header("Location: index.php?mensaje=".$mensaje."&m=".$id_metodo."&cat=".$_POST["clasificacion_metodo"]."&project=".$_POST["id_proyecto"]);
ob_end_flush();
?>
