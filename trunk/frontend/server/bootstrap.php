<?php


# #########################################
#
#	BOOTSTRAPING 
#
# #########################################
define('POS_PATH_TO_SERVER_ROOT', str_replace("bootstrap.php", "", __FILE__ ));
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . POS_PATH_TO_SERVER_ROOT);


#require http client for posts and gets
require_once("libs/HTTPClient.php");
require_once("libs/TestParser.php");
require_once("libs/Tester.php");


$BaseDatos 	= "api_pos";
$Servidor 	= "localhost";
$Usuario 	= "root";
$Clave 		= "";

$Conexion_ID = mysql_connect($Servidor, $Usuario, $Clave);

if (!$Conexion_ID){
	die(mysql_error());	
}

if (!mysql_select_db($BaseDatos, $Conexion_ID)){
	die(mysql_error());
}
