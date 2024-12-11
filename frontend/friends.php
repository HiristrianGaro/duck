
<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include "../common/connection.php";
include "../common/funzioni.php";


if(isset($_SESSION['Status'])) {
?>
<div class="d-flex justify-content-center px-lg-3">
    <div class="p-3 p-md-4 border rounded-3 bg-white">
        <div class="row">
            <!-- Suggested Friends Column -->
            <div class="col-md-6">
                <h3>Suggested Friends</h3>
                <ul class="list-group">
                <?php
                // Example array of suggested friends with placeholder images
                $suggestedFriends = [
                    ['name' => 'Friend A', 'image' => 'https://via.placeholder.com/50'],
                    ['name' => 'Friend B', 'image' => 'https://via.placeholder.com/50'],
                    ['name' => 'Friend C', 'image' => 'https://via.placeholder.com/50'],
                    ['name' => 'Friend D', 'image' => 'https://via.placeholder.com/50'],
                ];

                foreach ($suggestedFriends as $friend) {
                    echo '<li class="list-group-item d-flex justify-content-start align-items-center">' .
                        '<img src="' . $friend['image'] . '" alt="Profile Image" class="rounded-circle me-2" style="width: 30px; height: 30px;">' .
                        '<span class="me-auto">' . $friend['name'] . '</span>' .
                        '<button class="btn btn-success btn-sm ms-2">Add</button>' .
                        '</li>';
                }
                ?>
                </ul>
            </div>

            <!-- Your Current Friends Column -->
            <div class="col-md-6">
                <h3>Your Friends</h3>
                <ul class="list-group">
                    <?php
                    // Example array of current friends with placeholder images
                    $currentFriends = [
                        ['name' => 'Current Friend 1', 'image' => 'https://via.placeholder.com/50'],
                        ['name' => 'Current Friend 2', 'image' => 'https://via.placeholder.com/50'],
                        ['name' => 'Current Friend 3', 'image' => 'https://via.placeholder.com/50'],
                    ];

                    foreach ($currentFriends as $friend) {
                        echo '<li class="list-group-item d-flex justify-content-start align-items-center">' .
                            '<img src="' . $friend['image'] . '" alt="Profile Image" class="rounded-circle me-2" style="width: 30px; height: 30px;">' .
                            '<span class="me-auto">' . $friend['name'] . '</span>' .
                            '<button class="btn btn-danger btn-sm ms-2">Remove</button>' .
                            '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php } else {include("../common/UnauthorizedAllert.html");}?>
