<?php

    $BaseDatos="api_pos";
    $Servidor="localhost";
    $Usuario="root";
    $Clave="";

	$Conexion_ID = mysql_connect($Servidor, $Usuario, $Clave);


	if (!$Conexion_ID){
		die(mysql_error());	
    }

    if (!mysql_select_db($BaseDatos, $Conexion_ID)){
    	die(mysql_error());
	}
