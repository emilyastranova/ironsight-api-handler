<?php
function is_JSON()
{
    call_user_func_array('json_decode', func_get_args());
    return (json_last_error() === JSON_ERROR_NONE);
}

header('Content-type: application/json');
$queryVal = $_GET["q"];
$indexVal = $_GET["i"];
//$stripVal = queryVal without the first and last character
$stripVal = substr($queryVal, 1, strlen($queryVal)-2);
if (is_JSON($stripVal)) {
    chdir('/var/www/html/python_scripts');
    $command = 'python3 query.py ' . $queryVal . ' ' . $indexVal;
    $output = shell_exec($command);
    echo $output;
}
else {
    $error = json_last_error_msg();
    echo "Not valid JSON string ($error)";
}
?>
