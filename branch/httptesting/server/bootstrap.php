<?php


# #########################################
#
#	BOOTSTRAPING 
#
# #########################################
define('POS_PATH_TO_SERVER_ROOT', str_replace("bootstrap.php", "", __FILE__ ));
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . POS_PATH_TO_SERVER_ROOT);


#require http client for posts and gets
require_once("utils/http.php");

