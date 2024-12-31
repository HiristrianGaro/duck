<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
$IdPost = isset($_GET['IdPost']) ? $_GET['IdPost'] : '';

$sql = "SELECT IdFoto, NomeFile, PosizioneFile FROM foto
        WHERE IdPost = ?
        ORDER BY IdFoto DESC;";

$stmt = $cid->prepare($sql);

$stmt->bind_param('s', $IdPost);

$stmt->execute();

$result = $stmt->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
error_log("Returning data: " . json_encode($data));

$stmt->close();

$cid->close();
?>


