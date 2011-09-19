<pre><?php

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


	function delete_directory($dirname) {
	   if (is_dir($dirname))
	      $dir_handle = opendir($dirname);
	   if (!$dir_handle)
	      return false;
	   while($file = readdir($dir_handle)) {
	      if ($file != "." && $file != "..") {
	         if (!is_dir($dirname."/".$file))
	            unlink($dirname."/".$file);
	         else
	            delete_directory($dirname.'/'.$file);    
	      }
	   }
	   closedir($dir_handle);
	   rmdir($dirname);
	   return true;
	}
 

 	function create_structure( $dir, $n = 1 ){

 		$p = explode( "/", $dir );
 		
 		if( $n == sizeof($p) ) return;

		$f = "";

		for ($i=0; $i < $n; $i++) $f .=  $p[$i] . "/";

 		if( !is_dir( $f ) ) mkdir ( $f );
		
		create_structure($dir, ++$n);
 	}



 	/*********

 	 *********/
 	if(is_dir("../../tmp/out"))
 	{
 		delete_directory( "../../tmp/out" );
 	}

	create_structure("../../tmp/out/server/api/");
	create_structure("../../tmp/out/www/api/");


	$res = mysql_query("select * from metodo order by id_clasificacion") or die(mysql_error());



	function write_www_file($metodo)
	{
		
		$out = 	"<?php\n";
		$out .=	"/**\n";
		$out .= "  * " .  $metodo["descripcion"] . "\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  **/\n";


		return $out;

	}


	function write_server_file($mid)
	{

		$out = "x";

		return $out;
	}




	while(($row = mysql_fetch_assoc($res)) != null )
	{
		

		echo  $row["nombre"] . "\n";
		//echo "../../tmp/out/www/" . $row["nombre"] . "\n";

		//create www/space
		create_structure( "../../tmp/out/www/" . $row["nombre"] . "/");

		$f = "../../tmp/out/www/" . $row["nombre"] . "/index.php";
		$f = fopen($f, 'w') or die("can't open file");
		fwrite($f, write_www_file(  $row ) );
		fclose($f);


		$fname = str_replace("/",".", $row["nombre"]);

		$f = "../../tmp/out/server/api/" . $fname . ".php";
		$f = fopen($f, 'w') or die("can't open file");
		fwrite($f, write_server_file(  $row) );
		fclose($f);

	}











?></pre>