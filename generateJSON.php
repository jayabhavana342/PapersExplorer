<?php

include("functions/functions.php");

$id = $_POST["id"];
$core = $_POST["core"];

$result = exec("python ./pyCalculation/generateJSON.py $id $core 2>&1", $output, $ret_code);

echo $result;

?>