<?php require_once("inc/top.inc.php"); ?>

    <p><a href="download.php?what=api/full_api&out_name=full_api">Descargar Todo</a></p>

<pre><?php

require_once("inc/utils.inc.php");

function NormalizeApiNameToFile($iname) {
    $iname = str_replace("api/", "", $iname);
    $iname = str_replace("/", "_", $iname );

    return $iname;
}

function ApiNameToMethodName($apiName) {
    $iname = str_replace("api/", "", $apiName);
    $iname = str_replace("/", " ", $iname );
    $iname = str_replace("_", " ", $iname );
    $parts = explode(" ", $iname);
    $iname = "";

    for ($i= sizeof($parts) - 1; $i > 0 ; $i--) {
        $iname .= $parts[$i]." ";
    }

    $iname = ucwords($iname);
    return str_replace(" ","", $iname);
}

class FileWriter {
    private static function EnsureDirectoryExists($path) {
        create_structure($path);
    }

    public static function Write($filename, $contents) {
        $filename = "output/tmp/" . $filename;
        self::EnsureDirectoryExists($filename);
        print "Writing: ". str_replace("output/tmp/", "",$filename) . "\n";
        $handle = fopen($filename, 'w') or die("can't open file");
        fwrite($handle, $contents);
        fclose($handle);
    }
}

class Method {
    public $ejemplo_peticion;
    public $ejemplo_respuesta;
    public $id_metodo;
    public $descripcion;
    public $nombre;
    public $tipo;
    public $respuestas = [];
    public $argumentos = [];

    public function Method ($data) {
        $this->ejemplo_peticion = $data['ejemplo_peticion'];
        $this->ejemplo_respuesta = $data['ejemplo_respuesta'];
        $this->id_metodo = $data['id_metodo'];
        $this->nombre = $data['nombre'];
        $this->tipo = $data['tipo'];
        $this->descripcion = utf8_encode($data['descripcion']);

        // nombre, tipo, defaults, descripcion
        $args_params = mysql_query("select * from argumento where id_metodo = ". $this->id_metodo ." order by ahuevo desc, nombre;");
        while (($el = mysql_fetch_assoc($args_params)) != null ) {
            $el['descripcion'] = utf8_encode($el['descripcion']);
            $this->argumentos[] = $el;
        }

        // nombre, tipo, descripcion
        $returns_query = mysql_query("select * from respuesta where id_metodo = ". $this->id_metodo .";");
        while (($el = mysql_fetch_assoc($returns_query)) != null ) {
            $this->respuestas[] = $el;
        }
    }

    public function Start($metodoFn, $clasificacion) {
        $metodoFn($this, $clasificacion);
    }
}

class Clasif {
    public $metodos = [];
    public $nombre;
    public $descripcion;

    public static function Load ($clasificacionArray) {
        $clasificacion = new Clasif();
        $clasificacion->nombre = $clasificacionArray['nombre'];
        $clasificacion->descripcion = $clasificacionArray['descripcion'];
        $argsq = mysql_query("select * from metodo where id_clasificacion = ". $clasificacionArray['id_clasificacion'] .";");
        while (($metodo = mysql_fetch_assoc($argsq)) != null) {
            $clasificacion->metodos[] = new Method($metodo);
        }
        return $clasificacion;
    }

    public function Start($clasificacionFn, $metodoFn) {
        if ($clasificacionFn != null) {
            $clasificacionFn($this);
        }

        if ($metodoFn == null) {
            return;
        }

        foreach ($this->metodos as $metodo) {
            $metodo->Start($metodoFn, $this);
        }
    }
}

class Project {
    public $clasificaciones = [];
    public static function Load() {
        $proj = new Project();
        $query = mysql_query("select * from clasificacion where id_proyecto = 1;");
        while (($row = mysql_fetch_assoc( $query )) != null) {
            $proj->clasificaciones[] = Clasif::Load($row);
        }
        return $proj;
    }

    public function Start($clasificacionFn, $metodoFn) {
        foreach ($this->clasificaciones as $clasificacion) {
            $clasificacion->Start($clasificacionFn, $metodoFn);
        }
    }
}

if (is_dir("output/tmp")){
    delete_directory("output/tmp");
}
mkdir("output/tmp");
unlink("output/full_api.zip");

require_once( "inc/write_docs_api.inc.php" );
require_once( "inc/write_api.inc.php" );
require_once( "inc/write_cs_api.inc.php" );

Zip("output/tmp", "output/full_api.zip");

?></pre><?php

require_once("inc/bottom.inc.php");

