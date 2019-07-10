<?php

require_once("../bootstrap.php");
require_once("utils.inc.php");

class GeneratePhpCode {
    static $ApiLoaderFileStarted = false;

    static function write_api_file($metodo, $clasificacion){
        $cname = ucwords( str_replace("/"," ", str_replace("_"," ", $metodo->nombre) ) );
        $cname = str_replace(" ","", $cname) ;

        $out = "\nclass ". $cname ." extends ApiHandler {\n";
        $out .= "\tprotected function DeclareAllowedRoles(){ return BYPASS; }\n";

        if(!$metodo->sesion_valida)
            $out .= "\tprotected function CheckAuthorization() { return; }\n";

        $out .= "\tprotected function GetRequest()\n";
        $out .= "\t{\n";
        $out .= "\t\t$"."this->request = array(\n";

        foreach ($metodo->argumentos as $argumento)
        {
            $out .= "\t\t\t\"".$argumento["nombre"];
            $out .= "\" => new ApiExposedProperty(\"".$argumento["nombre"]."\", ";
            $out .= ( $argumento["ahuevo"] === "1" ) ? "true" : "false";
            $out .= ", ".$metodo->tipo.", array( \"".$argumento["tipo"]."\" )),\n";
        }

        $out .= "\t\t);\n\t}\n\n";
        $out .= "\tprotected function GenerateResponse() {\n";

        $nombreController = str_replace(" ", "", ucwords($clasificacion->nombre));
        $nombreInterfaz = ApiNameToMethodName($metodo->nombre);

        $out .= "\t\ttry{\n";
        $out .= "\t\t\t$"."this->response = ". $nombreController . "Controller::". $nombreInterfaz ."(\n";

        foreach ($metodo->argumentos as $argumento)
        {
            if ($argumento["tipo"] == "json") {
                $out .= "\t\t\t\tisset($"."_".$metodo->tipo."['".$argumento["nombre"]."'] ) ? (!is_string($"."_".$metodo->tipo."['".$argumento["nombre"]."']) ? $"."_".$metodo->tipo."['".$argumento["nombre"]."'] : json_decode($"."_".$metodo->tipo."['".$argumento["nombre"]."'])) : null,\n";

            } else {
                if($argumento["ahuevo"] == "0") {
                    $foo = false;

                    if(strlen($argumento["defaults"]) == 0){
                        $foo = true;
                        $_params = " \"\"";
                    }

                    if($argumento["defaults"] === "null"){
                        $foo = true;
                        $_params = " null";
                    }

                    if($argumento["defaults"] === "\"\""){
                        $foo = true;
                        $_params = " \"\"";
                    }

                    if(!$foo){
                        if( ($argumento["tipo"] == "bool") || ($argumento["tipo"] == "int")){
                            $_params = " " . $argumento["defaults"] . " ";

                        }else{
                            $_params = " \"" . $argumento["defaults"] . "\"";

                        }
                    }
                    $out .= "\t\t\t\tisset($"."_".$metodo->tipo."['".$argumento["nombre"]."'] ) ? $"."_".$metodo->tipo."['".$argumento["nombre"]."'] : ". $_params .",\n";
                }else{
                    $out .= "\t\t\t\tisset($"."_".$metodo->tipo."['".$argumento["nombre"]."'] ) ? $"."_".$metodo->tipo."['".$argumento["nombre"]."'] : null,\n";
                }
            }
        }

        if (count($metodo->argumentos) > 0) {
            $out = substr($out, 0, -2);
            $out .= "\n\t\t\t);\n";
        } else {
            $out = substr($out, 0, -1) . ");\n";
        }

        $out .= "\t\t}catch(Exception $"."e){\n";
        $out .= "\t\t\tthrow new ApiException($"."this->error_dispatcher->invalidDatabaseOperation($"."e->getMessage()));\n";
        $out .= "\t\t}\n";
        $out .= "\t}\n";
        $out .= "}\n";

        return $out;
    }

    static function write_controller($clasificacion)
    {
        $nombre = str_replace(" ","", ucwords($clasificacion->nombre));
        $out = "<?php\n";
        $out .= "require_once(\"interfaces/".$nombre.".interface.php\");\n";
        $out .= "\n";
        $out .= "\tclass ". $nombre ."Controller implements I" . $nombre . "{\n";
        $out .= "\n";

        $argsq = mysql_query("select * from metodo where id_clasificacion = ". $clasificacion->id_clasificacion .";");
        while(($m = mysql_fetch_assoc($argsq)) != null)
        {
            $out .= "\t\n";
            $out .= "\t/**\n";
            $out .= "\t*\n";
            $out .= "\t*" . utf8_decode(strip_tags($m["descripcion"])) . "\n";
            $out .= "\t*\n";

            $params = "";

            $args_params = mysql_query("select * from argumento where id_metodo = ". $m["id_metodo"] ." order by ahuevo desc, nombre;");
            while(($row_param = mysql_fetch_assoc( $args_params )) != null )
            {
                $out .= "\t* @param ". $row_param["nombre"] ." ". $row_param["tipo"] ." ". strip_tags($row_param["descripcion"]) ."\n";
                $params .= "\n\t\t$" . $row_param["nombre"] ;

                if($row_param["ahuevo"] == "0") {
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

            while (($row_respuesta = mysql_fetch_assoc( $returns_query )) != null)
            {
                $out .= "\t* @return ". $row_respuesta["nombre"] ." ". $row_respuesta["tipo"] ." ". $row_respuesta["descripcion"] ."\n";
            }

            $out .= "\t**/\n";

            $iname = ApiNameToMethodName($row_respuesta["nombre"]);

            $out .= "\tpublic static function " . $iname . "\n\t(".$params."\n\t)\n\t{";
            $out .= "\n";
            $out .= "\t}\n";
        }

        $out .= "  }\n";

        return $out;
    }

    static function write_controller_interface($clasificacion)
    {
        $nombre = str_replace(" ","", ucwords($clasificacion->nombre));

        $out = "<?php\n\n";
        $out .= "interface I". $nombre ." {\n";

        foreach ($clasificacion->metodos as $metodo)
        {
            $out .= "\t\n";
            $out .= "\t/**\n";
            $out .= "\t*\n";
            $out .= "\t* " . utf8_decode(strip_tags($metodo->descripcion)) . "\n";
            $out .= "\t*\n";

            $params = "";

            $args_params = mysql_query("select * from argumento where id_metodo = ". $metodo->id_metodo ." order by ahuevo desc, nombre;");
            while(($row_param = mysql_fetch_assoc( $args_params )) != null )
            {
                $out .= "\t* @param ". $row_param["nombre"] ." ". $row_param["tipo"] ." ". strip_tags($row_param["descripcion"]) ."\n";

                $params .= "\n\t\t$" . $row_param["nombre"] ;
                if($row_param["ahuevo"] == "0") {
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
                $params .=  ",";
            }

            $params = substr($params, 0, -1);

            $respuesta_out = "";
            $returns_query = mysql_query("select * from respuesta where id_metodo = ". $metodo->id_metodo ." ;");

            while(($row_respuesta = mysql_fetch_assoc( $returns_query )) != null )
            {
                $out .= "\t* @return ". $row_respuesta["nombre"] ." ". $row_respuesta["tipo"] ." ". strip_tags($row_respuesta["descripcion"]) ."\n";
            }

            $out .= "\t**/\n";

            $nombreMetodo = ApiNameToMethodName($metodo->nombre);

            $out .= "\tstatic function " . $nombreMetodo . "\n\t(".$params."\n\t);\n\n";
        }

        $out .= "  }\n";

        return $out;
    }

    static function GenerateUnittestCode($clasificacion)
    {
        $controllerName = str_replace(" ","", ucwords($clasificacion->nombre));
        $out = "<?php\n";
        $out .= "\n";
        $out .= "\t/*\n";
        $out .= "\t * Clase autogenerada para validar que cada uno de los metodos \n";
        $out .= "\t * dentro de los tests de controllers tienen un test\n";
        $out .= "\t */\n";
        $out .= "\tabstract class ITest". $controllerName ." extends EnterPOSTest {\n";
        $out .= "\n";


        foreach ($clasificacion->metodos as $metodo)
        {
            $out .= "\n";
            $methodName = ApiNameToMethodName($metodo->nombre);

            $out .= "\t\tabstract function prepareCall_" . $methodName . "();\n";
            $out .= "\t\tabstract function verifyResult_" . $methodName . "(\$result, \$arguments);\n";

            $out .= "\t\tfinal function test" . $methodName . "()\n";
            $out .= "\t\t{\n";
            $out .= "\t\t\t\$arguments = \$this->prepareCall_" . $methodName . "();\n";

            $argumentos = "";
            foreach ($metodo->argumentos as $argumento) {
                if($argumento["ahuevo"] == "0") {
                    $argumentos .= "\t\t\t\t\tarray_key_exists('". $argumento["nombre"] ."', \$arguments) ";
                    $argumentos .= "? \$arguments['" . $argumento["nombre"] ."'] : null,\n" ;
                } else {
                    $argumentos .= "\t\t\t\t\t\$arguments['" . $argumento["nombre"] ."'],\n" ;
                }
            }
            $argumentos = substr($argumentos, 0, -2);

            $out .= "\t\t\t\$result = " . $controllerName . "Controller::" . $methodName . "(\n$argumentos);\n";

            foreach ($metodo->respuestas as $respuesta) {
                $out .= "\t\t\t\$this->assertArrayHasKey('". $respuesta["nombre"]  ."', \$result);\n";
                if ($respuesta["tipo"] == 'json') {
                    $out .= "\t\t\t\$this->assertInternalType('array'/*json*/, \$result['". $respuesta["nombre"] ."']);\n";

                } else {
                    $out .= "\t\t\t\$this->assertInternalType('". $respuesta["tipo"]  ."', \$result['". $respuesta["nombre"] ."']);\n";
                }
            }

            $out .= "\t\t\t\$this->verifyResult_" . $methodName . "(\$result, \$arguments);\n";
            $out .= "\t\t}\n";
        }

        $out .= "}\n";

        return $out;
    }

    public static function WriteClass($clasificacion)
    {
        echo "php: Procesando $clasificacion->nombre\n";
        $iname = str_replace(" ","", ucwords($clasificacion->nombre));

        // write the interface
        $fileName = "_php/server/controller/interfaces/" . $iname . ".interface.php";
        FileWriter::Write($fileName, GeneratePhpCode::write_controller_interface($clasificacion));

        // write the controller
        $fileName = "_php/server/controller/" . $iname . ".controller.php";
        FileWriter::Write($fileName, GeneratePhpCode::write_controller($clasificacion));

        // write the controller
        $fileName = "_php/tests/phpunit/interfaces/" . $iname . ".test.interface.php";
        FileWriter::Write($fileName, GeneratePhpCode::GenerateUnittestCode($clasificacion));
    }

    public static function WriteMethod($metodo, $clasificacion)
    {
        $ApiLoaderFileName = "_php/server/api/ApiLoader.php";
        if (!self::$ApiLoaderFileStarted)
        {
            FileWriter::Write($ApiLoaderFileName, "<?php\n");
            self::$ApiLoaderFileStarted = true;
        }

        FileWriter::Append($ApiLoaderFileName, GeneratePhpCode::write_api_file($metodo, $clasificacion));
    }
}

$proj = Project::Load();
$proj->Start(['GeneratePhpCode', 'WriteClass'], ['GeneratePhpCode', 'WriteMethod']);

