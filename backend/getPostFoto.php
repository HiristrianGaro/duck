<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
$Timestamp = isset($_GET['Timestamp']) ? $_GET['Timestamp'] : '';
$IndirizzoAutore = isset($_GET['IndirizzoAutore']) ? $_GET['IndirizzoAutore'] : '';

$sql = "SELECT IdFoto, NomeFile, PosizioneFile FROM foto
        WHERE TimestampPubblicazione = ? AND AutorePostEmail = ?
        ORDER BY IdFoto DESC;";

$stmt = $cid->prepare($sql);

$stmt->bind_param('ss', $Timestamp, $IndirizzoAutore);

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


