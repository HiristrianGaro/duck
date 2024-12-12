<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
$searchParam = $_SESSION['Username'];
// Prepare the SQL statement with placeholders
$sql = "SELECT p.*, u.Username, f.NomeFile, f.PosizioneFile
        FROM Post p
        JOIN Utente u ON p.AutorePostEmail = u.IndirizzoEmail
        JOIN RichiedeAmicizia r ON (
            (r.RichiedenteEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?) AND r.RiceventeEmail = u.IndirizzoEmail)
            OR
            (r.RiceventeEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?) AND r.RichiedenteEmail = u.IndirizzoEmail)
        )
        JOIN Foto f ON p.OraDiPubblicazione = f.OraDiPubblicazione AND p.DataDiPubblicazione = f.DataDiPubblicazione AND p.AutorePostEmail = f.AutorePostEmail
        WHERE r.Accettazione = 'Accettato'
        AND u.Username != ?
        ORDER BY p.DataDiPubblicazione DESC, p.OraDiPubblicazione DESC;";

$stmt = $cid->prepare($sql);

// Bind parameters for all fields
$stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);

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
