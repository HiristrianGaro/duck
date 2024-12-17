
<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include "../common/connection.php";
include "../common/funzioni.php";


if(isset($_SESSION['Status'])) {
?>
<script src="js/showFriends.js"></script>
<div class="justify-content-center">
    <div class="border rounded-3 p-3 p-md-4 bg-white">
        <div class="row">
            <!-- Suggested Friends Column -->
            <div class="col-md-6">
                <h5>Suggested Friends</h5>
                <ul class="list-group" id="SuggestedFriendsListId">
                </ul>
            </div>

            <!-- Your Current Friends Column -->
            <div class="col-md-6">
                <h5>Your Friends</h5>
                <ul class="list-group" id="CurrentFriendsListId">

                </ul>
            </div>
        </div>
    </div>
</div>
<?php } else {include("../common/UnauthorizedAllert.html");}?>
