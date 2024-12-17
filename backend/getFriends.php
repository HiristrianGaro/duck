<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
$searchParam = $_SESSION['Username'];

$querySelect = isset($_GET['term']) ? $_GET['term'] : '';
// Prepare the SQL statement with placeholders

if ($querySelect == 'SuggestedFriends') {
    $sql = "WITH UserEmail AS (
    -- Step 1: Retrieve the email of the current user based on their username
    SELECT IndirizzoEmail
    FROM Utente
    WHERE Username = ?
),
Friends AS (
    -- Step 2: Get all friends of the current user
    SELECT 
        CASE 
            WHEN RA.RichiedenteEmail = (SELECT IndirizzoEmail FROM UserEmail) THEN RA.RiceventeEmail
            ELSE RA.RichiedenteEmail 
        END AS FriendEmail
    FROM RichiedeAmicizia RA
    WHERE RA.Accettazione = 'Accettato'
      AND ((SELECT IndirizzoEmail FROM UserEmail) IN (RA.RichiedenteEmail, RA.RiceventeEmail))
),
MutualFriends AS (
    -- Step 3: Find mutual friends (shared friends) between the current user's friends and other users
    SELECT
        CASE 
            WHEN RA.RichiedenteEmail != F.FriendEmail THEN RA.RichiedenteEmail
            ELSE RA.RiceventeEmail
        END AS SuggestedFriend,
        F.FriendEmail AS MutualFriend
    FROM Friends F
    JOIN RichiedeAmicizia RA 
        ON (F.FriendEmail = RA.RichiedenteEmail OR F.FriendEmail = RA.RiceventeEmail)
    WHERE RA.Accettazione = 'Accettato'
      AND (SELECT IndirizzoEmail FROM UserEmail) NOT IN (RA.RichiedenteEmail, RA.RiceventeEmail)
),
AggregatedSuggestions AS (
    -- Step 4: Aggregate suggestions, count mutual friends, and group names
    SELECT 
        SuggestedFriend AS SuggestedEmail,
        COUNT(DISTINCT MutualFriend) AS FriendsCount,
        GROUP_CONCAT(DISTINCT MutualFriend) AS MutualFriends
    FROM MutualFriends
    GROUP BY SuggestedFriend
)
-- Step 5: Exclude users who are already friends
SELECT 
    ASG.SuggestedEmail,
    ASG.FriendsCount,
    ASG.MutualFriends
FROM AggregatedSuggestions ASG
LEFT JOIN Friends F ON ASG.SuggestedEmail = F.FriendEmail
WHERE F.FriendEmail IS NULL
ORDER BY ASG.FriendsCount DESC
LIMIT 5;";

} else if ($querySelect == 'CurrentFriends') {
    $sql = "SELECT DISTINCT u2.username, u2.fotoprofilo
    FROM Utente u1
    JOIN RichiedeAmicizia ra ON u1.IndirizzoEmail = ra.RichiedenteEmail OR u1.IndirizzoEmail = ra.RiceventeEmail
    JOIN Utente u2 ON (
        (ra.RichiedenteEmail = u2.IndirizzoEmail AND ra.RiceventeEmail = u1.IndirizzoEmail)
        OR (ra.RiceventeEmail = u2.IndirizzoEmail AND ra.RichiedenteEmail = u1.IndirizzoEmail)
    )
    WHERE u1.Username = ?
    AND ra.Accettazione = 'Accettato'
    AND u2.Username != ?;";
} else {
    echo "Invalid query type";
}

$stmt = $cid->prepare($sql);

if ($querySelect == 'SuggestedFriends') {
    $stmt->bind_param('s', $searchParam);
} else {
    $stmt->bind_param('ss', $searchParam, $searchParam);
}

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

// Close the connection
$cid->close();
?>
