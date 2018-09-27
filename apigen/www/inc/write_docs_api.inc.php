<?php

require_once("../server/bootstrap.php");
require_once("utils.inc.php");

$perMethod = function($metodo, $clasificacion)
{
    $out = "---\n";
    $out .= "lang: http\n";
    $out .= "permalink: /docs/http/". $metodo->nombre ."/\n";
    $out .= "title: HTTP - ". $metodo->nombre ."/\n";
    $out .= "apiname: docs/http/". $metodo->nombre ."\n";
    $out .= "layout: docs\n";
    $out .= "---\n";

    // Clasificacion y nombre de metodo
    $out .= "## " . $clasificacion->nombre . " ##\n\n";
    $out .= "" . utf8_encode($clasificacion->descripcion) . "\n";
    $out .= "## " . $metodo->tipo . " " . $metodo->nombre ."  ##\n\n";

    // Argumentos
    $out .= "### Argumentos  ###\n\n";
    $out .= "| Nombre | Tipo | Default | Description |\n";
    foreach ($metodo->argumentos as $argumento) {
        $out .= utf8_encode($argumento["nombre"]) . "|";
        $out .= utf8_encode($argumento["tipo"]) . "|";
        $out .= utf8_encode($argumento["defaults"]) . "|";
        $out .= utf8_encode($argumento["descripcion"]) . "|\n";

        if ($argumento["ahuevo"] != "0") {
            // not optional, no default value
            continue;
        }
    }

    $out .= "\n";

    // Respuesta
    $out .= "### Respuesta  ###\n\n";
    $out .= "| Nombre | Tipo | Description |\n";
    foreach ($metodo->respuestas as $respuesta) {
        $out .= "|";
        $out .= utf8_encode($respuesta["nombre"]) . "|";
        $out .= utf8_encode($respuesta["tipo"]) . "|";
        $out .= utf8_encode($respuesta["descripcion"]) . "|\n";
    }

    $out .= "\n\n";

    $out .= "### Ejemplo solicitud ###\n\n";
    $out .= "{% highlight ruby %}\n";
    $out .= utf8_encode($metodo->ejemplo_peticion) . "\n";
    $out .= "{% endhighlight %}\n\n";

    $out .= "### Ejemplo respuesta ###\n\n";
    $out .= "{% highlight ruby %}\n";
    $out .= utf8_encode($metodo->ejemplo_respuesta) . "\n";
    $out .= "{% endhighlight %}\n\n";

    // Write the actual controller
    $filename = "http/".$metodo->nombre .".md";
    $filename = str_replace("/","_", ucwords($filename));

    FileWriter::Write("docs/" . $filename, $out);
};

$perClasif = function($clasificacion)
{
    $className = str_replace(" ","", ucwords($clasificacion->nombre));

    // Write the documentation
    $docs = "---\n";
    $docs .= "lang: http\n";
    $docs .= "permalink: /docs/http/". $className ."/\n";
    $docs .= "apiname: ". $className ."\n";
    $docs .= "layout: docs\n";
    $docs .= "toplevel: true\n";
    $docs .= "class: ". $className ."\n";
    $docs .= "---\n";

    $docs .= "| metodo | Description |\n";
    foreach ($clasificacion->metodos as $metodo)
    {
        $desc = str_replace("\n", "", $metodo->descripcion);
        $desc = str_replace("\r", "", $desc);

        $docs .= "[$metodo->nombre](/es/docs/http/$metodo->nombre)";
        $docs .= "|" . $desc ;
        $docs .= "|\n";
    }

    $documentationFileName = "docs/http_" . $className . ".md";
    FileWriter::Write($documentationFileName, $docs);
};

$proj = Project::Load();
$proj->Start($perClasif, $perMethod);
