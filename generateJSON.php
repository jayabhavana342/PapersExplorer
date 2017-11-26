<?php

include("functions/functions.php");

$id = $_POST["id"];
$core = $_POST["core"];

$result = json_encode(exec("python ./pyCalculation/generateJSON.py $id $core 2>&1", $output, $ret_code), true, JSON_PRETTY_PRINT);

echo $result;

?>