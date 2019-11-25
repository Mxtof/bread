<?php
include_once "cfg/core.php";
require_once "lib/sql/User.class.php";
require_once "lib/sql/QueryException.class.php";

use com\mxtof\sql as sql;
$authenticated = isLogged();

if (! $authenticated) {
    require_once "cfg/connect.php";
    $user = sql\User::create();
    try {
        $authenticated = $user->sessionLogin();

    } catch (sql\QueryException $ignored) {
        // fall through
    }

    if ($authenticated) {
        $user->dumpToSession();
    }
}


if ($require_login && (! $authenticated)) {
    if ($page_title === $home_title) {
        header("Location: {$guest_url}");
        die;

    } else {
        header("Location: {$login_url}?action=need_login");
        die;
    }
}

if ($authenticated &&
        (($page_title === $register_title) || ($page_title === $login_title)))
{
    header("Location: {$home_url}");
    die;
}
?>