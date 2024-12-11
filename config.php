<?php

define('ROOT_DIR', __DIR__);

define('SITE_DOMAIN', 'http://localhost/duck');

define('SITE_HOME', 'http://localhost/duck/index.php');

define('SITE_DIR', '/duck');

define('CONTENT_DIR', SITE_DIR . '/assets/brand');

ini_set("log_errors", TRUE); 

$log_file = 'phplog.log';

ini_set('error_log', $log_file);

?>