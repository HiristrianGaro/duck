<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';

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
                    WHEN RA.RichiedenteEmail = ? THEN RA.RiceventeEmail
                    ELSE RA.RichiedenteEmail 
                END AS FriendEmail
            FROM RichiedeAmicizia RA
            WHERE RA.Accettazione = 'Accettato'
              AND ? IN (RA.RichiedenteEmail, RA.RiceventeEmail)
        ),
        MutualFriends AS (
            SELECT
                CASE 
                    WHEN RA.RichiedenteEmail != F.FriendEmail THEN RA.RichiedenteEmail
                    ELSE RA.RiceventeEmail
                END AS SuggestedFriend,
                COUNT(*) AS MutualFriendCount
            FROM Friends F
            JOIN RichiedeAmicizia RA 
                ON (F.FriendEmail = RA.RichiedenteEmail OR F.FriendEmail = RA.RiceventeEmail)
            WHERE RA.Accettazione = 'Accettato'
              AND ? NOT IN (RA.RichiedenteEmail, RA.RiceventeEmail)
            GROUP BY SuggestedFriend
        ),
        PopularUsers AS (
            SELECT 
                u.IndirizzoEmail AS Email,
                u.Username,
                u.FotoProfilo,
                COUNT(CASE 
                    WHEN ra.RichiedenteEmail = u.IndirizzoEmail THEN ra.RiceventeEmail
                    WHEN ra.RiceventeEmail = u.IndirizzoEmail THEN ra.RichiedenteEmail
                END) AS FriendCount
            FROM Utente u
            LEFT JOIN RichiedeAmicizia ra 
                ON u.IndirizzoEmail IN (ra.RichiedenteEmail, ra.RiceventeEmail)
            WHERE ra.Accettazione = 'Accettato'
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
            FROM RichiedeAmicizia RA
            WHERE (
                (RA.RichiedenteEmail = ? AND RA.RiceventeEmail = MF.SuggestedFriend) OR
                (RA.RichiedenteEmail = MF.SuggestedFriend AND RA.RiceventeEmail = ?)
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
            SELECT DISTINCT u2.Username, u2.FotoProfilo
            FROM Utente u1
            JOIN RichiedeAmicizia ra 
                ON u1.IndirizzoEmail = ra.RichiedenteEmail OR u1.IndirizzoEmail = ra.RiceventeEmail
            JOIN Utente u2 
                ON (
                    (ra.RichiedenteEmail = u2.IndirizzoEmail AND ra.RiceventeEmail = u1.IndirizzoEmail)
                    OR (ra.RiceventeEmail = u2.IndirizzoEmail AND ra.RichiedenteEmail = u1.IndirizzoEmail)
                )
            WHERE u1.IndirizzoEmail = ?
              AND ra.Accettazione = 'Accettato'
              AND u2.IndirizzoEmail != ?;
        ";
        $types = 'ss';
        $params = [$searchParam, $searchParam];

    } elseif ($querySelect === 'FriendRequests') {
        $sql = "
            SELECT 
                u.Username, 
                u.FotoProfilo
            FROM 
                RichiedeAmicizia ra
            JOIN 
                Utente u ON ra.RichiedenteEmail = u.IndirizzoEmail
            WHERE 
                ra.RiceventeEmail = ?
                AND ra.Accettazione = 'In Attesa';
        ";
        $types = 's';
        $params = [$searchParam];

    } else {

        throw new Exception("Invalid query type: $querySelect");
    }


    $stmt = $cid->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $cid->error);
    }

    $stmt->bind_param($types, ...$params);

    if (!$stmt->execute()) {
        throw new Exception("Failed to execute SQL statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);

    $stmt->close();

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

$cid->close();
?>
