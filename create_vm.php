<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Take POST request with JSON data (template_name, user_name, vm_name, is_elastic)
$data = json_decode(file_get_contents('php://input'), true);
$vm_name = $data["vm_name"];
$vm_template = $data["template_name"];
$vm_username = $data["user_name"];
$template_override = $data["template_override"];

// Make sure command is safe (no shell injection)
$vm_name = escapeshellcmd($vm_name);
$vm_template = escapeshellcmd($vm_template);
$vm_username = escapeshellcmd($vm_username);
$template_override = escapeshellcmd($template_override);

chdir('/var/www/ironsight-api-handler/scripts/ironsight_harvester_api/');
$command = 'python3 create_vm.py ' . $vm_name . ' ' . $vm_template . ' ' . $vm_username . ' ' . $template_override;
$output = shell_exec($command);
// Get rid of newlines in output
$output = str_replace("\n", ", ", $output);
// If the output contains "Usage", then the command failed
if (strpos($output, 'Usage') !== false) {
    echo '{"status": "fail"}';
}
else {
    echo '{"status": "success", "raw_query": "' . $command . '" ,"output": "' . $output . '", "name": "' . $vm_name . '", "template": "' . $vm_template . '", "username": "' . $vm_username . '"}';
}
?>
