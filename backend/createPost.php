<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
include '../config.php';
include 'imagecrop.php';
include '../common/connection.php';

createPost();
error_log(POST_DIR);

function createPost() {
    global $cid; // Ensure $cid is accessible

    error_log('Creating Post');
    $AutorePostEmail = $_SESSION['IndirizzoEmail'];
    $Description = " "; // Fixed typo
    $timestamp = date('Y-m-d H:i:s');
    $NomeCitta = 'Milan';
    $StatoCitta = 'Italy';
    $ProvinciaCitta = 'Milan';

    $sql = "INSERT INTO Post (AutorePostEmail, Descrizione, TimestampPubblicazione, NomeCitta, StatoCitta, ProvinciaCitta) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $cid->prepare($sql);

    if (!$stmt) {
        error_log("Prepare failed: " . $cid->error);
        die('Database error');
    }

    $stmt->bind_param('ssssss', $AutorePostEmail, $Description, $timestamp, $NomeCitta, $StatoCitta, $ProvinciaCitta);

    error_log('Executing Query');
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error);
        die('Failed to execute query');
    }

    $stmt->close();
    error_log('Post Created');
    error_log('Adding Foto to Post');
    addFotoPost($AutorePostEmail, $timestamp);
}

function addFotoPost($AutorePostEmail, $timestamp) {
    global $cid; // Ensure $cid is accessible

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_FILES['filesToUpload'])) {
            $files_to_upload = $_FILES['filesToUpload'];

            if (empty($files_to_upload['name'][0])) {
                error_log("No files uploaded");
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No files uploaded!',
                ]);
                exit();
            }

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

                        $sql = "INSERT INTO Foto (TimestampPubblicazione, AutorePostEmail, NomeFile, PosizioneFile) VALUES (?, ?, ?, ?)";
                        $stmt = $cid->prepare($sql);

                        if (!$stmt) {
                            error_log("Prepare failed: " . $cid->error);
                            continue;
                        }

                        $stmt->bind_param('ssss', $timestamp, $AutorePostEmail, $sanitizedFileName, $DBdestination);

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
