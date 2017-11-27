<?php


include("functions/functions.php");

$description = $_POST["description"];
$id = $_POST["id"];
$core = $_POST["core"];
$description = escapeshellarg(addslashes($description));
echo "<script>console.log($description" . " " . "$id" . " " . "$core);</script>";
$result = exec("python ./pyCalculation/updateAnnotation.py $description $id $core 2>&1", $output, $ret_code);
//echo $result;
echo "<pre>";
foreach ($output as $val) {
    echo $val;
    echo "<br>";
}
echo "</pre>";
//echo "<script>console.log($result);</script>";
?>