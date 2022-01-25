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
$productsDirPath = join_paths($relativePathToMetadataDir, "products/");
$productDirName = $_GET["productID"];
$requestedRunTime = $_GET["runtime"];
$productsDir = array_values(array_diff(scandir($productsDirPath, SCANDIR_SORT_ASCENDING), array(".", "..")));
if (in_array($productDirName, $productsDir)) {
    $productDirPath = join_paths($productsDirPath, $productDirName);
    $productRuns = array_values(array_diff(scandir($productDirPath, SCANDIR_SORT_ASCENDING), array(".", "..")));
    if (in_array($requestedRunTime . ".json", $productRuns)) {
        echo file_get_contents(join_paths($productDirPath, $requestedRunTime . ".json"));
    } else {
        http_response_code(400);
        echo "Error: requested run not found.";
    }
} else {
    http_response_code(400);
    echo "Error: productID not found.";
}

?>