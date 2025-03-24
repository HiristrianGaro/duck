<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';

$searchParam = $_SESSION['IndirizzoEmail'] ?? '';
$querySelect = $_GET['term'] ?? '';

if (!$searchParam) {
    error_log("Session parameter 'IndirizzoEmail' is missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid session data']);
    exit;
}

error_log("Action: $querySelect, SearchParam: $searchParam");

try {
    $sql = '';
    $params = [];
    $types = '';

    if ($querySelect == 'SuggestedFriends') {
        $sql = "
        WITH Friends AS (
            SELECT 
                CASE 
                    WHEN RA.UtenteRichiedente = ? THEN RA.UtenteRicevente
                    ELSE RA.UtenteRichiedente 
                END AS FriendEmail
            FROM richiede_amicizia RA
            WHERE RA.DataRichiesta IS NOT NULL OR RA.DataAccettazione IS NOT NULL
              AND ? IN (RA.UtenteRichiedente, RA.UtenteRicevente)
        ),
        MutualFriends AS (
            SELECT
                CASE 
                    WHEN RA.UtenteRichiedente != F.FriendEmail THEN RA.UtenteRichiedente
                    ELSE RA.UtenteRicevente
                END AS SuggestedFriend,
                COUNT(*) AS MutualFriendCount
            FROM Friends F
            JOIN richiede_amicizia RA 
                ON (F.FriendEmail = RA.UtenteRichiedente OR F.FriendEmail = RA.UtenteRicevente)
            WHERE RA.DataAccettazione IS NOT NULL
              AND ? NOT IN (RA.UtenteRichiedente, RA.UtenteRicevente)
            GROUP BY SuggestedFriend
        ),
        PopularUsers AS (
            SELECT 
                u.IndirizzoEmail AS Email,
                u.Username,
                u.PosizioneFileSystemFotoProf,
                COUNT(CASE 
                    WHEN ra.UtenteRichiedente = u.IndirizzoEmail THEN ra.UtenteRicevente
                    WHEN ra.UtenteRicevente = u.IndirizzoEmail THEN ra.UtenteRichiedente
                END) AS FriendCount
            FROM Utente u
            LEFT JOIN richiede_amicizia ra 
                ON u.IndirizzoEmail IN (ra.UtenteRichiedente, ra.UtenteRicevente)
            WHERE RA.DataAccettazione IS NOT NULL
            GROUP BY u.IndirizzoEmail
            ORDER BY FriendCount DESC
            LIMIT 8
        )
        SELECT 
            MF.SuggestedFriend AS Email,
            U.Username,
            MF.MutualFriendCount
        FROM MutualFriends MF
        JOIN Utente U ON MF.SuggestedFriend = U.IndirizzoEmail
        WHERE NOT EXISTS (
            SELECT 1
            FROM richiede_amicizia RA
            WHERE (
                (RA.UtenteRichiedente = ? AND RA.UtenteRicevente = MF.SuggestedFriend) OR
                (RA.UtenteRichiedente = MF.SuggestedFriend AND RA.UtenteRicevente = ?)
            )
        )
        UNION
        SELECT 
            P.Email,
            P.Username,
            P.FriendCount AS MutualFriendCount
        FROM PopularUsers P
        WHERE P.Email NOT IN (
            SELECT FriendEmail 
            FROM Friends
        )
        AND P.Email != ?;
        ";    
        $types = 'ssssss';
        $params = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam];

    } elseif ($querySelect === 'CurrentFriends') {
        $sql = "
            SELECT DISTINCT u2.Username, u2.PosizioneFileSystemFotoProf
            FROM Utente u1
            JOIN richiede_amicizia ra 
                ON u1.IndirizzoEmail = ra.UtenteRichiedente OR u1.IndirizzoEmail = ra.UtenteRicevente
            JOIN Utente u2 
                ON (
                    (ra.UtenteRichiedente = u2.IndirizzoEmail AND ra.UtenteRicevente = u1.IndirizzoEmail)
                    OR (ra.UtenteRicevente = u2.IndirizzoEmail AND ra.UtenteRichiedente = u1.IndirizzoEmail)
                )
            WHERE u1.IndirizzoEmail = ?
              AND RA.DataAccettazione IS NOT NULL
              AND u2.IndirizzoEmail != ?;
        ";
        $types = 'ss';
        $params = [$searchParam, $searchParam];

    } elseif ($querySelect === 'FriendRequests') {
        $sql = "
            SELECT 
                u.Username, 
                u.PosizioneFileSystemFotoProf
            FROM 
                richiede_amicizia ra
            JOIN 
                Utente u ON ra.UtenteRichiedente = u.IndirizzoEmail
            WHERE 
                ra.UtenteRicevente = ?
                AND RA.DataRichiesta IS NOT NULL
                AND RA.DataAccettazione IS NULL
        ";
        $types = 's';
        $params = [$searchParam];

    } else {

        throw new Exception("Invalid query type: $querySelect");
    }

    list($result, $data) = getQuery($cid, $sql, $params, $types);


    header('Content-Type: application/json');
    echo json_encode($data);


} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$cid->close();
?>
