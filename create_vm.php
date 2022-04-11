<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
// $vm_name = $_GET["name"];
// $vm_template = $_GET["template"];
// $vm_username = $_GET["username"];

// Take POST request with JSON data (template_name, user_name, vm_name, is_elastic)
$data = json_decode(file_get_contents('php://input'), true);
$vm_name = $data["vm_name"];
$vm_template = $data["template_name"];
$vm_username = $data["user_name"];
$is_elastic = $data["is_elastic"];

// Make sure command is safe (no shell injection)
$vm_name = escapeshellcmd($vm_name);

chdir('/var/www/ironsight-api-handler/scripts/ironsight_harvester_api/');
$command = 'python3 create_vm.py ' . $vm_name . ' ' . $vm_template . ' ' . $vm_username;
$output = shell_exec($command);
// Get rid of newlines in output
$output = str_replace("\n", ", ", $output);
// If the output contains "Usage", then the command failed
if (strpos($output, 'Usage') !== false) {
    echo '{"status": "fail"}';
}
else {
    echo '{"status": "success", "output": "' . $output . '", "name": "' . $vm_name . '", "template": "' . $vm_template . '", "username": "' . $vm_username . '"}';
}
?>
