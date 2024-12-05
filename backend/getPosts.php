<?php
include '../config.php';
include '../common/connection.php';
error_log("Received search term: " . $_GET['term']);
// Retrieve the search term from the AJAX request
$searchTerm = isset($_GET['term']) ? $_GET['term'] : '';
echo $_GET['term'];
if ($searchTerm !== '') {
    // Prepare the SQL statement with placeholders
    $sql = "SELECT * FROM post NATURAL JOIN foto 
            WHERE AutorePostEmail = (SELECT IndirizzoEmail FROM utente WHERE username = ?) 
            ORDER BY DataDiPubblicazione DESC";

    $stmt = $cid->prepare($sql);

    // Bind parameters for all fields
    $stmt->bind_param('s', $searchTerm);

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
} else {
    // No search term provided
    echo json_encode([]);
}

// Close the connection
$cid->close();
?>