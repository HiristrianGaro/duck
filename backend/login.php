<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
session_unset();
include '../errorLogging.php';
include '../common/connection.php';
include '../common/funzioni.php';

$result = array();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log('User has Started Logging in!');
    if (empty($_POST["Login_Id"])) {
        $errore = "Inserire un indirizzo email";
        $showModal = "true";
    } elseif (empty($_POST["Login_Password"])) {
        $errore = "Inserire una password";
        $showModal = "true";
    } else {
        error_log('Checking if user exists...');
        $LoginId = filter_input(INPUT_POST, "Login_Id", FILTER_SANITIZE_EMAIL);

        $Password = filter_input(INPUT_POST, "Login_Password", FILTER_SANITIZE_SPECIAL_CHARS);

        $findUtente = "SELECT Username, IndirizzoEmail, Password FROM Utente 
                        WHERE IndirizzoEmail = '$LoginId' OR username = '$LoginId'";
        $findResults = mysqli_query($cid, $findUtente);


        if ($findResults) {
            if (mysqli_num_rows($findResults) == 1) {
                $row = mysqli_fetch_assoc($findResults);
                $hashed = $row["Password"];
                if (password_verify($Password, $hashed)) {
                    $_SESSION["Username"] = $row["Username"];
                    $_SESSION["IndirizzoEmail"] = $row["IndirizzoEmail"];
                    $_SESSION["Status"] = 'ok';

                    $verificaAdmin = checkAdmin($cid, $_SESSION["IndirizzoEmail"]);
                    if (!empty($verificaAdmin[0]["AdminBool"]) && $verificaAdmin[0]["AdminBool"]) {
                        $_SESSION["Admin"] = 'true';
                    }

                    $response = [
                        'status' => 'Success',
                        'redirect' => 'frontend/pond.php'];
                    array_push($result, $response);
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Email o password errati!',
                ];
                array_push($result, $response);
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Errore durante il login: ' . mysqli_error($cid),
            ];
            array_push($result, $response);
            exit();
        }
        error_log(json_encode($result));
        echo json_encode($result);
    }
}
