<?php

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
$queryVal = $_GET["q"];
$indexVal = $_GET["i"];

// Make sure command is safe (no shell injection)
$queryVal = escapeshellcmd($queryVal);
$indexVal = escapeshellcmd($indexVal);

// Check if string contains a '{'}
$isElastic = strpos($queryVal, '{');

chdir('/var/www/ironsight-api-handler/scripts');
if ($isElastic) {
    $command = 'python3 query.py --elastic ' . $queryVal . ' ' . $indexVal;
}
else {
    $command = 'python3 query.py --data ' . $queryVal;
}
$output = shell_exec($command);
echo $output;
?>
