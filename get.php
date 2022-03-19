<?php

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
$queryVal = $_GET["q"];
$indexVal = $_GET["i"];

// Make sure command is safe (no shell injection)
$queryVal = escapeshellcmd($queryVal);
$indexVal = escapeshellcmd($indexVal);

chdir('/var/www/html/python_scripts');
$command = 'python3 query.py ' . $queryVal . ' ' . $indexVal;

$output = shell_exec($command);
echo $output;
?>
