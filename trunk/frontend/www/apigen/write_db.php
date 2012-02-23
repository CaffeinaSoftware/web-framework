<pre><?php

	require_once("../../server/bootstrap.php");
	require_once("utils.php");







################################################################################
				   #####     #####
                                   #   ##    #   ##
                                   #    ##   #   ##
                                   #     #   #  #
                                   #    ##   #   ##
                                   #   ##    #   ##
                                   #####     #####
################################################################################
	function write_db_file( $base ){
                $texto = "";
               
               //$tablas=mysql_query("show tables from $base;"); 
               $tablas = array
               (
                   array( "mantis_project_table" ),
                   array( "clasificacion" ),
                   array( "metodo" ),
                   array( "argumento" ),
                   array( "registro" ),
                   array( "respuesta" ),
                   array( "httptesting_paquete_de_pruebas" ),
                   array( "httptesting_ruta" ),
               );
               $texto.="create database if not exists $base;\n"; 
               $texto.="use $base;\n"; 
               //while($tabla=mysql_fetch_array($tablas)) 
               foreach($tablas as $tabla)
               { 
                  $mitabla=$tabla[0]; 
                  $creates=mysql_query("show create table $base.$mitabla;"); 
                  while($create=mysql_fetch_array($creates)) 
                  { 
                     $texto.=$create[1].";\n"; 
                     $datos=mysql_query("select * from $base.$mitabla;"); 
                     $campos=mysql_num_fields($datos); 
                     $regs=mysql_num_rows($datos); 
                     for($i=0;$i<$regs;$i++) 
                     { 
                        $inserta="insert into $mitabla("; 
                        for($j=0;$j<$campos;$j++) 
                        { 
                           $nombre=mysql_field_name($datos,$j); 
                           $inserta.="$nombre,"; 
                        } 
                        $inserta=substr($inserta,0,strlen($inserta)-1).") values("; 
                        for($j=0;$j<$campos;$j++) 
                        { 
                           $tipo=mysql_field_type($datos,$j); 
                           $valor=mysql_result($datos,$i,$j); 
                           switch($tipo) 
                           { 
                              case "string": 
                              case "date": 
                              case "time": 
                              case "blob":
                                 $valor="'$valor'"; 
                                 break; 
                           } 
                           $inserta.="$valor,"; 
                        } 
                        $inserta=substr($inserta,0,strlen($inserta)-1).");"; 
                        $texto.=$inserta."\n"; 
                     } 
                  } 
                  $texto.="\n"; 
               } 
            return $texto;

	}


################################################################################
					  ####  #####   ##   #####  ##### 
					 #        #    #  #  #    #   #   
					  ####    #   #    # #    #   #   
					      #   #   ###### #####    #   
					 #    #   #   #    # #   #    #   
					  ####    #   #    # #    #   #  	
################################################################################
 	if(is_dir("tmp/out")){
 		delete_directory( "tmp/out" );
 	}

 	if(is_dir("tmp/builds")){
 		delete_directory( "tmp/builds" );
 	}

	create_structure("tmp/out/server/private/");
	create_structure("tmp/builds/");


	$_api_file = fopen("tmp/out/server/private/api_pos_caffeina-labs.sql", 'w') or die("can't open file");
	
	
        fwrite($_api_file, write_db_file(  "caffeina-labs" ) );

	fclose($_api_file);
        
        echo write_db_file("caffeina-labs");
	
	//ok al terminar enzipar todo en builds
	Zip('tmp/out', 'tmp/builds/api_pos_caffeina-labs.zip');








?></pre>