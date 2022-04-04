<?php 

    header('Content-type: application/json');
    chdir('/var/www/html/scripts');
    $command = escapeshellcmd('python3 query.py --elastic');
    $output = shell_exec($command);
    echo $output;

?>
