<?php
session_start();
$_SESSION["metodo"]=$_GET["id"];
header("Location: editmethod.inc.php");
?>
