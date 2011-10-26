<?php

	require_once("../server/bootstrap.php");

?>

<form action="test.php" method="POST">
<textarea cols="80"  rows="10" name="tests">

#beginTest
	#Desc Nueva empresa
	#URL /empresa/nuevo
	#Input { "des" : 12 }
	#JSONOutput {"status":"error","error":"Required parameter rfc is missing.","errorcode":100}
#endTest


</textarea><br>
	<input type="submit" value="Iniciar pruebas">
</form>

