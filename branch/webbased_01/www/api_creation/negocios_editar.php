<?php
ob_start();
//			       metodo
//  nombre				----------	$_POST["nombre_metodo"]
//	subtitulo  			----------	$_POST["subtitulo"]
//	descripcion			----------	$_POST["descripcion_metodo"]
//	tipo				----------	$_POST["tipo_metodo"]
//  sesion_valida		----------	$_POST["sesion_valida"]
//	grupo				----------	$_POST["grupo"]
//	peticion			----------	$_POST["ejemplo_peticion"]
//	respuesta			----------	$_POST["ejemplo_respuesta"]
//
//           		argumento
//	nombre				----------	$_POST["nombre_argumento_n"]
//	descripcion			----------	$_POST["descripcion_argumento_n"]
//	ahuevo				----------	$_POST["ahuevo_n"]
//	tipo				----------	$_POST["tipo_argumento_n"]
//	default				----------	$_POST["default_n"]
//
//                 	respuesta
//	nombre				----------	$_POST["nombre_respuesta_n"]
//	descripcion			----------	$_POST["descripcion_respuesta_n"]
//	tipo				----------	$_POST["tipo_respuesta_n"]

			require_once("../../server/bootstrap.php");
			$combo=isset($_POST["sesion_valida"]);
			if(!$combo)
			$combo=0;
			$regresa_html=isset($_POST["regresa_html"]);
			if(!$regresa_html)
			$regresa_html=0;
		   $sql="update metodo set id_clasificacion=".$_POST["clasificacion_metodo"].",nombre='".$_POST["nombre_metodo"]."',tipo='".$_POST["tipo_metodo"]."',sesion_valida=".$combo.",grupo=".$_POST["grupo"].",ejemplo_peticion='".$_POST["ejemplo_peticion"]."',ejemplo_respuesta='".$_POST["ejemplo_respuesta"]."',descripcion='".$_POST["descripcion_metodo"]."',subtitulo='".$_POST["subtitulo"]."',regresa_html=".$regresa_html." where id_metodo=".$_POST["id_metodo"];
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
							if($_POST["borrar_argumento_".$i])
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
							if($_POST["borrar_respuesta_".$i])
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
						}
					}
				}
			}
		header("Location: ../render/api_doc.php?mensaje=".$mensaje."&m=".$id_metodo);
		
ob_end_flush();
?>
