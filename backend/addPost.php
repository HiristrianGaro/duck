<script>
<?php
include '../config.php';

    if($_SERVER["REQUEST_METHOD"]== "POST"){

        if(isset($_POST["AddPost"])){
            if(empty($_POST["fileUpload"])){
                $errore= "Please add a file";
                $showModal = "true";
            }
        }
    }
?>
</script>
