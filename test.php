<?php 

    header('Content-type: application/json');
    chdir('/var/www/html/pythonScripts');
    $command = escapeshellcmd('python3 query.py');
    $output = shell_exec($command);
    echo $output;

?>