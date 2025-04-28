<?php
include "../frontend/footer.php";
include '../errorLogging.php';
include("../common/connection.php");
include("../common/funzioni.php");

session_start();

error_log("Running test.php");
echo json_encode($_SESSION) . "<br>";


$CurrentUser = $_SESSION['IndirizzoEmail'];


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



$stmt = $cid->prepare($sql);

$stmt->bind_param('ss', $CurrentUser, $CurrentUser);

$stmt->execute();

$result = $stmt->get_result();

$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
    echo json_encode($row) . "<br>";
}




//echo json_encode($data);
