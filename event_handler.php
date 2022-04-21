<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Take POST request with JSON data
$data = json_decode(file_get_contents('php://input'), true);
$encoded_data = base64_encode(json_encode($data));

chdir('/var/www/ironsight-api-handler/scripts/ironsight_harvester_api/');
$command = 'python3 event_handler.py ' . $encoded_data;
$output = shell_exec($command);
// Get rid of newlines in output
$output = str_replace("\n", ", ", $output);
// If the output contains "Usage", then the command failed
if (strpos($output, 'Usage') !== false || strpos($output, 'Error') !== false) {
    echo '{"status": "fail"}';
}
else {
    echo '{"status": "success", "raw_query": "' . $command . '" ,"output": "' . $output . '"}';
}
?>
