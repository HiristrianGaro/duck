<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../errorLogging.php';
include 'imagecrop.php';
include '../common/connection.php';
include 'addCity.php';

$files_to_upload = $_FILES['filesToUpload'];

if (empty($files_to_upload['name'][0])) {
    error_log("No files uploaded");
    echo json_encode([
        'status' => 'error',
        'message' => 'No files uploaded!',
    ]);
    exit();
}
error_log(POST_DIR);

function createPost() {
    global $cid; // Ensure $cid is accessible

    error_log('Creating Post');
    $Utente = $_SESSION['IndirizzoEmail'];
    $Description = isset($_POST['Description']) ? $_POST['Description'] : '';
    $timestamp = date('Y-m-d H:i:s');
    $NomeCitta = isset($_POST['City']) ? $_POST['City'] : '';
    $StatoCitta = isset($_POST['Country']) ? $_POST['Country'] : '';
    $ProvinciaCitta = isset($_POST['State']) ? $_POST['State'] : '';
    error_log(print_r($_POST, true));
    error_log('Creating Post');
    error_log($Utente);
    error_log($timestamp);
    error_log($NomeCitta);
    error_log($StatoCitta);
    error_log($ProvinciaCitta);
    error_log($Description);
    if ($StatoCitta != NULL) {
        error_log(checkCity($StatoCitta, $ProvinciaCitta, $NomeCitta));
    } else {
        error_log('City not set');
    }

    

    $sql = "INSERT INTO Post (Utente, testo, TimestampPubblicazione, Citta, Provincia, Regione) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $cid->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $cid->error);
        die('Database error');
    }

    $stmt->bind_param('ssssss', $Utente, $Description, $timestamp, $NomeCitta, $ProvinciaCitta, $StatoCitta);

    error_log('Executing Query');
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        die('Failed to execute query');
    }

    $stmt->close();
    error_log('Post Created');
    error_log('Adding Foto to Post');
    addFotoPost($Utente, $timestamp);
}


//Verrà utilizzata per ottenere l'Id del post appena creato, sostituendo TimestampPubblicazione e Utente
//quando cambieremo la struttura di foto rimuovendo questi ultimi

function getPostId($Utente, $timestamp) {
    global $cid;

    $sql = "SELECT IdPost FROM Post WHERE Utente = ? AND TimestampPubblicazione = ?";
    $stmt = $cid->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $cid->error);
        return false;
    }

    $stmt->bind_param('ss', $Utente, $timestamp);

    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['IdPost'];
    }

    return false;
}

function addFotoPost($Utente, $timestamp) {
    $IdPost = getPostId($Utente, $timestamp);
    global $cid;

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_FILES['filesToUpload'])) {
            $files_to_upload = $_FILES['filesToUpload'];

            $errorArray = [];
            for ($i = 0; $i < count($files_to_upload['name']); $i++) {
                $originalName = basename($files_to_upload['name'][$i]);
                $sanitizedFileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
                $DBdestination = LOCAL_POST_DIR . '/' . $sanitizedFileName;
                $destination = POST_DIR . '/' . $sanitizedFileName;

                if (is_uploaded_file($files_to_upload['tmp_name'][$i])) {
                    $mime_type = mime_content_type($files_to_upload['tmp_name'][$i]);
                    $allowed_file_types = ['image/jpeg', 'image/gif', 'image/png'];

                    if (in_array($mime_type, $allowed_file_types)) {
                        cropImageToAspectRatioGD($files_to_upload['tmp_name'][$i], $destination, 5, 4);

                        $sql = "INSERT INTO Foto (IdPost, NomeFile, PosizioneFile) VALUES (?, ?, ?)";
                        $stmt = $cid->prepare($sql);

                        if (!$stmt) {
                            error_log("Prepare failed: " . $cid->error);
                            continue;
                        }

                        $stmt->bind_param('sss', $IdPost, $sanitizedFileName, $DBdestination);

                        if (!$stmt->execute()) {
                            error_log("Execute failed: " . $stmt->error);
                            $response = [
                                'name' => $originalName,
                                'status' => 'error',
                                'message' => 'Database insertion failed',
                            ];
                            array_push($errorArray, $response);
                        }

                        $stmt->close();
                    } else {
                        $response = [
                            'name' => $originalName,
                            'status' => 'error',
                            'message' => 'File type not allowed!',
                        ];
                        error_log('File type not allowed');
                        array_push($errorArray, $response);
                    }
                }
            }

            if (!empty($errorArray)) {
                error_log(json_encode($errorArray));
                echo json_encode($errorArray);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Files uploaded successfully!',
                ]);
            }
        } else {
            error_log("Upload not set");
            echo json_encode([
                'status' => 'error',
                'message' => 'No files uploaded!',
            ]);
            exit();
        }
    }
}
