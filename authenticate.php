<?php
header('Content-type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Access-Control-Request-Method, Access-Control-Request-Headers');
$data = json_decode(file_get_contents('php://input'), true);
$username = $data["username"];
$password = $data["password"];

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

// Table is users in ironsight database
$sql = "SELECT password FROM ironsight.users WHERE user_name = '$username'";
$result = $conn->query($sql);

// If user exists, check password
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // if ($row["password"] == $password) {
        //     echo '{"status": "success"}';
        // }
        // Check against SHA-512 hash, 4096 iterations
        if (password_verify($password, $row["password"])) {
            echo '{"status": "success"}';
        }
        else {
            echo '{"status": "wrong_password"}';
        }
    }
}
else {
    echo '{"status": "user_not_found"}';
}
