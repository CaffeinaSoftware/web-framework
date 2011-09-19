<pre><?php

	require_once("../../server/bootstrap.php");









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














	function write_www_file($metodo)
	{
		
		$out = 	"<?php\n";
		$out .=	"/**\n";
		$out .= "  * " .  $metodo["tipo"] . " " . $metodo["nombre"] . "\n";
		$out .= "  * " .  utf8_decode($metodo["subtitulo"]) . "\n";
		$out .= "  *\n";
		$out .= "  * " .  utf8_decode($metodo["descripcion"]) . "\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  **/\n";

		$fname = "api/" . str_replace("/",".", $metodo["nombre"]) . ".php";
		$out .= "  require_once(\"" . $fname . "\");\n";
		return $out;

	}










	function write_server_file($metodo)
	{


		$out = 	"<?php\n";
		$out .=	"/**\n";
		$out .= "  * " .  $metodo["tipo"] . " " . $metodo["nombre"] . "\n";
		$out .= "  * " .  utf8_decode($metodo["subtitulo"]) . "\n";
		$out .= "  *\n";
		$out .= "  * " .  utf8_decode($metodo["descripcion"]) . "\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  **/\n";

		$cname = ucwords(

				str_replace("/"," ", 
					str_replace("_"," ", $metodo["nombre"])
				)

			);

		$cname = str_replace(" ","", $cname) ;
		
		$out .= "\n";
		$out .= "  class ". $cname ." extends ApiHandler {\n";
		$out .= "  \n";
		$out .= "      protected function CheckAuthorization() \n";
		$out .= "      {\n";
		$out .= "  		\n";
		$out .= "      }\n";



		$out .= "\n";$out .= "\n";$out .= "\n";
		$out .= "      protected function ProcessRequest()\n";
		$out .= "      {\n";
                
		$out .= "          $"."this->request = array(\n";
		$out .= "            new ApiExposedProperty(\"username\", true, POST, array(\n";
		$out .= "                new StringValidator()\n";
		$out .= "            )),\n";
		$out .= "            new ApiExposedProperty(\"password\", true, POST, array(\n";
		$out .= "                new StringValidator()\n";
		$out .= "            ))  \n";
		$out .= "          );\n";
               
		$out .= "      }\n";

		$out .= "\n";$out .= "\n";$out .= "\n";
		$out .= "      protected function GenerateResponse()\n";
		$out .= "      {\n";
        
		$out .= "      }\n";


		$out .= "\n";$out .= "\n";$out .= "\n";
		$out .= "      protected function SendResponse() \n";
		$out .= "      {\n";
		$out .= "          // There should not be any failing path that gets into here\n";
		$out .= "          // Happy ending\n";
		$out .= "          die(json_encode(array(\n";
		$out .= "                  \"success\" => true,\n";
		$out .= "                  \"auth_token\" =>  $"."this->response->getToken( )\n";
		$out .= "          )));\n";
		$out .= "      }\n";

		$out .= "  }\n";
		$out .= "  \n";
		$out .= "  \n";
		$out .= "  \n";
		$out .= "  \n";
		$out .= "  \n";
		$out .= "  \n";

		return $out;

	}






	function write_controller($clasificacion)
	{
		
		$out = 	"<?php\n";
		$out .=	"/**\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  **/\n";		
		$out .= "	\n";
		$out .= "  class ". $clasificacion["nombre"] ."Controller implements I" . $clasificacion["nombre"] . "{\n";
		$out .= "  \n";

		$argsq = mysql_query("select * from metodo where id_clasificacion = ". $clasificacion["id_clasificacion"] .";");

		while(($m = mysql_fetch_assoc($argsq)) != null)
		{
			$out .= "  \n";
			$out .=	"	/**\n";
			$out .= " 	 *\n";
			$out .= " 	 *" . $m["descripcion"] . "\n";
			$out .= " 	 *\n";
			$out .= " 	 **/\n";

			$iname = str_replace("api/", "", $m["nombre"] );
			$iname = str_replace("/", " ", $iname );

			$parts = explode(" ", $iname);
			$iname = "";

			for ($i= sizeof($parts) - 1; $i > 0  ; $i--) 
			{ 
				$iname .= $parts[$i]." ";
			}

			$iname = ucwords($iname);
			$iname = str_replace(" ","", $iname );


			$out .= "	protected function " . $iname . "();";
			$out .= "  \n";
			$out .= "  \n";
			$out .= "  \n";
			$out .= "  \n";
		}

		$out .= "  }\n";


		return $out;


	}



	function write_controller_interface($clasificacion)
	{


		$out = 	"<?php\n";
		$out .=	"/**\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  *\n";
		$out .= "  **/\n";		
		$out .= "\n";
		$out .= "  interaface I". $clasificacion["nombre"] ." {\n";
		$out .= "  \n";

		$argsq = mysql_query("select * from metodo where id_clasificacion = ". $clasificacion["id_clasificacion"] .";");

		while(($m = mysql_fetch_assoc($argsq)) != null)
		{
			$out .= "  \n";
			$out .=	"	/**\n";
			$out .= " 	 *\n";
			$out .= " 	 *" . $m["descripcion"] . "\n";
			$out .= " 	 *\n";
			$out .= " 	 **/\n";

			$iname = str_replace("api/", "", $m["nombre"] );
			$iname = str_replace("/", " ", $iname );

			$parts = explode(" ", $iname);
			$iname = "";

			for ($i= sizeof($parts) - 1; $i > 0  ; $i--) 
			{ 
				$iname .= $parts[$i]." ";
			}

			$iname = ucwords($iname);
			$iname = str_replace(" ","", $iname );


			$out .= "	protected function " . $iname . "();";
			$out .= "  \n";
			$out .= "  \n";
			$out .= "  \n";
			$out .= "  \n";
		}

		$out .= "  }\n";


		return $out;

	}






	function write_html_doc( $clasificacion )
	{
		
		$out = "";
		$out .= "";
		$out .= "";
		$out .= "";
		$out .= "";
		$out .= "";
		$out .= "";
		$out .= "";

		return     $out;


	}


	function write_html_index()
	{
		


	}








 	if(is_dir("../../tmp/out"))
 	{
 		delete_directory( "../../tmp/out" );
 	}

	create_structure("../../tmp/out/server/api/");
	create_structure("../../tmp/out/www/api/");
	create_structure("../../tmp/out/docs/api/");
	create_structure("../../tmp/out/server/controller/");


	$res = mysql_query("select * from metodo order by id_clasificacion") or die(mysql_error());





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


		//create api 
		$fname = str_replace("/",".", $row["nombre"]);
		$f = "../../tmp/out/server/api/" . $fname . ".php";
		$f = fopen($f, 'w') or die("can't open file");
		fwrite($f, write_server_file(  $row) );
		fclose($f);

	}


	
	//create controller interface
	
	$query = mysql_query("select * from clasificacion ;");
	
	while( ($row = mysql_fetch_assoc( $query )) != null )
	{
		// write the interface
		$iname = str_replace(" ","", ucwords($row["nombre"]));
		$fn = "../../tmp/out/server/controller/" . $iname . ".interface.php";
		$f = fopen($fn, 'w') or die("can't open file");
		fwrite($f, write_controller_interface(  $row) );
		fclose($f);			


		//write the actual controller
		$fn = "../../tmp/out/server/controller/" . $iname . ".controller.php";
		$f = fopen($fn, 'w') or die("can't open file");
		fwrite($f, write_controller(  $row) );
		fclose($f);			


		//write the html documentation
		$fn = "../../tmp/out/docs/api/" . $iname . ".html";
		$f = fopen($fn, 'w') or die("can't open file");
		fwrite($f, write_html_doc(  $row) );
		fclose($f);	
	}

	//write the html index
	$fn = "../../tmp/out/docs/api/index.html";
	$f = fopen($fn, 'w') or die("can't open file");
	fwrite($f, write_html_index( $row) );
	fclose($f);		











?></pre>