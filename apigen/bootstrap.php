<?php

# #########################################
#
#   BOOTSTRAPING
#
# #########################################
//define('POS_PATH_TO_SERVER_ROOT', str_replace("bootstrap.php", "", __FILE__ ));
//ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . POS_PATH_TO_SERVER_ROOT);

require_once('config.php');

$conn = new mysqli($Servidor, $Usuario, $Clave, $BaseDatos);

if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function mysql_query($query) {
    global $conn;
    return $conn->query($query);
}

function mysql_fetch_assoc($result) {
    global $conn;
    return $result->fetch_assoc();
}
