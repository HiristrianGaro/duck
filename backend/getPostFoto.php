<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';
$IdPost = isset($_GET['IdPost']) ? $_GET['IdPost'] : '';
$attr = array($IdPost);

$sql = "SELECT PosizioneFileSystem FROM foto
        WHERE IdPost = ?";

list($result, $data) = getQuery($cid, $sql, $attr, 's');
if ($result) {
    header('Content-Type: application/json');
    echo json_encode($data);
    error_log("Returning data: " . json_encode($data));
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get post foto']);
    error_log("Failed to get post foto");
}

$cid->close();
?>


