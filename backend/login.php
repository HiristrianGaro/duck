<script>
<?php
    include '../config.php';
    if($_SERVER["REQUEST_METHOD"]== "POST"){

        if(isset($_POST["Login"])){
            error_log('User has Started Logging in!');
            if(empty($_POST["Login_Id"])){
                $errore= "Inserire un indirizzo email";
                $showModal = "true";
            }elseif (empty($_POST["Login_Password"])){
                $errore= "Inserire una password";
                $showModal = "true";
            }else{
                $LoginId = filter_input(INPUT_POST, "Login_Id", FILTER_SANITIZE_EMAIL);

                $Password = filter_input(INPUT_POST, "Login_Password", FILTER_SANITIZE_SPECIAL_CHARS);

                $findUtente = "SELECT * FROM utente 
                            WHERE IndirizzoEmail = '$LoginId' OR username = '$LoginId'";
                $findResults = mysqli_query($cid, $findUtente);
                

                if($findResults){
                    error_log('User Found');
                    echo mysqli_num_rows($findResults);
                    if(mysqli_num_rows($findResults)==1){
                        $row = mysqli_fetch_assoc($findResults);
                        $hashed = $row["Password"];
                        if(password_verify($Password,$hashed)){
                            $_SESSION["Username"] = $row["Username"];
                            $_SESSION["Status"] = 'ok';
                            error_log('User has logged in successfully!');
                            header("Location: ./index.php");
                            exit();
                        }
                        else{
                            $errore= "Password errata";
                            $showModal = "true";
                        }
                    }else{
                        $errore= "Email errata";
                        $showModal = "true";
                    }
                }else{
                    echo ("MySql Query failed:".mysqli_error($cid));
                    exit();
                }
                
            }       
        }
    }
?>
</script>