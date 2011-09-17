<?php
    $BaseDatos="mini_programa";
    $Servidor="localhost";
    $Usuario="root";
    $Clave="";

       $Conexion_ID = mysql_connect($Servidor, $Usuario, $Clave);
	   
       if (!$Conexion_ID){
          $Error = "ERROR : El intento de conexión ha fallado.";
		  echo $Error;
       }

       if (!@mysql_select_db($BaseDatos, $Conexion_ID)){
          $Error = "ERROR : Imposible abrir la base de datos ".$this->BaseDatos ;
		  echo $Error;
       }
		$sql="Insert into metodo(nombre,tipo,sesion_valida,grupo,ejemplo_peticion,ejemplo_respuesta,descripcion,subtitulo) values('".$_POST["nombre_metodo"]."','".$_POST["tipo_metodo"]."',".$_POST["sesion_valida"].",".$_POST["grupo"].",'".$_POST["ejemplo_peticion"]."','".$_POST["ejemplo_respuesta"]."','".$_POST["descripcion_metodo"]."','".$_POST["subtitulo"]."')";
       $Consulta_ID = mysql_query($sql, $Conexion_ID);

       if (!$Consulta_ID){
		echo $sql."<br>";
		echo mysql_error();
       }
		else
		{
			$sql="Select LAST_INSERT_ID()";
			$Consulta_ID = mysql_query($sql, $Conexion_ID);
			if (!$Consulta_ID){
			echo $sql."<br>";
			echo mysql_error();
			}
			else
			{
				for($i = 0; $i < $_POST["numero_argumentos"]; $i++)
				{
					$id_metodo= mysql_fetch_row($Consulta_ID);
					$sql="Insert into argumento(id_metodo,nombre,descripcion,ahuevo,tipo,default) values(".$id_metodo[0].",'".$_POST["nombre_argumento"]."','".$_POST["descripcion_argumento"]."',".$_POST["ahuevo"].",'".$_POST["tipo_argumento"]."','".$_POST["default"]."')";
					$Consulta_ID = mysql_query($sql, $Conexion_ID);
					if (!$Consulta_ID){
					echo $sql."<br>";
					echo mysql_error();
					break;
					}
				}
				for($i = 0; $i < $_POST["numero_respuestas"]; $i++)
				{
					$sql="Insert into respuesta(id_metodo,nombre,descripcion,tipo) values(".$id_metodo[0].",'".$_POST["nombre_respuesta"]."','".$_POST["descripcion_respuesta"]."','".$_POST["tipo_respuesta"]."')";
					$Consulta_ID = mysql_query($sql, $Conexion_ID);
					if (!$Consulta_ID){
					echo $sql."<br>";
					echo mysql_error();
					break;
					}
				}
			}
		}
?>