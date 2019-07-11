<?php

require_once("../bootstrap.php");
require_once("utils.inc.php");

class GenerateCsharpApi {
    static function build_http_call($metodo) {
        $out = "";
        foreach ($metodo->argumentos as $argumento) {
            if($argumento["ahuevo"] != "0") {
                $out .= "           request[\"".  $argumento["nombre"] ."\"] = ". $argumento["nombre"] .".ToString();\n";
            } else {
                $out .= "           if (" . $argumento["nombre"] . " != null) request[\"".  $argumento["nombre"] ."\"] = ".$argumento["nombre"]."?.ToString();\n";
            }
        }
        return $out;
    }

    static function build_argument_list($metodo) {
        $params = "";
        foreach ($metodo->argumentos as $argumento) {
            $params .= " ";

            // Write the type
            switch ($argumento["tipo"]) {
                case "string": 
                    $params .= "String";
                    break;
                    //case "float": 
                case "json": 
                    $params .= $argumento["tipo"];
                    break;
                case "enum": 
                    $params .= "String /*enum */";
                    break;
                default:
                    $params .= $argumento["tipo"];
                    if($argumento["ahuevo"] == "0") {
                        $params .= "?"; 
                    }
            }

            $params .= " " . $argumento["nombre"] ;

            if($argumento["ahuevo"] != "0") {
                // not optional, no default value
                $params .=  ",";
                continue;
            }

            // Write the default value
            $found = false;

            if(strlen($argumento["defaults"]) == 0){
                $found = true;
                $params .= " = null";
            }

            if($argumento["defaults"] === "null"){
                $found = true;
                $params .= " = null";
            }

            if($argumento["defaults"] === "\"\""){
                $found = true;
                $params .= " = \"\"";
            }

            if ($found) {
                $params .=  ",";
                continue;
            }

            if (($argumento["tipo"] == "bool") || ($argumento["tipo"] == "int")) {
                $params .= " =  " . $argumento["defaults"] . " ";
            } else if (($argumento["tipo"] == "float")) {
                $params .= " =  " . $argumento["defaults"] . "f";

            } else {
                $params .= " = \"" . $argumento["defaults"] . "\"";
            }
            $params .=  ",";
        }

        $params = substr($params, 0, -1);
        return $params;
    }

    static function WriteMethod($metodo, $clasificacion) {

        $className = str_replace(" ","", ucwords($clasificacion->nombre));
        $methodName = ApiNameToMethodName($metodo->nombre);

        $docs = "---\n";
        $docs .= "name: empresas \n";
        $docs .= "position: Developer\n";
        $docs .= "lang: cs\n";
        $docs .= "permalink: /es/docs/cs/". $className ."/".$methodName."/\n";
        $docs .= "locale: es\n";
        $docs .= "apiname: ". $className ."/".$methodName. "\n";
        $docs .= "layout: docs\n";
        $docs .= "---\n";

        $params = GenerateCsharpApi::build_argument_list($metodo);

        $desc = str_replace("\n", "", $metodo->descripcion);
        $desc = str_replace("\r", "", $desc);

        $docs .= "## $methodName ##\n";

        $docs .= "{% highlight ruby %}\n";
        $docs .= "public static Response " . $methodName . "(".$params.")\n";
        $docs .= "{% endhighlight %}\n\n";
        $docs .= "\n";

        $docs .= "### Parametros ###\n\n";
        $docs .= "| Nombre | Tipo | Default | Description |\n";
        foreach ($metodo->argumentos as $argumento) {
            $docs .= "|" . $argumento['nombre'] . "|";

            // Write the type
            switch ($argumento["tipo"]) {
                case "string": 
                    $docs .= "String";
                    break;
                    //case "float": 
                case "json": 
                    $docs .= $argumento["tipo"];
                    break;
                case "enum": 
                    $docs .= "String /*enum */";
                    break;
                default:
                    $docs .= $argumento["tipo"];
                    if($argumento["ahuevo"] == "0") {
                        $docs .= "?"; 
                    }
            }
            $docs .= "|";

            if($argumento["ahuevo"] == "0") {
                $found = false;

                if(strlen($argumento["defaults"]) == 0){
                    $found = true;
                    $docs .= "null";
                }

                if($argumento["defaults"] === "null"){
                    $found = true;
                    $docs .= "null";
                }

                if($argumento["defaults"] === "\"\""){
                    $found = true;
                    $docs .= "\"\"";
                }

                if (!$found) {
                    if (($argumento["tipo"] == "bool") || ($argumento["tipo"] == "int")) {
                        $docs .= $argumento["defaults"] . " ";
                    } else if (($argumento["tipo"] == "float")) {
                        $docs .= $argumento["defaults"] . "f";

                    } else {
                        $docs .= " \"" . $argumento["defaults"] . "\"";
                    }
                }

            }
            $docs .= "|";
            $docs .= $argumento['descripcion']."|\n";
        }

        $documentationFileName = "docs/" . NormalizeApiNameToFile("cs/".$metodo->nombre) . ".md";
        FileWriter::Write($documentationFileName, $docs);
    }
}

$perClasif = function($clasificacion)
{
    print "cs: $clasificacion->nombre\n";

    $nombre = str_replace(" ","", ucwords($clasificacion->nombre));

    $out = "\n";
    $out .= "using System;\n";
    $out .= "using System.Collections.Generic;\n\n";
    $out .= "namespace PosERP {\n\n";
    $out .= "    public class ". $nombre ."Controller {\n\n";

    foreach ($clasificacion->metodos as $metodo)
    {
        $methodName = ApiNameToMethodName($metodo->nombre);

        $params = GenerateCsharpApi::build_argument_list($metodo);

        $out .= "       public static Response " . $methodName . "(".$params.")\n";
        $out .= "       {\n";
        $out .= "           Dictionary<string, string> request = new Dictionary<string, string>();\n";
        $out .= GenerateCsharpApi::build_http_call($metodo);
        $out .= "           return (Response)PosERP.GetInstance().". $metodo->tipo ."(\"". $metodo->nombre  ."\", request);\n";
        $out .= "       }\n";
    }

    $out .= "  }\n";
    $out .= "}\n";

    // Write the actual controller
    $className = str_replace(" ","", ucwords($clasificacion->nombre));
    $controllerFileName = "sdk/cs/" . $className . ".controller.cs";
    FileWriter::Write($controllerFileName, $out);

    // Write the documentation
    $docs = "---\n";
    $docs .= "lang: cs\n";
    $docs .= "permalink: /es/docs/cs/". $className ."/\n";
    $docs .= "locale: es\n";
    $docs .= "apiname: ". $className ."\n";
    $docs .= "layout: docs\n";
    $docs .= "toplevel: true\n";
    $docs .= "class: ". $className ."\n";
    $docs .= "---\n";

    $docs .= "### ". $clasificacion->nombre ." ###\n\n";
    $docs .= "{% highlight ruby %}\n";
    $docs .= " ". $className .";\n";
    $docs .= "{% endhighlight %}\n\n";

    $docs .= "### Metodos ###\n\n";
    $docs .= "| Nombre | Description |\n";
    foreach ($clasificacion->metodos as $metodo)
    {
        $methodName = ApiNameToMethodName($metodo->nombre);

        $params = GenerateCsharpApi::build_argument_list($metodo);

        $desc = str_replace("\n", "", $metodo->descripcion);
        $desc = str_replace("\r", "", $desc);

        $docs .= "[$methodName](/public/es/docs/cs/$clasificacion->nombre/$methodName)";
        $docs .= "|" . $desc ;
        $docs .= "|\n";
    }

    $documentationFileName = "docs/cs" . $className . ".md";
    FileWriter::Write($documentationFileName, $docs);
};

$proj = Project::Load();
$proj->Start($perClasif, ['GenerateCsharpApi', 'WriteMethod']);

