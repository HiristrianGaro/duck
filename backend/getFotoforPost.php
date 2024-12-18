<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
$Timestamp = isset($_GET['Timestamp']) ? $_GET['Timestamp'] : '';
$IndirizzoAutore = isset($_GET['IndirizzoAutore']) ? $_GET['IndirizzoAutore'] : '';
// Prepare the SQL statement with placeholders
$sql = "SELECT IdFoto, NomeFile, PosizioneFile FROM foto
        WHERE TimestampPubblicazione = ? AND AutorePostEmail = ?
        ORDER BY IdFoto DESC;";

$stmt = $cid->prepare($sql);

// Bind parameters for all fields
$stmt->bind_param('ss', $Timestamp, $IndirizzoAutore);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch data and store it in an array
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);
error_log("Returning data: " . json_encode($data));

// Close the statement
$stmt->close();

// Close the connection
$cid->close();
?>


