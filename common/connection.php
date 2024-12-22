<?php

$hostname = 'localhost';
$username = 'root';
$password = '';
$db = 'duck';

try {
    $cid = new mysqli($hostname,$username,$password,$db);
} catch (Exception $e) {
    $cid=null;
}

mysqli_report(MYSQLI_REPORT_ERROR);

?>
