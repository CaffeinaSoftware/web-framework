<?php

require_once("../server/bootstrap.php");
require_once("utils.php");

$tmpPath = "tmp/cs/out/";

################################################################################
      ####   ####  #    # ##### #####   ####  #      #      ###### #####   ####
     #    # #    # ##   #   #   #    # #    # #      #      #      #    # #
     #      #    # # #  #   #   #    # #    # #      #      #####  #    #  ####
     #      #    # #  # #   #   #####  #    # #      #      #      #####       #
     #    # #    # #   ##   #   #   #  #    # #      #      #      #   #  #    #
      ####   ####  #    #   #   #    #  ####  ###### ###### ###### #    #  ####
################################################################################
function write_controller( $clasificacion ) {

    $nombre = str_replace(" ","", ucwords( $clasificacion["nombre"] ));

    $out = "\n";
    $out .= "\n";
    $out .= "using System;\n";
    $out .= "using System.Collections.Generic;\n";

    $out .= "\n";
    $out .= "namespace PosERP {\n";
    $out .= "\n";
    $out .= "    public class ". $nombre ."Controller {\n";
    $out .= "\n";

    $argsq = mysql_query("select * from metodo where id_clasificacion = ". $clasificacion["id_clasificacion"] .";");
    while (($m = mysql_fetch_assoc($argsq)) != null)
    {
        $out .= "\n";
        //$out .= "  /**\n";
        //$out .= "    * " . utf8_decode(strip_tags($m["descripcion"])) . "\n";
        //$out .= "    *\n";

        $params = build_argument_list($m["id_metodo"]);

        $respuesta_out = "";
        $returns_query = mysql_query("select * from respuesta where id_metodo = ". $m["id_metodo"] .";");

        //while(($row_respuesta = mysql_fetch_assoc( $returns_query )) != null ) {
        //    $out .= "      * @return ". $row_respuesta["nombre"] ." ". $row_respuesta["tipo"] ." ". $row_respuesta["descripcion"] ."\n";
        //}
        //$out .= "    **/\n";

        $iname = str_replace("api/", "", $m["nombre"] );
        $iname = str_replace("/", " ", $iname );
        $iname = str_replace("_", " ", $iname );
        $parts = explode(" ", $iname);
        $iname = "";

        for ($i= sizeof($parts) - 1; $i > 0  ; $i--) {
            $iname .= $parts[$i]." ";
        }

        $iname = ucwords($iname);
        $iname = str_replace(" ","", $iname );

        $out .= "       public static Response " . $iname . "(".$params.")\n";
        $out .= "       {\n";

        $out .= "           Dictionary<string, string> request = new Dictionary<string, string>();\n";

        $out .= build_http_call($m["id_metodo"]);

        //$out .= "       request["usuario"] = "1";");

        $out .= "           return (Response)PosERP.GetInstance().". $m["tipo"] ."(\"". $m["nombre"]  ."\", request);\n";
        $out .= "       }\n";
    }
    $out .= "  }\n";

    $out .= "}\n";

    return $out;
}

function build_http_call($metodo)
{
    $out = "";
    $args_params = mysql_query("select * from argumento where id_metodo = ". $metodo ." order by ahuevo desc, nombre;");

    while (($row_param = mysql_fetch_assoc( $args_params )) != null ) {


        if($row_param["ahuevo"] != "0") {

            $out .= "           request[\"".  $row_param["nombre"] ."\"] = ". $row_param["nombre"] .".ToString();\n";
        } else {
            $out .= "           if (" . $row_param["nombre"] . " != null) request[\"".  $row_param["nombre"] ."\"] = ".$row_param["nombre"]."?.ToString();\n";
        }
    }

    return $out;
}

function build_argument_list($metodo)
{
    $params = "";
    $args_params = mysql_query("select * from argumento where id_metodo = ". $metodo ." order by ahuevo desc, nombre;");


    while (($row_param = mysql_fetch_assoc( $args_params )) != null ) {
        //$out .= "      * @param ". $row_param["nombre"] ." ". $row_param["tipo"] ." ". strip_tags($row_param["descripcion"]) ."\n";
        $params .= " ";

        // Write the type
        switch ($row_param["tipo"]) {
            case "string": 
                $params .= "String";
                break;
                //case "float": 
            case "json": 
                $params .= $row_param["tipo"];
                break;
            case "enum": 
                $params .= "String /*enum */";
                break;
            default:
                $params .= $row_param["tipo"];
                if($row_param["ahuevo"] == "0") {
                    $params .= "?"; 
                }
        }


        $params .= " " . $row_param["nombre"] ;

        if($row_param["ahuevo"] != "0") {
            // not optional, no default value
            $params .=  ",";
            continue;
        }

        //
        // Write the default value
        //
        $found = false;

        if(strlen($row_param["defaults"]) == 0){
            $found = true;
            $params .= " = null";
        }

        if($row_param["defaults"] === "null"){
            $found = true;
            $params .= " = null";
        }

        if($row_param["defaults"] === "\"\""){
            $found = true;
            $params .= " = \"\"";
        }


        if ($found) {
            $params .=  ",";
            continue;
        }

        if (($row_param["tipo"] == "bool") || ($row_param["tipo"] == "int")) {
            $params .= " =  " . $row_param["defaults"] . " ";
        } else if (($row_param["tipo"] == "float")) {
            $params .= " =  " . $row_param["defaults"] . "f";

        } else {
            $params .= " = \"" . $row_param["defaults"] . "\"";
        }
        $params .=  ",";
    }

    $params = substr($params, 0, -1);
    return $params;

}



################################################################################
                      ####  #####   ##   #####  #####
                     #        #    #  #  #    #   #
                      ####    #   #    # #    #   #
                          #   #   ###### #####    #
                     #    #   #   #    # #   #    #
                      ####    #   #    # #    #   #
################################################################################

if(is_dir($tmpPath . "/server/")){
    delete_directory( $tmpPath . "/server/" );
}

create_structure($tmpPath . "/server/api/");
create_structure($tmpPath . "/server/controller/");
create_structure($tmpPath . "/server/controller/interfaces/");

//create controller interface
$query = mysql_query("select * from clasificacion where id_proyecto = ".$_GET["project"].";");

while (($row = mysql_fetch_assoc( $query )) != null) {

    // write the interface
    $iname = str_replace(" ","", ucwords($row["nombre"]));
    //$fn = $tmpPath . "/server/controller/interfaces/" . $iname . ".interface.cs";
    //$f = fopen($fn, 'w') or die("can't create new interface file");
    //fwrite($f, write_controller_interface($row) );
    //fclose($f);


    //write the actual controller
    $fn = $tmpPath . "/server/controller/" . $iname . ".controller.cs";
    $f = fopen($fn, 'w') or die("can't open file");

    fwrite($f, write_controller($row));
    fclose($f);
}

//ok al terminar enzipar todo en builds
Zip($tmpPath . "/server/", "tmp/builds/api/full_api.zip");


?></pre>
