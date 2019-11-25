<?php
include_once "cfg/core.php";
$page_title = $guest_title;
include_once "includes/head.php";

echo <<<HTML
    <div class="container col-md-6">
        <div class="guest-view">
            <h3>$page_title</h3>
            <p class="lead">Welcome, dear guest.</p>
            <p>
                If you haven't registered yet, first step in
                <a href="$register_url">here</a>
            </p>
            <p>
                To access further contents, please do log in
                <a href="$login_url">here</a>
            </p><br>
            <p class="lead">Thanks for visiting us!</p>
        </div>
    </div>
HTML;

include_once "includes/foot.php";
?>