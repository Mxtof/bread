<?php
include_once "cfg/core.php";
$page_title = $home_title;
include_once "includes/head.php";

$require_login = true;
include_once "includes/login_check.php";

$name   = htmlspecialchars($_SESSION["name"]);
$action = $_GET["action"] ?? "";

if ($action === "welcome") {
    success("<h3>Congratulations!</h3><p>Your registration is successful.<br><strong>Welcome aboard, {$name}!</strong></p>");

} else if ($action === "login_success") {
    info("Welcome back Home, <strong>{$name}</strong>!");

} else {
    info("Welcome Home, <strong>{$name}</strong>.");
}

echo <<<HTML
    <div class="container col-md-6">
        <figure class="figure">
            <img src="lib/img/coffee.jpg" class="figure-img img-fluid rounded" alt="Coffee Cup img">
            <figcaption class="figure-caption text-right">
                Home is where your <em>coffee</em> is.
            </figcaption>
        </figure>
    </div>
HTML;

include_once "includes/foot.php";
?>