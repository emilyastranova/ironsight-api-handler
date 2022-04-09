<?php

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
$queryVal = $_GET["q"];
$indexVal = $_GET["i"];
$startTime = $_GET["start_time"];
$endTime = $_GET["end_time"];
$step = $_GET["step"];

// Make sure command is safe (no shell injection)
$queryVal = escapeshellcmd($queryVal);
$indexVal = escapeshellcmd($indexVal);
$startTime = escapeshellcmd($startTime);
$endTime = escapeshellcmd($endTime);

// Add start time and end time for query
$queryVal = $queryVal . " " . $startTime . " " . $endTime . " " . $step;

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
