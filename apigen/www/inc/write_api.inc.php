<?php

require_once("../server/bootstrap.php");
require_once("utils.inc.php");

class GeneratePhpApi {

    static $tmpPath = "tmp/php/out/";

################################################################################
                   ##   #####  #
                  #  #  #    # #
                 #    # #    # #
                 ###### #####  #
                 #    # #      #
                 #    # #      #
################################################################################
static function write_api_file( $metodo ){
    $cname = ucwords( str_replace("/"," ", str_replace("_"," ", $metodo["nombre"]) ) );
    $cname = str_replace(" ","", $cname) ;

    $out = "\n";
    $out .= "  class ". $cname ." extends ApiHandler {\n";
    $out .= "  \n\n";
    $out .= "\tprotected function DeclareAllowedRoles(){  return BYPASS;  }\n";

    if( $metodo["sesion_valida"] == 0 )
        $out .= "\tprotected function CheckAuthorization() { /*SESION NO NECESARIA*/ return; }\n";

    $out .= "\tprotected function GetRequest()\n";
    $out .= "\t{\n";
    $out .= "\t\t$"."this->request = array(    \n";

    $args_params = mysql_query("select * from argumento where id_metodo = ". $metodo["id_metodo"] ." order by ahuevo desc, nombre;");

    while (($row_param = mysql_fetch_assoc( $args_params )) != null )
    {
        $out .= "\t\t\t\"".$row_param["nombre"];
        $out .= "\" => new ApiExposedProperty(\"".$row_param["nombre"]."\", ";
        $out .= ( $row_param["ahuevo"] === "1" ) ? "true" : "false";
        $out .= ", ".$metodo["tipo"].", array( \"".$row_param["tipo"]."\" )),\n";
    }

    $out .= "\t\t);\n\t}\n\n";

    $out .= "\tprotected function GenerateResponse() {";

    $out .= "\t\t\n";

    // ----- ----- ----- -----
    $controller_name = "";
    $method_name     = "";
    $args              = "";


    //controller
    $args_clas = mysql_query("select * from clasificacion where id_clasificacion = ". $metodo["id_clasificacion"] .";");
    $controller_name = mysql_fetch_assoc( $args_clas );
    $controller_name = str_replace( " ", "", ucwords( $controller_name["nombre"] ) );


    $iname = str_replace("api/", "", $metodo["nombre"] );
    $iname = str_replace("/", " ", $iname );
    $iname = str_replace("_", " ", $iname );
    $parts = explode(" ", $iname);
    $iname = "";

    for ($i= sizeof($parts) - 1; $i > 0  ; $i--)
    {
        $iname .= $parts[$i]." ";
    }

    $iname = ucwords($iname);
    $iname = str_replace(" ","", $iname );

    // ----- ----- ----- -----

    $out .= "\t\ttry{\n ";
    $out .= "\t\t$"."this->response = ". $controller_name . "Controller::". $iname ."( \n ";
    $out .= "\t\t\t\n";
    $out .= "\t\t\t\n";

        //argumentos
        $args_params = mysql_query("select * from argumento where id_metodo = ". $metodo["id_metodo"] ." order by ahuevo desc, nombre;");

        while(($row_param = mysql_fetch_assoc( $args_params )) != null )
        {
            if($row_param["tipo"] == "json"){
                $out .= "\t\t\tisset($"."_".$metodo["tipo"]."['".$row_param["nombre"]."'] ) ? json_decode($"."_".$metodo["tipo"]."['".$row_param["nombre"]."']) : null,\n";

            }else{
                /* */
                if($row_param["ahuevo"] == "0") {

                    /* ES OPCIONAL */
                    $foo = false;

                    if(strlen($row_param["defaults"]) == 0){
                        $foo = true;
                        $_params = " \"\"";
                    }

                    if($row_param["defaults"] === "null"){
                        $foo = true;
                        $_params = " null";
                    }

                    if($row_param["defaults"] === "\"\""){
                        $foo = true;
                        $_params = " \"\"";
                    }



                    if(!$foo){
                        if( ($row_param["tipo"] == "bool") || ($row_param["tipo"] == "int")){
                            $_params = " " . $row_param["defaults"] . " ";

                        }else{
                            $_params = " \"" . $row_param["defaults"] . "\"";

                        }
                    }
                    $out .= "\t\t\tisset($"."_".$metodo["tipo"]."['".$row_param["nombre"]."'] ) ? $"."_".$metodo["tipo"]."['".$row_param["nombre"]."'] : ". $_params .",\n";
                }else{
                    $out .= "\t\t\tisset($"."_".$metodo["tipo"]."['".$row_param["nombre"]."'] ) ? $"."_".$metodo["tipo"]."['".$row_param["nombre"]."'] : null,\n";
                }


            }

        }
        $out = substr( $out, 0, -2 );

    $out .= "\n\t\t\t\n";
    $out .= "\t\t\t);\n";
    $out .= "\t\t}catch(Exception $"."e){\n ";
    //$out .= "\t\t\tthrow new ApiException( $e->getMessage() );\n ";
    $out .= "\t\t\t//Logger::error($"."e);\n";
    $out .= "\t\t\tthrow new ApiException( $"."this->error_dispatcher->invalidDatabaseOperation( $"."e->getMessage() ) );\n";

    $out .= "\t\t}\n ";
    $out .= "\t}\n";


    $out .= "  }\n";
    $out .= "  \n";
    $out .= "  \n";

    return $out;

}

################################################################################
      ####   ####  #    # ##### #####   ####  #      #      ###### #####   ####
     #    # #    # ##   #   #   #    # #    # #      #      #      #    # #
     #      #    # # #  #   #   #    # #    # #      #      #####  #    #  ####
     #      #    # #  # #   #   #####  #    # #      #      #      #####       #
     #    # #    # #   ##   #   #   #  #    # #      #      #      #   #  #    #
      ####   ####  #    #   #   #    #  ####  ###### ###### ###### #    #  ####
################################################################################
    static function write_controller( $clasificacion )
    {

        $nombre = str_replace(" ","", ucwords( $clasificacion["nombre"] ));

        $out =     "<?php\n";
        $out .= "require_once(\"interfaces/".$nombre.".interface.php\");\n";
        $out .=    "/**\n";
        $out .= "  *\n";
        $out .= "  *\n";
        $out .= "  *\n";
        $out .= "  **/\n";
        $out .= "    \n";
        $out .= "  class ". $nombre ."Controller implements I" . $nombre . "{\n";
        $out .= "  \n";

        $argsq = mysql_query("select * from metodo where id_clasificacion = ". $clasificacion["id_clasificacion"] .";");

        while(($m = mysql_fetch_assoc($argsq)) != null)
        {
            $out .= "\t\n";
            $out .=    "\t/**\n";
            $out .= "\t*\n";
            $out .= "\t*" . utf8_decode(strip_tags($m["descripcion"])) . "\n";
            $out .= "\t*\n";

            //---------
            //  PARAMETROS
            //---------

            $params = "";

            $args_params = mysql_query("select * from argumento where id_metodo = ". $m["id_metodo"] ." order by ahuevo desc, nombre;");

            while(($row_param = mysql_fetch_assoc( $args_params )) != null )
            {
                $out .= "      * @param ". $row_param["nombre"] ." ". $row_param["tipo"] ." ". strip_tags($row_param["descripcion"]) ."\n";

                $params .= "\n\t\t$" . $row_param["nombre"] ;

                if($row_param["ahuevo"] == "0") {

                    /* ES OPCIONAL */
                    $foo = false;

                    if(strlen($row_param["defaults"]) == 0){
                        $foo = true;
                        $params .= " = \"\"";
                    }

                    if($row_param["defaults"] === "null"){
                        $foo = true;
                        $params .= " = null";
                    }

                    if($row_param["defaults"] === "\"\""){
                        $foo = true;
                        $params .= " = \"\"";
                    }



                    if(!$foo){
                        if( ($row_param["tipo"] == "bool") || ($row_param["tipo"] == "int")){
                            $params .= " =  " . $row_param["defaults"] . " ";

                        }else{
                            $params .= " = \"" . $row_param["defaults"] . "\"";

                        }
                    }
                }

                $params .=  ", ";
            }

            $params = substr( $params, 0, -2 );


            $respuesta_out = "";

            $returns_query = mysql_query("select * from respuesta where id_metodo = ". $m["id_metodo"] .";");

            while(($row_respuesta = mysql_fetch_assoc( $returns_query )) != null )
            {
                $out .= "      * @return ". $row_respuesta["nombre"] ." ". $row_respuesta["tipo"] ." ". $row_respuesta["descripcion"] ."\n";

                //$respuesta_out .= "\n\t\t$" . $row_respuesta["nombre"] . ", ";
            }

            $out .= "      **/\n";

            $iname = str_replace("api/", "", $m["nombre"] );
            $iname = str_replace("/", " ", $iname );
            $iname = str_replace("_", " ", $iname );
            $parts = explode(" ", $iname);
            $iname = "";

            for ($i= sizeof($parts) - 1; $i > 0  ; $i--)
            {
                $iname .= $parts[$i]." ";
            }

            $iname = ucwords($iname);
            $iname = str_replace(" ","", $iname );

            $out .= "\tpublic static function " . $iname . "\n\t(".$params."\n\t)\n\t{";
            $out .= "  \n";
            $out .= "  \n";
            $out .= "  \n";
            $out .= "\t}\n";
        }

        $out .= "  }\n";

        return $out;
    }


################################################################################
             #  #    # ##### ###### #####  ######   ##    ####  ######
             #  ##   #   #   #      #    # #       #  #  #    # #
             #  # #  #   #   #####  #    # #####  #    # #      #####
             #  #  # #   #   #      #####  #      ###### #      #
             #  #   ##   #   #      #   #  #      #    # #    # #
             #  #    #   #   ###### #    # #      #    #  ####  ######
################################################################################
    static function write_controller_interface($clasificacion)
    {

        $nombre = str_replace(" ","", ucwords( $clasificacion["nombre"] ));

        $out =     "<?php\n";

        $out .=    "/**\n";
        $out .= "  *\n";
        $out .= "  *\n";
        $out .= "  *\n";
        $out .= "  **/\n";
        $out .= "    \n";
        $out .= "  interface I". $nombre ." {\n";
        $out .= "  \n";

        $argsq = mysql_query("select * from metodo where id_clasificacion = ". $clasificacion["id_clasificacion"] ." order by nombre;");

        while(($m = mysql_fetch_assoc($argsq)) != null)
        {
            $out .= "  \n";
            $out .=    "    /**\n";
            $out .= "      *\n";
            $out .= "      *" . utf8_decode(strip_tags($m["descripcion"])) . "\n";
            $out .= "      *\n";

            //---------
            //  PARAMETROS
            //---------

            $params = "";

            $args_params = mysql_query("select * from argumento where id_metodo = ". $m["id_metodo"] ." order by ahuevo desc, nombre;");

            while(($row_param = mysql_fetch_assoc( $args_params )) != null )
            {
                $out .= "      * @param ". $row_param["nombre"] ." ". $row_param["tipo"] ." ". strip_tags($row_param["descripcion"]) ."\n";

                $params .= "\n\t\t$" . $row_param["nombre"] ;
                if($row_param["ahuevo"] == "0") {
                    /* ES OPCIONAL */
                    $foo = false;

                    if(strlen($row_param["defaults"]) == 0){
                        $foo = true;
                        $params .= " = \"\"";
                    }

                    if($row_param["defaults"] === "null"){
                        $foo = true;
                        $params .= " = null";
                    }

                    if($row_param["defaults"] === "\"\""){
                        $foo = true;
                        $params .= " = \"\"";
                    }



                    if(!$foo){
                        if( ($row_param["tipo"] == "bool") || ($row_param["tipo"] == "int")){
                            $params .= " =  " . $row_param["defaults"] . " ";

                        }else{
                            $params .= " = \"" . $row_param["defaults"] . "\"";

                        }
                    }
                }
                $params .=  ", ";
            }

            $params = substr( $params, 0, -2 );


            $respuesta_out = "";

            $returns_query = mysql_query("select * from respuesta where id_metodo = ". $m["id_metodo"] ." ;");

            while(($row_respuesta = mysql_fetch_assoc( $returns_query )) != null )
            {
                $out .= "      * @return ". $row_respuesta["nombre"] ." ". $row_respuesta["tipo"] ." ". strip_tags($row_respuesta["descripcion"]) ."\n";

                //$respuesta_out .= "\n\t\t$" . $row_respuesta["nombre"] . ", ";
            }

            $out .= "      **/\n";




            $iname = str_replace("api/", "", $m["nombre"] );
            $iname = str_replace("/", " ", $iname );
            $iname = str_replace("_", " ", $iname );
            $parts = explode(" ", $iname);
            $iname = "";

            for ($i= sizeof($parts) - 1; $i > 0  ; $i--)
            {
                $iname .= $parts[$i]." ";
            }


            $iname = ucwords($iname);
            $iname = str_replace(" ","", $iname );





            $out .= "  static function " . $iname . "\n\t(".$params."\n\t);";
            $out .= "  \n";
            $out .= "  \n";
            $out .= "  \n";
            $out .= "\t\n";
        }

        $out .= "  }\n";

        return $out;
    }
}

################################################################################
                      ####  #####   ##   #####  #####
                     #        #    #  #  #    #   #
                      ####    #   #    # #    #   #
                          #   #   ###### #####    #
                     #    #   #   #    # #   #    #
                      ####    #   #    # #    #   #
################################################################################

if(is_dir(GeneratePhpApi::$tmpPath . "/server/")) {
    delete_directory( GeneratePhpApi::$tmpPath . "/server/" );
}

if(is_dir(GeneratePhpApi::$tmpPath . "/server/")) {
    delete_directory( "tmp/out/server/" );
}

if(is_dir("tmp/builds/api/")) {
    delete_directory("tmp/builds/api/");
}

create_structure(GeneratePhpApi::$tmpPath . "/server/api/");
create_structure(GeneratePhpApi::$tmpPath . "/server/controller/");
create_structure(GeneratePhpApi::$tmpPath . "/server/controller/interfaces/");
create_structure(GeneratePhpApi::$tmpPath . "/builds/api");

$res = mysql_query("select m.* from metodo m,clasificacion c where c.id_proyecto = ".$_GET["project"]." and m.id_clasificacion = c.id_clasificacion order by id_clasificacion") or die(mysql_error());

$_api_file = fopen(GeneratePhpApi::$tmpPath . "/server/api/ApiLoader.php", 'w') or die("can't open ApiLoader");

fwrite( $_api_file, "<?php \n\n");

while(($row = mysql_fetch_assoc($res)) != null ){


    fwrite($_api_file, GeneratePhpApi::write_api_file(  $row ) );
}

fclose($_api_file);


//create controller interface
$query = mysql_query("select * from clasificacion where id_proyecto = ".$_GET["project"].";");

while( ($row = mysql_fetch_assoc( $query )) != null )
{
    echo "php: Procesando " . $row["nombre"] . " ... \n";

    // write the interface
    $iname = str_replace(" ","", ucwords($row["nombre"]));
    $fn = GeneratePhpApi::$tmpPath . "/server/controller/interfaces/" . $iname . ".interface.php";
    $f = fopen($fn, 'w') or die("can't create new interface file");
    fwrite($f, GeneratePhpApi::write_controller_interface(  $row) );
    fclose($f);


    //write the actual controller
    $fn = GeneratePhpApi::$tmpPath . "/server/controller/" . $iname . ".controller.php";
    $f = fopen($fn, 'w') or die("can't open file");

    fwrite($f, GeneratePhpApi::write_controller(  $row) );
    fclose($f);
}

