<?php
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

    $BaseDatos="mini_programa";
    $Servidor="localhost";
    $Usuario="root";
    $Clave="";

       $Conexion_ID = mysql_connect($Servidor, $Usuario, $Clave);
	   
       if (!$Conexion_ID){
          $Error = "ERROR : El intento de conexión ha fallado.";
		  $mensaje= $Error;
       }

       else if (!@mysql_select_db($BaseDatos, $Conexion_ID)){
          $Error = "ERROR : Imposible abrir la base de datos ".$BaseDatos ;
		  $mensaje= $Error;
       }
	   else
	   {
			$combo=isset($_POST["sesion_valida"]);
			if(!$combo)
			$combo=0;
		   $sql="Insert into metodo(id_clasificacion,nombre,tipo,sesion_valida,grupo,ejemplo_peticion,ejemplo_respuesta,descripcion,subtitulo) values(".$_POST["clasificacion_metodo"].",'".$_POST["nombre_metodo"]."','".$_POST["tipo_metodo"]."',".$combo.",".$_POST["grupo"].",'".$_POST["ejemplo_peticion"]."','".$_POST["ejemplo_respuesta"]."','".$_POST["descripcion_metodo"]."','".$_POST["subtitulo"]."')";
		   $Consulta_ID = mysql_query($sql, $Conexion_ID);

		   if (!$Consulta_ID){
			$mensaje= $sql."<br>";
			$mensaje.= mysql_error();
		   }
			else
			{
				$sql="Select LAST_INSERT_ID()";
				$Consulta_ID = mysql_query($sql, $Conexion_ID);
				if (!$Consulta_ID){
				$mensaje= $sql."<br>";
				$mensaje.= mysql_error();
				}
				else
				{
					$mensaje="";
					for($i = 0; $i < $_POST["numero_argumentos"]; $i++)
					{
						$id_metodo= mysql_fetch_row($Consulta_ID);
						$sql="Insert into argumento(id_metodo,nombre,descripcion,ahuevo,tipo,defaults) values(".$id_metodo[0].",'".$_POST["nombre_argumento_".$i]."','".$_POST["descripcion_argumento_".$i]."','".$_POST["ahuevo_".$i]."','".$_POST["tipo_argumento_".$i]."','".$_POST["default_".$i]."')";
						$Consulta_ID = mysql_query($sql, $Conexion_ID);
						if (!$Consulta_ID){
						$mensaje.= $sql."<br>";
						$mensaje.= mysql_error()."<br>";
						break;
						}
					}
					for($i = 0; $i < $_POST["numero_respuestas"]; $i++)
					{
						$sql="Insert into respuesta(id_metodo,nombre,descripcion,tipo) values(".$id_metodo[0].",'".$_POST["nombre_respuesta_".$i]."','".$_POST["descripcion_respuesta_".$i]."','".$_POST["tipo_respuesta_".$i]."')";
						$Consulta_ID = mysql_query($sql, $Conexion_ID);
						if (!$Consulta_ID){
						$mensaje.= $sql."<br>";
						$mensaje.= mysql_error();
						break;
						}
					}
					if($mensaje=="")
					{
						$mensaje="Inserción exitosa!! :D";
					}
				}
			}
		}
		header("Location: new_method.php?mensaje=".$mensaje);
?>