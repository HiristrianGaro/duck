<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
$searchParam = $_SESSION['Username'];
// Prepare the SQL statement with placeholders
$sql = "SELECT u2.Username,
COUNT(DISTINCT mf.IndirizzoEmail) AS MutualFriendsCount,
GROUP_CONCAT(DISTINCT mf.Username ORDER BY mf.Username SEPARATOR ', ') AS MutualFriends
FROM Utente u1
JOIN RichiedeAmicizia ra1 ON (u1.IndirizzoEmail = ra1.RichiedenteEmail OR u1.IndirizzoEmail = ra1.RiceventeEmail)
JOIN RichiedeAmicizia ra2 ON (
(ra1.RichiedenteEmail = ra2.RiceventeEmail OR ra1.RiceventeEmail = ra2.RichiedenteEmail)
AND ra2.Accettazione = 'Accettato'
AND ra2.RichiedenteEmail != u1.IndirizzoEmail
AND ra2.RiceventeEmail != u1.IndirizzoEmail
)
JOIN Utente u2 ON (ra2.RichiedenteEmail = u2.IndirizzoEmail OR ra2.RiceventeEmail = u2.IndirizzoEmail)
JOIN Utente mf ON (
(mf.IndirizzoEmail = ra1.RichiedenteEmail OR mf.IndirizzoEmail = ra1.RiceventeEmail)
AND mf.IndirizzoEmail != u2.IndirizzoEmail
AND mf.IndirizzoEmail != u1.IndirizzoEmail
)
WHERE u1.Username = 'HiristrianGaro'
AND u2.Username != 'HiristrianGaro'
AND u2.IndirizzoEmail NOT IN (
SELECT DISTINCT r2.RiceventeEmail
FROM RichiedeAmicizia r2
WHERE r2.RichiedenteEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = 'HiristrianGaro')
OR r2.RiceventeEmail = (SELECT IndirizzoEmail FROM Utente WHERE Username = 'HiristrianGaro')
)
GROUP BY u2.Username
ORDER BY MutualFriendsCount DESC, u2.Username ASC;





";

$stmt = $cid->prepare($sql);

// Bind parameters for all fields
$stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);

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
