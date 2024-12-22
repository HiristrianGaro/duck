<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
error_log("Received search term: " . $_GET['username']);
$CurrentUser = $_SESSION['Username'];

$UserToCheck = isset($_GET['username']) ? $_GET['username'] : '';

if ($UserToCheck === $CurrentUser) {
    $result = ['Accettazione' => 'Self'];
    $data[] = $result;
    echo json_encode($data);
    exit();
}

$sql = "SELECT Accettazione FROM RichiedeAmicizia 
        WHERE (RichiedenteEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)
        AND RiceventeEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)) 
        OR (RichiedenteEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)
        AND RiceventeEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?))";

$stmt = $cid->prepare($sql);

$stmt->bind_param('ssss', $CurrentUser, $UserToCheck, $UserToCheck, $CurrentUser);

$stmt->execute();

$result = $stmt->get_result();

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
error_log("Returning data: " . json_encode($data));

?>

