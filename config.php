<?php

define('ROOT_DIR', __DIR__);

define('SITE_DOMAIN', 'http://localhost/duck');

define('SITE_HOME', 'http://localhost/duck/index.php');

define('SITE_DIR', '/duck');

define('CONTENT_DIR', SITE_DIR . '/assets/brand');

define('POST_DIR', __DIR__ . '/assets/images/post');

ini_set("log_errors", 1); 

ini_set('error_log', '/Applications/XAMPP/xamppfiles/htdocs/duck/phplog.log');

// if (function_exists('gd_info')) {
//     $gdInfo = gd_info();
//     print_r($gdInfo);
// } else {
//     echo "GD library is not enabled.";
// }

?>