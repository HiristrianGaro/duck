<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
include '../common/funzioni.php';
if (isset($_GET['user'])) {
    $searchParam = $_GET['user'];
} else {
    $searchParam = $_SESSION['IndirizzoEmail'];
}
$querySelect = isset($_GET['term']) ? $_GET['term'] : '';


if ($querySelect && $searchParam) {
    
    try {

        $sql = '';
        $params = [];
        $types = '';

        switch ($querySelect) {
            case 'Friends':
                error_log('Query: Friends for ' . $searchParam);
                $sql = "SELECT IdPost, u.Username, IndirizzoEmail, TimestampPubblicazione, Descrizione, PostCity, PostState, PostCountry, fotoprofilo
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
                    WHERE f1.IdPost = p.IdPost
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
                $sql = "SELECT IdPost, u.Username, IndirizzoEmail, TimestampPubblicazione, Descrizione, PostCity, PostState, PostCountry, fotoprofilo
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
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    error_log("Invalid request: Action or session email missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}


list($result, $data) = getQuery($cid, $sql, $params, $types);
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to get post foto']);
    error_log("Failed to get post foto");
    exit();
}

echo toJson($data);

$cid->close();
?>


