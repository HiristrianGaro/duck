<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php'; // Assicurati che questa includa la funzione getQuery e toJson

// Recupera l'IdPost dalla richiesta GET
$IdPost = isset($_GET['IdPost']) ? $_GET['IdPost'] : '';

// Recupera l'email dell'utente loggato dalla sessione
// Assicurati che la tua sessione contenga l'email dell'utente loggato, ad esempio $_SESSION['user_email']
$loggedInUserEmail = isset($_SESSION['IndirizzoEmail']) ? $_SESSION['IndirizzoEmail'] : null;

// Verifico se l'IdPost è presente e se l'utente è loggato (necessario per i like dell'utente)
if ($IdPost && $loggedInUserEmail !== null) {

    try {
        // Query SQL per recuperare i commenti con i dati dell'utente, conteggio like e stato like dell'utente loggato
        // Usiamo alias per rendere il codice più leggibile (c per commento, u per utente, cl per commentlikes)
        $sql = "SELECT
                    c.IdCommento,
                    c.IdPost,
                    c.TimestampCommento,
                    c.Testo,
                    c.UtenteCommento,
                    u.Username,
                    u.PosizioneFileSystemFotoProf,
                    (SELECT COUNT(*) FROM CommentLikes cl WHERE cl.IdCommento = c.IdCommento) AS comment_likes_count,
                    (SELECT COUNT(*) FROM CommentLikes cl2 WHERE cl2.IdCommento = c.IdCommento AND cl2.UtenteLikeC = ?) AS user_liked_comment
                FROM
                    COMMENTO c
                JOIN
                    UTENTE u ON c.UtenteCommento = u.IndirizzoEmail
                WHERE
                    c.IdPost = ?
                ORDER BY
                    c.TimestampCommento ASC"; // Ordina i commenti per timestamp

        // Parametri per la prepared statement
        // Il primo parametro è l'email dell'utente loggato per la subquery user_liked_comment
        // Il secondo parametro è l'IdPost per filtrare i commenti
        $params = [$loggedInUserEmail, $IdPost];
        $types = 'ss'; // 's' per stringa, due stringhe

        // Esegui la query utilizzando la tua funzione getQuery
        list($result, $data) = getQuery($cid, $sql, $params, $types);

        // Controlla se la query ha avuto successo
        if (!$result) {
            error_log("Failed to get comments for post: " . $IdPost . " - " . mysqli_error($cid)); // Logga l'errore di MySQL
            echo json_encode(['status' => 'error', 'message' => 'Failed to get Comments']);
            exit();
        }

        // Restituisci i dati in formato JSON
        echo toJson($data);
        exit();

    } catch (Exception $e) {
        // Gestione degli errori generali
        error_log("Error fetching comments: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        exit(); // Assicurati di uscire dopo aver inviato la risposta di errore
    }

} else {
    // Gestisci il caso in cui mancano IdPost o l'email dell'utente loggato
    $errorMessage = "Invalid request: ";
    if (!$IdPost) $errorMessage .= "IdPost missing. ";
    if ($loggedInUserEmail === null) $errorMessage .= "User not logged in.";
    error_log($errorMessage);
    echo json_encode(['status' => 'error', 'message' => trim($errorMessage)]);
    exit(); // Assicurati di uscire dopo aver inviato la risposta di errore
}
?>

