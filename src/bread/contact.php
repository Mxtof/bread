<?php
include_once "cfg/core.php";
$page_title = $contact_title;

$require_login = true;
include_once "includes/login_check.php";

include_once "includes/head.php";

echo <<<HTML
<div class="container col-md-6">
    <div class="contact-view">
        <h3>{$page_title}</h3>
        <address>
            <strong>Utoplab</strong><br>
            Rue Valentin Giraud<br>
            13600 La Ciotat, France<br>
        </address>
        <address>
            <strong>Christophe Michel</strong><br>
            <a href="mailto:#">michel.xtof@mail.com</a>
        </address>
    </div>
</div>
HTML;

include_once "includes/foot.php";
?>