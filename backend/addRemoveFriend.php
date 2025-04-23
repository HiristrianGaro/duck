<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/funzioni.php';
include '../common/connection.php';

$querySelect = $_GET['action'] ?? '';
$Ricevente = $_GET['ricevente'] ?? '';
$Richiedente = $_SESSION['IndirizzoEmail'] ?? '';
$date = date('Y-m-d H:i:s');


if ($querySelect && $Richiedente) {
    $sql = '';
    $params = [];
    $types = '';

    switch ($querySelect) {
        
        case 'Add':
        $sql = "
            INSERT INTO richiede_amicizia (UtenteRichiedente, UtenteRicevente, DataRichiesta, DataAccettazione)
            VALUES (?, (SELECT IndirizzoEmail FROM Utente WHERE Username = ?), ?, NULL);
        ";
        $types = 'sss';
        $params = [$Richiedente, $Ricevente, $date];
        break;

        case 'Remove':
        $sql = "
            DELETE FROM richiede_amicizia
            WHERE ((UtenteRichiedente = ?
                AND UtenteRicevente = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?))
               OR (UtenteRichiedente = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)
                   AND UtenteRicevente = ?));
        ";
        $types = 'ssss';
        $params = [$Richiedente, $Ricevente, $Ricevente, $Richiedente];
        break;

        case 'Accept':
        $sql = "
            UPDATE richiede_amicizia ra
            SET ra.DataAccettazione = ?
            WHERE ra.UtenteRichiedente = (SELECT u.IndirizzoEmail FROM Utente u WHERE u.Username = ?)
              AND ra.UtenteRicevente = ?;";
              
        $types = 'sss';
        $params = [$date, $Ricevente, $Richiedente];
        break;

        default:
        error_log("Invalid action: $querySelect");
        echo json_encode(['status' => 'error', 'message' => "Invalid action: $querySelect"]);
        exit;
    }

} else {
    // No valid action or missing Richiedente
    error_log("Invalid request: Action or session email missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

list($result, $data) = getQuery($cid, $sql, $params, $types);
if (!$result) {
    echo json_encode(['status' => 'error', 'action' => $querySelect, 'message' => 'Failed to Friends actions']);
    exit();
}

// Close the connection
$cid->close();
?>
