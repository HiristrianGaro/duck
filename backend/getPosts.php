<?php
// Setup Iniziale e Inclusione File
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php'; // Assicurati che questo file esista e funzioni
include '../common/connection.php'; // Assicurati che questo file stabilisca $cid
include '../common/funzioni.php'; // Assicurati che contenga getQuery e toJson

// Determinazione dell'Utente di Riferimento
$searchParam = null;
if (isset($_GET['user'])) {
    $searchParam = $_GET['user'];
} elseif (isset($_SESSION['IndirizzoEmail'])) { // Controlla anche se esiste nella sessione
    $searchParam = $_SESSION['IndirizzoEmail'];
}

// Determinazione del Tipo di Query
$querySelect = isset($_GET['term']) ? $_GET['term'] : '';

// Variabili per Query SQL
$sql = '';
$params = [];
$types = '';
$isValidQueryType = false; // Flag per indicare se una query valida è stata selezionata


if ($querySelect && $searchParam !== null) { 

    try {
        switch ($querySelect) {
            case 'Friends':
                error_log('Query: Friends for ' . $searchParam);
                $sql = "SELECT IdPost, u.Username, IndirizzoEmail, TimestampPubblicazione, testo, PostCitta, PostProvincia, PostRegione, PosizioneFileSystemFotoProf
                        FROM Post p
                        JOIN Utente u ON p.Utente = u.IndirizzoEmail
                        JOIN richiede_amicizia r ON (
                            (r.UtenteRichiedente = ? AND r.UtenteRicevente = u.IndirizzoEmail)
                            OR
                            (r.UtenteRicevente = ? AND r.UtenteRichiedente = u.IndirizzoEmail)
                        )
                        WHERE r.DataAccettazione IS NOT NULL
                        ORDER BY p.TimestampPubblicazione DESC;";
                $params = [$searchParam, $searchParam];
                $types = 'ss';
                $isValidQueryType = true;
                break;

            case 'User':
                error_log('Query: User');
                 // Query per post dell'utente specifico (assumendo $searchParam è username qui, come da query originale)
                 // Se $searchParam fosse sempre email, la subquery WHERE p.Utente = (SELECT ...) non servirebbe
                $sql = "SELECT p.*, f.*
                FROM post p
                LEFT JOIN foto f ON f.IdPost = p.IdPost AND f.Ordine = (
                    SELECT MIN(f1.Ordine)
                    FROM foto f1
                    WHERE f1.IdPost = p.IdPost
                )
                WHERE p.Utente = (
                    SELECT IndirizzoEmail
                    FROM utente
                    WHERE username = ?
                )
                ORDER BY p.TimestampPubblicazione DESC;"; // Aggiunto LEFT JOIN per includere post senza foto

                $params = [$searchParam]; // Assumendo $searchParam contiene lo username qui
                $types = 's';
                $isValidQueryType = true;
                break;

            case 'SinglePost':
                error_log('Query: SinglePost');
                // ** CORREZIONE: Query per un singolo post, assumendo ID passato via GET['post_id'] **
                $postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0; // Converti a intero, default 0
                if ($postId > 0) {
                    $sql = "SELECT p.*, u.Username, u.PosizioneFileSystemFotoProf, f.*
                            FROM Post p
                            JOIN Utente u ON p.Utente = u.IndirizzoEmail
                            LEFT JOIN foto f ON f.IdPost = p.IdPost AND f.Ordine = (
                                SELECT MIN(f1.Ordine)
                                FROM foto f1
                                WHERE f1.IdPost = p.IdPost
                            )
                            WHERE p.IdPost = ?;"; // Seleziona per ID post
                    $params = [$postId];
                    $types = 'i'; // ID del post è un intero
                    $isValidQueryType = true;
                } else {
                     // Se post_id non è valido, gestisci come un errore
                     error_log("Error: SinglePost request without valid post_id");
                     echo json_encode(['status' => 'error', 'message' => 'Missing or invalid Post ID']);
                     exit(); // Esci subito
                }
                break;

            default:
                // CORREZIONE: Gestisce esplicitamente il caso di 'term' non valido
                error_log("Invalid request: Unknown query term '" . $querySelect . "'");
                echo json_encode(['status' => 'error', 'message' => 'Invalid query term']);
                exit(); // Esci subito se il term non è valido
        }

    } catch (Exception $e) {
        // Questo catch ora cattura errori DURANTE la selezione/preparazione della query
        error_log("Error during query setup: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Internal server error during query setup']);
        exit(); // Esci in caso di errore nella preparazione
    }

} else {
    // Gestisce il caso in cui manchino term o searchParam
    error_log("Invalid request: Query term or user parameter missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request: Missing parameters']);
    exit(); // Esci subito se i parametri iniziali non sono validi
}


// Esecuzione della Query (solo se una query valida è stata selezionata)
if ($isValidQueryType) { // Esegui solo se $sql, $params, $types sono stati impostati correttamente

    try {
        list($result, $data) = getQuery($cid, $sql, $params, $types);

        if (!$result) {
            error_log("Failed to execute query: " . $sql); // Logga anche la query che ha fallito
            echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve data from database']);
            // Non uscire qui, il catch generale gestirà l'errore se getQuery lancia eccezioni
        } else {
            // Output dei Dati in formato JSON
             echo toJson($data);
        }
    } catch (Exception $e) {
        // Questo catch cattura errori DURANTE l'esecuzione di getQuery
        error_log("Error executing query: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Internal server error during data retrieval']);
    }

} // else for isValidQueryType is implicitly handled by the default case and initial checks exiting

// Chiusura Connessione Database
if ($cid) { // Controlla se la connessione è stata stabilita prima di chiuderla
    $cid->close();
}

?>