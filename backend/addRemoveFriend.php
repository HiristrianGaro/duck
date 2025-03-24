<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
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
            INSERT INTO RichiedeAmicizia (RichiedenteEmail, RiceventeEmail, DataRichiesta, Accettazione)
            VALUES (
            ?,
            (SELECT IndirizzoEmail FROM Utente WHERE Username = ?),
            ?,
            'In Attesa'
            );
        ";
        $types = 'sss';
        $params = [$Richiedente, $Ricevente, $date];
        break;

        case 'Remove':
        $sql = "
            DELETE FROM RichiedeAmicizia
            WHERE ((RichiedenteEmail = ?
                AND RiceventeEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?))
               OR (RichiedenteEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = ?)
                   AND RiceventeEmail = ?));
        ";
        $types = 'ssss';
        $params = [$Richiedente, $Ricevente, $Ricevente, $Richiedente];
        break;

        case 'Accept':
        $sql = "
            UPDATE RichiedeAmicizia ra
            SET ra.Accettazione = 'Accettato',
            ra.DataRichiesta = ?
            WHERE ra.RichiedenteEmail = (SELECT u.IndirizzoEmail FROM Utente u WHERE u.Username = ?)
              AND ra.RiceventeEmail = ?;
        ";
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
    echo json_encode(['status' => 'error', 'message' => 'Failed to get post foto']);
    error_log("Failed to get post foto");
    exit();
}

// Close the connection
$cid->close();
?>
