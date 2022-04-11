<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
$data = json_decode(file_get_contents('php://input'), true);
$timestamp = $data["timestamp"];
$username = $data["username"];
$activity = $data["activity"];

// Check against database
$sql_server = "ssh.rellis.dev";
$sql_username = "ironsight";
$sql_password = "ironsight";
$dbname = "ironsight";

// Create connection
$conn = new mysqli($sql_server, $sql_username, $sql_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die('{"status": "no_connection"}');
}

// If request is GET, return log data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Table is logs in ironsight database
    $sql = "SELECT * FROM ironsight.log_data";
    $result = $conn->query($sql);
    // Convert to JSON
    $json = array();
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $json[] = $row;
        }
    }
    echo json_encode($json);
}

// If request is POST, insert log data
// INSERT INTO `ironsight`.`log_data` (`log_id`, `log_timestamp`, `log_username`, `log_activity`) VALUES ('2', '2022-04-02 00:02:00', 'tyler_harrison', '[Ironsight] Logged out');
// Will need to account for the auto-incrementing id
else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Table is logs in ironsight database
    $sql = "INSERT INTO `ironsight`.`log_data` (`log_timestamp`, `log_username`, `log_activity`) VALUES ('$timestamp', '$username', '$activity')";
    $result = $conn->query($sql);
    echo '{"status": "success", "timestamp": "' . $timestamp . '", "username": "' . $username . '", "activity": "' . $activity . '"}';
}