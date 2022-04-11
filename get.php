<?php

header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
$queryVal = $_GET["q"];
$indexVal = $_GET["i"];
$startTime = $_GET["start_time"];
$endTime = $_GET["end_time"];
$step = $_GET["step"];
$lab_num = $_GET["lab_num"];
$student_name = $_GET["student_name"];

// Make sure command is safe (no shell injection)
$queryVal = escapeshellcmd($queryVal);
$indexVal = escapeshellcmd($indexVal);
$startTime = escapeshellcmd($startTime);
$endTime = escapeshellcmd($endTime);
$step = escapeshellcmd($step);
$lab_num = escapeshellcmd($lab_num);
$student_name = escapeshellcmd($student_name);

// Add start time and end time for query
$queryVal = $queryVal . " " . $startTime . " " . $endTime . " " . $step;

// Add lab number for query
$queryVal = $queryVal . " " . $lab_num . " " . $student_name;

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
