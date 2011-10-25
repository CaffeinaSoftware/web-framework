<?php

	require_once("../server/bootstrap.php");

?>

<form action="test.php" method="POST">
<textarea cols="80"  rows="10" name="tests">

#beginTest
	#Desc Nueva empresa
	#URL /empresas/nueva
	#Input { "des" : 12 }
	#JSONOutput { "success": false , "reason" : "Invalid method call for dispatching." }
#endTest



#beginTest
	#Desc Envio a login sin argumentos
	#URL asdf
	#Input action=2099
	#JSONOutput {"success": false , "reason": "Invalidas", "text" : "Credenciales invalidas. Intento numero <b>1</b>. " }
#endTest

</textarea><br>
	<input type="submit" value="Iniciar pruebas">
</form>

