<?php

define('ROOT_DIR', __DIR__);

$repl = explode('htdocs/', ROOT_DIR)[1];



define('SITE_DOMAIN', 'http://localhost/' . $repl);

if(isset($_SESSION['Username'])){
    define('SITE_HOME', 'http://localhost/' . $repl . '/index.php?page=frontend/pond.php');
}else {
    define('SITE_HOME', 'http://localhost/' . $repl . '/index.php');
}

define('SITE_DIR', '/duck');

define('CONTENT_DIR', SITE_DIR . '/assets/brand');

define('POST_DIR', __DIR__ . '/assets/images/post');

define('LOCAL_POST_DIR', 'assets/images/post');


// if (function_exists('gd_info')) {
//     $gdInfo = gd_info();
//     print_r($gdInfo);
// } else {
//     echo "GD library is not enabled.";
// }

?>