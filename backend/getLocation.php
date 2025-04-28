<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';

$query = $_GET['query'] ?? '';
$Region = $_GET['Region'] ?? '';
$Province = $_GET['Province'] ?? '';

if (!$query) {
    error_log("Query parameter is missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid query parameter']);
    exit;
}

error_log("Action: $query");

try {
    $sql = '';
    $params = [];
    $types = '';

    if ($query == 'Regions') {
        $sql = "SELECT DISTINCT Regione FROM LOCATION WHERE Regione IS NOT NULL";
        $Result = mysqli_query($cid, $sql);
        if (!$Result) {
            error_log("Error executing query: " . mysqli_error($cid));
            echo json_encode(['status' => 'error', 'message' => 'Database query error']);
            exit;
        }
        echo json_encode(mysqli_fetch_all($Result, MYSQLI_ASSOC));
        exit;

    } elseif ($query === 'Provinces') {
        error_log("Region: $Region");
        $sql = "Select DISTINCT Provincia FROM LOCATION WHERE Regione = ?";
        $types = 's';
        $params = [$Region];

    }elseif ($query === 'Cities') {
        error_log("Region: $Region");
        error_log("Province: $Province");
        $sql = "SELECT DISTINCT Citta FROM LOCATION WHERE Regione = ? AND Provincia = ?";
        $types = 'ss';
        $params = [$Region, $Province];

    } else {

        throw new Exception("Invalid query type: $query");
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
