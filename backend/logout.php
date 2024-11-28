<?php
include '../config.php';
session_unset();
session_destroy();
error_log('I am trying to log out');
header("location: ". SITE_HOME);

?>