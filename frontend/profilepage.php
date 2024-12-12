<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) { ?>

<div class="container mt-5">
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center" id="profile-header">
            
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-primary btn-block float-right">Follow</button>
        </div>
    </div>

    <div class="card mx-lg-5 mb-4">
        <div class="card-body m-3">
            <div class="row no-gutters" id="grid-item">
                
            </div>
        </div>
    </div>
</div>

<?php } else { include("../common/UnauthorizedAllert.html");} ?>

<script src="js/profilePage.js"></script>
