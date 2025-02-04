<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include '../common/connection.php';

$IdPost = isset($_GET['IdPost']) ? $_GET['IdPost'] : '';
$querySelect = isset($_GET['term']) ? $_GET['term'] : '';


if ($querySelect && $IdPost) {
    
    try {

        $sql = '';
        $params = [];
        $types = '';

        switch ($querySelect) {
            case 'getComments':
                error_log('Query: getComments for IdPost: ' . $IdPost);
                $sql = "SELECT * FROM commento WHERE IdPost = ? ORDER BY TimestampPubblicazione DESC;";


                $params = [$IdPost];
                $types = 's';
                break;

            default:
            error_log('Query: Default');
                $data = ['ciaoo'];
                break;
        }
    } catch (Exception $e) {

        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    error_log("Invalid request: Action or session email missing");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}


header('Content-Type: application/json');
echo json_encode($data);
error_log("Returning data: " . json_encode($data));
exit();
?>


