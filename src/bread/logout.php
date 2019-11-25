<?php
include_once "cfg/core.php";
$page_title = $logout_title;

$require_login = true;
include_once "includes/login_check.php";

require_once "cfg/connect.php";
require_once "lib/sql/User.class.php";
com\mxtof\sql\User::create()->logout();

session_destroy();
header("Location: {$home_url}");
?>