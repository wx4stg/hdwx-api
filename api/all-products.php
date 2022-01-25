<?php
include "config.php";
function join_paths() {
    $paths = array();

    foreach (func_get_args() as $arg) {
        if ($arg !== '') { $paths[] = $arg; }
    }

    return preg_replace('#/+#','/',join('/', $paths));
}
header('Content-Type: application/json');
$productTypesDirPath = join_paths($relativePathToMetadataDir, "productTypes/");
$productTypeJsons = array_values(array_diff(scandir($productTypesDirPath, SCANDIR_SORT_ASCENDING), array(".", "..")));
$returnStr = "";
$allProductTypes = [];
for ($i=0; $i < count($productTypeJsons); $i++) {
    $productType = json_decode(file_get_contents(join_paths($productTypesDirPath, $productTypeJsons[$i])), true);
    array_push($allProductTypes, $productType);
}
echo json_encode($allProductTypes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>