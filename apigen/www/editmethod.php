<?php

require_once("api/api.php");

require_once("inc/top.inc.php");

require_once("inc/methodform.inc.php");

$detalles = ApiGenApi::MethodDetails($_GET["m"]);

if (($json = json_encode($detalles, JSON_PARTIAL_OUTPUT_ON_ERROR  )) === false)
{
    switch(json_last_error()) {
        case JSON_BIGINT_AS_STRING: echo "JSON_BIGINT_AS_STRING"; break;
        case JSON_ERROR_CTRL_CHAR: echo "JSON_ERROR_CTRL_CHAR"; break;
        case JSON_ERROR_DEPTH: echo "JSON_ERROR_DEPTH"; break;
        case JSON_ERROR_INF_OR_NAN: echo "JSON_ERROR_INF_OR_NAN"; break;
        case JSON_ERROR_NONE: echo "JSON_ERROR_NONE"; break;
        case JSON_ERROR_RECURSION: echo "JSON_ERROR_RECURSION"; break;
        case JSON_ERROR_STATE_MISMATCH: echo "JSON_ERROR_STATE_MISMATCH"; break;
        case JSON_ERROR_SYNTAX: echo "JSON_ERROR_SYNTAX"; break;
        case JSON_ERROR_UNSUPPORTED_TYPE: echo "JSON_ERROR_UNSUPPORTED_TYPE"; break;
        case JSON_ERROR_UTF8: echo "JSON_ERROR_UTF8"; break;
        case JSON_FORCE_OBJECT: echo "JSON_FORCE_OBJECT"; break;
        case JSON_HEX_AMP: echo "JSON_HEX_AMP"; break;
        case JSON_HEX_APOS: echo "JSON_HEX_APOS"; break;
        case JSON_HEX_QUOT: echo "JSON_HEX_QUOT"; break;
        case JSON_HEX_TAG: echo "JSON_HEX_TAG"; break;
        case JSON_NUMERIC_CHECK: echo "JSON_NUMERIC_CHECK"; break;
        case JSON_OBJECT_AS_ARRAY: echo "JSON_OBJECT_AS_ARRAY"; break;
        case JSON_PARTIAL_OUTPUT_ON_ERROR: echo "JSON_PARTIAL_OUTPUT_ON_ERROR"; break;
        case JSON_PRESERVE_ZERO_FRACTION: echo "JSON_PRESERVE_ZERO_FRACTION"; break;
        case JSON_PRETTY_PRINT: echo "JSON_PRETTY_PRINT"; break;
        case JSON_UNESCAPED_LINE_TERMINATORS: echo "JSON_UNESCAPED_LINE_TERMINATORS"; break;
        case JSON_UNESCAPED_SLASHES: echo "JSON_UNESCAPED_SLASHES"; break;
        case JSON_UNESCAPED_UNICODE: echo "JSON_UNESCAPED_UNICODE"; break;
        //case JSON_ERROR_INVALID_PROPERTY_NAME: echo "JSON_ERROR_INVALID_PROPERTY_NAME"; break;
        //case JSON_ERROR_UTF16: echo "JSON_ERROR_UTF16"; break;
    }

}

echo "<script>SetValuesInEditForm(" . $json . ");</script>";;

?> <button id="saveedit">Save</button> <?php

require_once("inc/bottom.inc.php");


