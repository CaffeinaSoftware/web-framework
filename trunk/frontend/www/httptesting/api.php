<?php

	require_once( "../../server/bootstrap.php" );

	if(!isset($_POST["metodo"]))
	{
		throw new Exception("No se recibio que metodo se usara");
	}
  
	switch($_POST["metodo"])
	{
		case "editarPaquete" :
			$sql = "Update httptesting_paquete_de_pruebas set pruebas = '".$_POST["pruebas"]."', nombre='".$_POST["nombre"]."', descripcion='".$_POST["descripcion"]."', locked=".$_POST["locked"]." where id_paquete_de_pruebas=".$_POST["id_paquete_de_pruebas"];
	        break;
			
		case "nuevoPaquete" :
			$sql = "Insert into httptesting_paquete_de_pruebas(pruebas,nombre,descripcion,locked) values('".$_POST["pruebas"]."','".$_POST["nombre"]."','".$_POST["descripcion"]."',".$_POST["locked"].")";
			break;
			
		case "nuevaRuta" :
			$sql = "Insert into httptesting_ruta(nombre,ruta) values('".$_POST["nombre"]."','".$_POST["ruta"]."')";
			break;
			
		case "editarRuta" :
			$sql = "Update httptesting_ruta set nombre = '".$_POST["nombre"]."', ruta = '".$_POST["ruta"]."' where id_ruta = ".$_POST["id_ruta"];
			break;
	}
	$res = mysql_query($sql);
	if(!$res)
	{
		throw new Exception(mysql_error(),mysql_errno());
	}


?>