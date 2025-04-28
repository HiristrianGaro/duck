<?php

if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';

$IndirizzoEmail = isset($_SESSION['IndirizzoEmail']) ? $_SESSION['IndirizzoEmail'] : '';


$sql = "SELECT * FROM utente WHERE IndirizzoEmail = ?";
$params = [$IndirizzoEmail];
$types = 's';

list($result, $data) = getQuery($cid, $sql, $params, $types);
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get Comments']);
    error_log("Failed to get userdata");
    exit();
}
echo toJson($data);
exit();
