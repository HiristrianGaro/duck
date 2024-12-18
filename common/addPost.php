<div class="d-flex justify-content-center px-lg-3">
    <div class="p-3 p-md-4 border rounded-3 bg-white">
        <div class="pb-3 text-center">
        <h2 class="text-blue mb-3">Add an Egg</h2>    
        <p class="lead">Welcome back to <span class="text-yellow fw-bold">Duck</span></p>
        <p class="lead mb-3">Login to <span class="text-blue fw-bold">Quack</span> with your friends!</p>
    </div>
    <form method="POST" enctype="multipart/form-data" id="addPostForm" class="needs-validation">
        <div class="form-floating">
            <input type="file" class="p-2 mb-2" name="filesToUpload[]" id="filesToUpload" multiple="multiple">
        </div>
        <div class="form-floating">
            <textarea class="form-control" name="postDesc" id="postDesc" placeholder="Post text"></textarea>
            <label for="postDesc">Add a Description to your Egg! 💃💃💃</label>
        </div>
        <div>
        <input type="text" id="searchInput" placeholder="Type to search..."><select id="myDropdown"></select>
        </div>
        <button class="btn btn-primary w-100 py-1 mt-4" type="submit">Post</button>
        <p class="text-center mt-3" id="addPostAlertP"></p>
    </form>
</div>

<script src="js/addPost.js"></script>
<script src="js/citiesAPI.js"></script>