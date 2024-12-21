<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
if (isset($_GET['user'])) {
    $searchParam = $_GET['user'];
} else {
    $searchParam = $_SESSION['IndirizzoEmail'];
}
$querySelect = isset($_GET['term']) ? $_GET['term'] : '';

// Prepare the SQL statement with placeholders

if ($querySelect && $searchParam) {
    
    try {

        $sql = '';
        $params = [];
        $types = '';

        switch ($querySelect) {
            case 'Friends':
                error_log('Query: Friends for ' . $searchParam);
                $sql = "SELECT IdPost, u.Username, IndirizzoEmail, TimestampPubblicazione, Descrizione, NomeCitta, StatoCitta, ProvinciaCitta, fotoprofilo
                        FROM Post p
                        JOIN Utente u ON p.AutorePostEmail = u.IndirizzoEmail
                        JOIN RichiedeAmicizia r ON (
                            (r.RichiedenteEmail = ? AND r.RiceventeEmail = u.IndirizzoEmail)
                            OR
                            (r.RiceventeEmail = ? AND r.RichiedenteEmail = u.IndirizzoEmail)
                        )
                        WHERE r.Accettazione = 'Accettato'
                        ORDER BY p.TimestampPubblicazione DESC;";


                $params = [$searchParam, $searchParam];
                $types = 'ss';
                break;

                        

            case 'User':
                error_log('Query: User');
                $sql = "SELECT p.*, f.*
                FROM post p
                JOIN foto f ON f.IdFoto = (
                    SELECT MIN(f1.IdFoto)
                    FROM foto f1
                    WHERE f1.TimestampPubblicazione = p.TimestampPubblicazione
                      AND f1.AutorePostEmail = p.AutorePostEmail
                )
                WHERE p.AutorePostEmail = (
                    SELECT IndirizzoEmail 
                    FROM utente 
                    WHERE username = ?
                )
                ORDER BY p.TimestampPubblicazione DESC;
                ";

                $params = [$searchParam];
                $types = 's';
                break;
            case 'SinglePost':
                $sql = "SELECT IdPost, u.Username, IndirizzoEmail, TimestampPubblicazione, Descrizione, NomeCitta, StatoCitta, ProvinciaCitta, fotoprofilo
                        FROM Post p
                        JOIN Utente u ON p.AutorePostEmail = u.IndirizzoEmail
                        JOIN RichiedeAmicizia r ON (
                            (r.RichiedenteEmail = ? AND r.RiceventeEmail = u.IndirizzoEmail)
                            OR
                            (r.RiceventeEmail = ? AND r.RichiedenteEmail = u.IndirizzoEmail)
                        )
                        WHERE r.Accettazione = 'Accettato'
                        ORDER BY p.TimestampPubblicazione DESC;";

            default:
            error_log('Query: Default');
                $data = ['ciaoo'];
                break;
        }
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


$stmt = $cid->prepare($sql);

        // Bind parameters for all fields
$stmt->bind_param($types, ...$params);

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
    exit();
?>


