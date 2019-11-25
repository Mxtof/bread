<?php
include_once "cfg/core.php";
$page_title = $about_title;

$require_login = true;
include_once "includes/login_check.php";

include_once "includes/head.php";
?>
<section class="container col-md-8">
    <h1>The BREAD project</h1>
    <p class="text-muted">Last updated: November 24, 2019</p><br>
    <h2>Objectives</h2>
    <ul>
        <li>Develop a small <abbr title="PHP Hypertext Processor">PHP</abbr> library which spares a client from database queries.</li>
        <li>First (akward) steps into bootstrap / jquery components.</li>
    </ul>
    <br>
    <h2>Description</h2>
    <p>
        The <em>BREAD</em> acronym stands for Browse, Read, Edit, Add and Delete.<br>
        Those are the basic functionalities which are (or will be) developped here,
        with <em>PHP Object Oriented Programming</em>.
    </p>
    <p>
        Note <strong>this project is a work in progress</strong>: it is only the first draft
        of two weeks work, made available to allow its evaluation.
    </p>
    <br>
    <h2>Features</h2>
    <p>
        The package <code>com\mxtof\sql</code> is the core of the library, which provides
        the functionalities described thereafter.
    </p>
    <br>
    <h3>The <code>Config</code> class</h3>
    <ul>
        <li>Dependency injection</li>
        <li>Holds the settings to open a database connection through PDO handle</li>
    </ul>
    <br>
    <h3>The <code>Database</code> class</h3>
    <ul>
        <li>Unique instance (singleton pattern)</li>
        <li>Open database connection with a given <code>Config</code> (low client-coupling)</li>
        <li>Access to <code>PDO</code> handle</li>
        <li>Lookup table (checks the presence of a value in a table)</li>
    </ul>
    <br>
    <h3>The <code>User</code> class</h3>
    <ul>
        <li>Creation</li>
        <li>Input validation</li>
        <li>Lookup <code>name</code> or <code>email</code> in user table</li>
        <li>Registration</li>
        <li>Login</li>
        <li>Session login (current session saved to database)</li>
        <li>Logout</li>
    </ul>
    <br>
    <h3>The <code>...Exception</code> classes</h3>
    <ul>
        <li>Custom error handling / report</li>
    </ul>
    <br>
    <h2>TODO</h2>
    <ul>
        <li>Email validation</li>
        <li>Forgotten password</li>
        <li>Basic content management (admin zone)</li>
        <li>Language support</li>
    </ul>
</section>

<?php include_once "includes/foot.php"; ?>