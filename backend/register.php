<?php
include "../common/connection.php";
include "../common/funzioni.php";


if(empty($_POST["RegisterUsername"])){
    $errore= "Inserire un username";
    $showModal = "true";
}elseif(empty($_POST["RegisterName"])){
    $errore= "Inserire un nome";
    $showModal = "true";
}elseif(empty($_POST["RegisterSurname"])){
    $errore= "Inserire un cognome";
    $showModal = "true";
}elseif(empty($_POST["RegisterEmail"])){
    $errore= "Inserire un indirizzo Email valido";
    $showModal = "true";
}elseif(empty($_POST["RegisterPassword"])){
    $errore= "Inserire una password";
    $showModal = "true";
}elseif(empty($_POST["ConfirmRegisterPassword"])){
    $errore= "Per favore conferma la password";
    $showModal = "true";
}elseif($_POST["RegisterPassword"] != $_POST["ConfirmRegisterPassword"]){
    $errore= "Le password non coincidono";
    $showModal = "true";
}else{

    $Username = filter_input(INPUT_POST, "RegisterUsername", FILTER_SANITIZE_SPECIAL_CHARS);
    $Nome = filter_input(INPUT_POST, "RegisterName", FILTER_SANITIZE_SPECIAL_CHARS);
    $Cognome = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_SPECIAL_CHARS);
    $Email = filter_input(INPUT_POST, "RegisterEmail", FILTER_SANITIZE_EMAIL);
    $Password = filter_input(INPUT_POST, "RegisterPassword", FILTER_SANITIZE_SPECIAL_CHARS);
    $ConfirmPassword = filter_input(INPUT_POST, "ConfirmRegisterPassword", FILTER_SANITIZE_SPECIAL_CHARS);

    $hashed = password_hash($Password, PASSWORD_DEFAULT);

    echo "Checking if email already exists...\n";

    if (checkEmailExist($cid, $Email)["status"] == "ko") {
        echo "Email does not exist. Proceeding with insertion...\n";
            echo "Email does not exist. Proceeding with insertion...\n";
            $registration = "INSERT INTO utente (Username, IndirizzoEmail, Nome, Cognome, Password)
                            VALUES ('$Username','$Email','$Nome','$Cognome','$hashed')";
            if (mysqli_query($cid, $registration)) {
                $_SESSION["Email"]= $Email;
                $_SESSION["Status"]= "ok";
                header("Location: ../index.php");
                echo "Registration successful.\n";
            } else {
                echo "Error during registration: " . mysqli_error($cid) . "\n";
            }
        }
    }
?>