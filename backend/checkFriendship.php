<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
error_log("Received search term: " . $_GET['username']);
$CurrentUser = $_SESSION['Username'];

$UserToCheck = isset($_GET['username']) ? $_GET['username'] : '';

error_log("Current user: " . $CurrentUser);
error_log("User to check: " . $UserToCheck);

if ($UserToCheck === $CurrentUser) {
    error_log('User is self');
    $result = ['DataAccettazione' => 'Self'];
    $data[] = $result;
    echo json_encode($data);
    exit();
}

$sql = "SELECT DataAccettazione, DataRichiesta FROM richiede_amicizia 
        WHERE (UtenteRichiedente = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)
        AND UtenteRicevente = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)) 
        OR (UtenteRichiedente = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)
        AND UtenteRicevente = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?))";

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

