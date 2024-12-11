<?php
    require_once("connection.php");
    require_once("../backend/addPost.php");
    include("modalErrore.php");
    include("errorModal.php");
?>

<div class="d-flex justify-content-center px-lg-3">
    <div class="p-3 p-md-4 border rounded-3 bg-white">
        <div class="pb-3 text-center">
        <h2 class="text-blue mb-3">Add an Egg</h2>    
        <p class="lead">Welcome back to <span class="text-yellow fw-bold">Duck</span></p>
        <p class="lead mb-3">Login to <span class="text-blue fw-bold">Quack</span> with your friends!</p>
    </div>
    <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"])?>" class="needs-validation">
        <div class="form-floating">
            <input type="file" class="p-2 mb-2" name="fileUpload" id="fileUpload">
            <!-- <label class="input-group-text" for="inputGroupFile02">Upload</label> -->
        </div>
        <div class="form-floating">
            <span class="input-group-text">Add Your Description</span>
            <textarea class="form-control" aria-label="With textarea"></textarea>
        </div>  
        <button class="btn btn-primary w-100 py-1 mt-4" type="submit" name= "addPost" value= "addPost">Post</button>
    </form>
</div>

