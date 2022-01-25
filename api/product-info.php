<?php
include "config.php";
function join_paths() {
    $paths = array();
    foreach (func_get_args() as $arg) {
        if ($arg !== '') {
            $paths[] = $arg;
        }
    }
    return preg_replace('#/+#','/',join('/', $paths));
}

header('Content-Type: application/json');
$filename = $_GET["productID"] . ".json";
$productJsons = array_values(array_diff(scandir($relativePathToMetadataDir, SCANDIR_SORT_ASCENDING), array(".", "..")));
if (in_array($filename, $productJsons)) {
    echo file_get_contents(join_paths($relativePathToMetadataDir, $filename));
} else {
    http_response_code(400);
    echo "Error: productID not found.";
}
?>