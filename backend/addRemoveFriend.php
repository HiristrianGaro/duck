<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';
error_log("Received search term: " . $_GET['term']);
// Retrieve the search term from the AJAX request
$querySelect = isset($_GET['term']) ? $_GET['term'] : '';
$Ricevente = isset($_GET['ricevente']) ? $_GET['ricevente'] : '';
$Richiedente = $_SESSION['Username'];

if ($querySelect !== '') {
    if ($querySelect == 'AddFriend') {
        $sql = "SET @username1 = ?;
        SET @username2 = ?;

        SET @userEmail1 = (SELECT IndirizzoEmail FROM Utente WHERE Username = @username1);
        SET @userEmail2 = (SELECT IndirizzoEmail FROM Utente WHERE Username = @username2);

        INSERT INTO RichiedeAmicizia (RichiedenteEmail, RiceventeEmail, DataRichiesta, Accettazione)
        VALUES (@userEmail1, @userEmail2, '2023-10-10', 'In Attesa');";
    } else if ($querySelect == 'RemoveFriend') {
        $sql = "SET @username1 = ?;
        SET @username2 = ?;

        SET @userEmail1 = (SELECT IndirizzoEmail FROM Utente WHERE Username = @username1);
        SET @userEmail2 = (SELECT IndirizzoEmail FROM Utente WHERE Username = @username2);

        DELETE FROM RichiedeAmicizia
        WHERE (RichiedenteEmail = @userEmail1 AND RiceventeEmail = @userEmail2)
        OR (RichiedenteEmail = @userEmail2 AND RiceventeEmail = @userEmail1);";
    } else {
        // Invalid search term
        echo 'Invalid search term';
    }
    // Prepare the SQL statement with placeholders
    
    $stmt = $cid->prepare($sql);

    // Bind parameters for all fields
    $stmt->bind_param('ss', $Richiedente, $Ricevente);


    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch data and store it in an array

    echo $result;

    $stmt->close();
} else {
    // No search term provided
    echo 'No search term provided';
}

// Close the connection
$cid->close();
?>