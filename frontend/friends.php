
<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();

if(isset($_SESSION['Status'])) {
?>
<div class="mx-3">
<div class="p-3 p-md-4 border rounded-3 bg-white">
        <div class="pb-3 text-center">
        <h2 class="text-blue mb-3">Friends and Acitvity</h2>    
        <p class="lead">Lets see what your <span class="text-yellow fw-bold">Friends</span> have been up to</p>
        <p class="lead mb-2">And maybe <span class="text-blue fw-bold">Add</span> some more!</p>
    </div>
    <hr>
<div class="row">
    <div class="leftsplit col-lg-3 d-none d-md-flex justify-content-md-start">
        <div class="flex-fill border rounded-3 shadow p-3 p-md-4 bg-white">
            <h5>Suggested Friends</h5>
            <ul class="list-group friends-container" id="SuggestedFriendsListId"></ul>
        </div>
    </div>

    <div class="centralsplit col-12 col-lg-6 d-flex justify-content-center mx-auto">
        <div class="flex-fill border rounded-3 shadow p-3 p-md-4 bg-white ">
            <div class="row">
                <div class="col-6">
                    <h5>Friend Requests</h5>
                    <ul class="list-group friends-container" id="FriendRequestsListId"></ul>
                </div>

                <div class="col-6">
                    <h5>Your Friends</h5>
                    <ul class="list-group friends-container" id="CurrentFriendsListId"></ul>
                </div>
            </div>
        </div>
    </div>
    <div class="rightsplit col-lg-3 d-none d-md-flex justify-content-md-end">
    </div>
</div>
</div>



<?php } else {include("../frontend/items/UnauthorizedAllert.html");}?>
<script src="js/showFriends.js"></script>
