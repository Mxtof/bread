<?php
include_once "cfg/core.php";

$isLogged = isLogged();
$active   = "<li class='nav-item active'>";
$neutral  = "<li class='nav-item'>";
$disabled = "<a class='nav-link disabled' href='";
$enabled  = "<a class='nav-link' href='";

$menu = [
    [
        "name"  => "Home",
        "item"  => ($page_title === $home_title || $page_title === $guest_title) ? $active : $neutral,
        "state" => $enabled,
        "href"  => $home_url
    ],
    [
        "name"  => "Register",
        "item"  => ($page_title === $register_title) ? $active : $neutral,
        "state" => ($isLogged) ? $disabled : $enabled,
        "href"  => $register_url
    ],
    [
        "name"  => "Login",
        "item"  => ($page_title === $login_title) ? $active : $neutral,
        "state" => ($isLogged) ? $disabled : $enabled,
        "href"  => $login_url
    ],
    [
        "name"  => "Logout",
        "item"  => ($page_title === $logout_title) ? $active : $neutral,
        "state" => ($isLogged) ? $enabled : $disabled,
        "href"  => $logout_url
    ],
    [
        "name"  => "Profile",
        "item"  => ($page_title === $profile_title) ? $active : $neutral,
        "state" => ($isLogged) ? $enabled : $disabled,
        "href"  => $profile_url
    ],
    [
        "name"  => "About",
        "item"  => ($page_title === $about_title) ? $active : $neutral,
        "state" => ($isLogged) ? $enabled : $disabled,
        "href"  => $about_url
    ],
    [
        "name"  => "Contact",
        "item"  => ($page_title === $contact_title) ? $active : $neutral,
        "state" => ($isLogged) ? $enabled : $disabled,
        "href"  => $contact_url
    ]
];

?>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" role="navigation" style="background-color:<?php echo $main_color; ?>">
        <a class="navbar-brand" href="<?php echo $home_url ?>">
            <img src="lib/img/guitar-hero.png" width="36" height="36" class="d-inline-block align-top" alt="logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                    foreach ($menu as $entry) {
                        echo $entry["item"]
                            . $entry["state"]
                            . $entry["href"]
                            . "'>"
                            . $entry["name"]
                            . "</a></li>";
                    }
                ?>
            </ul>
        </div>
    </nav>
</header>