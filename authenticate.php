<!-- Receive username and password from POST body -->
<?php
$username = $_POST["username"];
$password = $_POST["password"];

// Check against database
$sql_server = "ssh.rellis.dev";
$sql_username = "ironsight";
$sql_password = "ironsight";
$dbname = "ironsight";

// Create connection
$conn = new mysqli($sql_server, $sql_username, $sql_password, $dbname);

// Table is users in ironsight database
$sql = "SELECT password FROM users WHERE username = '$username'";
$result = $conn->query($sql);

// If user exists, check password
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if ($row["password"] == $password) {
            echo "true";
        }
        else {
            echo "false";
        }
    }
}
else {
    echo "false";
}
