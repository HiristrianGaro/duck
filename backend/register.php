<script>
    <?php

if($_SERVER["REQUEST_METHOD"]== "POST"){

    if(isset($_POST["Register"])){
        error_log('User has Started Registering in!');

        if(empty($_POST["RegisterUsername"])){
            $errore= "Please Insert a Username";
            $showModal = "true";
        }elseif(empty($_POST["RegisterName"])){
            $errore= "Please Insert a Name";
            $showModal = "true";
        }elseif(empty($_POST["RegisterSurname"])){
            $errore= "Please Insert a Surname";
            $showModal = "true";
        }elseif(empty($_POST["RegisterEmail"])){
            $errore= "Please Insert a valid Email";
            $showModal = "true";
        }elseif(empty($_POST["RegisterPassword"])){
            $errore= "Please Insert a Password";
            $showModal = "true";
        }elseif(empty($_POST["ConfirmRegisterPassword"])){
            $errore= "Please Confirm your Password";
            $showModal = "true";
        }elseif($_POST["RegisterPassword"] != $_POST["ConfirmRegisterPassword"]){
            $errore= "Passwords do not match";
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
                error_log('Email does not exist. Proceeding with insertion...');
                $registration = "INSERT INTO utente (Username, IndirizzoEmail, Nome, Cognome, Password)
                                VALUES ('$Username','$Email','$Nome','$Cognome','$hashed')";
                if (mysqli_query($cid, $registration)) {
                    $_SESSION["Status"] = "ok";
                    header("Location: ./index.php");
                    echo "Registration successful.\n";
                    error_log('Registration successful.');
                } else {
                    echo "Error during registration: " . mysqli_error($cid) . "\n";
                }
            }
        }
    }
}
?>
</script>