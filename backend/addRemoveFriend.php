<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Retrieve parameters from the request and session
$querySelect = $_GET['action'] ?? '';
$Ricevente = $_GET['ricevente'] ?? '';
$Richiedente = $_SESSION['IndirizzoEmail'] ?? '';
$date = date('Y-m-d H:i:s');

// Log the action and parameters
error_log("Action: $querySelect, Ricevente: $Ricevente, Richiedente: $Richiedente");

if ($querySelect && $Richiedente) {
    try {
        $sql = '';
        $params = [];
        $types = ''; // Type string for parameter binding

        switch ($querySelect) {
            
            case 'Add':
            // Add friend request
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
            // Remove friend request
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
            // Accept friend request
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
            throw new Exception("Invalid action: $querySelect");
        }

        // Prepare and execute the statement
        $stmt = $cid->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare SQL statement: " . $cid->error);
        }

        // Bind parameters
        $stmt->bind_param($types, ...$params);

        // Log the prepared statement
        error_log("Executing SQL: $sql with params: " . implode(', ', $params));

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute SQL statement: " . $stmt->error);
        }

        // Close the statement
        $stmt->close();

        // Success response
        echo json_encode(['status' => 'success', 'message' => 'Action executed successfully']);

    } catch (Exception $e) {
        // Log and return error
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    // No valid action or missing Richiedente
    error_log("Invalid request: Action or session email missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

// Close the connection
$cid->close();
?>
