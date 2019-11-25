<?php
// Provides php engine with a few hints for searching file entities.
$path = dirname(__DIR__);
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
spl_autoload_register(function ($className) {
    include $className . ".class.php";
});

// Ensures all multi-bytes functions are set with the same character encoding.
const DEFAULT_CHARSET = "UTF-8";
mb_language("uni");
mb_internal_encoding(DEFAULT_CHARSET);
mb_http_input(       DEFAULT_CHARSET);
mb_http_output(      DEFAULT_CHARSET);
mb_regex_encoding(   DEFAULT_CHARSET);
ob_start("mb_output_handler");

// Starts (or resume) a session.
session_start();

// Specifies the URLs for the main pages.
$home_url     = "http://localhost/projects/bread/src/bread/";
$register_url = $home_url . "register.php";
$login_url    = $home_url . "login.php";
$logout_url   = $home_url . "logout.php";
$profile_url  = $home_url . "profile.php";
$about_url    = $home_url . "about.php";
$contact_url  = $home_url . "contact.php";
$guest_url    = $home_url . "guest.php";

// Specifies the titles of the main pages.
$home_title     = "BREAD Home Page";
$register_title = "Register";
$login_title    = "User Login";
$logout_title   = "User Logout";
$profile_title  = "User Profile";
$about_title    = "About";
$contact_title  = "Contact";
$guest_title    = "BREAD Starting Page";

// Specifies the pseudo-theme colors in use.
$guest_color = "#0069D9";
$user_color  = "#EE773A";
$main_color  = (isLogged()) ? $user_color : $guest_color;

// Specifies the boundaries for pagination.
$page = isset($_GET["page"]) ?? 1;
$record_per_page = 5;
$from_record_num = ($record_per_page * $page) - $record_per_page;


/**
 * Extracts a value from the `$_SESSION` container, and returns
 * a string expression which is suitable for a HTML page.
 *
 * @param string $key
 * The label of the value to look for.
 * @return string
 * A safe string expression; or an empty string if no value was
 * associated with `$key`.
 */
function getSessionDisplayValue(string $key) : string {
    return isset($_SESSION[$key]) ? htmlspecialchars($_SESSION[$key]) : "";
}

/**
 * Checks whether the current user is authenticated.
 *
 * @return boolean
 */
function isLogged() : bool {
    return (isset($_SESSION["authenticated"]) && ($_SESSION["authenticated"]));
}

/**
 * Stores a given error message in session container, redirects the user
 * to a specified page, and terminates the execution.
 *
 * @param string $msg   The message to be stored.
 * @param string $url   The URL of the redirection.
 * @return void
 */
function fail(string $msg, string $url) {
    $_SESSION["error"] = $msg;
    header("Location: {$url}");
    die;
}

/**
 * Displays a success message.
 *
 * @param string $msg   The message to be displayed.
 * @return void
 */
function success(string $msg) {
    echo "<div class='alert alert-success'>{$msg}</div>";
}

/**
 * Displays an information message.
 *
 * @param string $msg   The message to be displayed.
 * @return void
 */
function info(string $msg) {
    echo "<div class='alert alert-info'>{$msg}</div>";
}

/**
 * Displays an error message.
 *
 * @param string $msg   The message to be displayed.
 * @return void
 */
function error(string $msg) {
    echo "<div class='alert alert-danger'><strong>{$msg}</strong></div>";
}

/**
 * Checks whether the session container holds an error, and display the message if any.
 *
 * @return void
 */
function errorCheck() {
    if (isset($_SESSION["error"]))
    {
        error($_SESSION["error"]);
        unset($_SESSION["error"]);
    }
}
?>