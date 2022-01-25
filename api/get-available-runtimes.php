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
$allProductDirs = array_values(array_diff(scandir($productsDirPath, SCANDIR_SORT_ASCENDING), array(".", "..")));
if (in_array($productDirName, $allProductDirs)) {
    $productDirPath = join_paths($productsDirPath, $productDirName);
    $productRuns = array_values(array_diff(scandir($productDirPath, SCANDIR_SORT_ASCENDING), array(".", "..")));
    $productRunTimes = [];
    for ($i=0; $i < count($productRuns); $i++) {
        $isInRange = true;
        $runTime = intval(str_replace(".json", "", $productRuns[$i]));
        if (isset($_GET["start"])) {
            if ($runTime < intval($_GET["start"])) {
                $isInRange = false;
            }
        }
        if (isset($_GET["end"])) {
            if ($runTime > intval($_GET["end"])) {
                $isInRange = false;
            }
        }
        if ($isInRange) {
            array_push($productRunTimes, $runTime);
        }
    }
    echo json_encode($productRunTimes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    http_response_code(400);
    echo "Error: productID not found.";
}
?>