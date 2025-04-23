<?php    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();

    if(isset($_SESSION['Status'])) { ?>
<div class="mx-4">
    <div class="m-md-6">
        <h1 class="text-center mt-4">Profile</h1>
        <div class="profile-container card mb-4">
            <div class="card-body d-flex align-items-center shadow-lg" id="profile-header">
                
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-block float-right" id="follow-btn" data-action="{{Action}}" onclick="followAction(event)" ></button>
            </div>
        </div>
    </div>
        <div>
            <div class="card mx-lg-5 mb-4 shadow">
                <div class="card-body m-3">
                    <div class="row no-gutters" id="grid-item">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } else { include("../frontend/items/UnauthorizedAllert.html");} ?>


<script src="js/profilePage.js"></script>
