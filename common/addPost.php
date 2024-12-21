<div class="d-flex justify-content-center px-lg-3 mb-3">
    <div class="p-3 p-md-4 border rounded-3 bg-white">
        <div class="text-center">
        <h2 class="text-blue mb-3">Add an Egg</h2>    
        <p class="lead">Welcome back to <span class="text-yellow fw-bold">Duck</span></p>
        <p class="lead mb-3">Login to <span class="text-blue fw-bold">Quack</span> with your friends!</p>
    </div>
    <hr>
    <p>Add you pictures here ⬇️</p>
    <form method="POST" enctype="multipart/form-data" id="addPostForm" class="needs-validation">
        <div class="form-floating">
            <input type="file" class="p-2 mb-2" name="filesToUpload[]" id="filesToUpload" multiple="multiple">
        </div>
        <p style="font-size: 10px;">* Note that all images will be cropped and centered to 5:4.
            <br>We suggested to crop them before hand to choose a center point</p>
        <hr>
        <p>Add a Description to your Egg 💃💃💃</p>
        <div class="form-floating">
            <textarea class="form-control" name="postDesc" id="postDesc" placeholder="Post text"></textarea>
        </div>
        <div>

        <hr>
        <div>
            <p class="bold">Want to let people know where you took these Quakidy pictures?</p>
            <select class='form-select mb-1' id="country">
                <option value="" disabled selected>Select a country</option>
            </select>
            <select class='form-select mb-1 mb-0' id="state" disabled>
                <option value="" disabled selected>Select a state</option>
            </select>
            <select class='form-select mb-1' id="city" disabled>
                <option value="" disabled selected>Select a city</option>
            </select>
        </div>

        <button class="btn btn-primary w-100 py-1 mt-4" type="submit">Post</button>
        <p class="text-center mt-3" id="addPostAlertP"></p>
    </form>
</div>

<script src="js/addPost.js"></script>
<script src="js/CountryStateCityAPI.js"></script>