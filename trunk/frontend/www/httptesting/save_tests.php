<?php



require_once("../server/bootstrap.php");


mysql_query("UPDATE `http_tests` SET `tests` = '" . $_POST["tests"] . "'; ") ;

die(header("Location: index.php"));
