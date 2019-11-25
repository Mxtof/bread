<?php
include_once "cfg/core.php";
$page_title = $profile_title;
include_once "includes/head.php";

$require_login = true;
include_once "includes/login_check.php";

include_once "includes/head.php";

$id      = getSessionDisplayValue("id"     );
$name    = getSessionDisplayValue("name"   );
$email   = getSessionDisplayValue("email"  );
$group   = getSessionDisplayValue("group"  );
$status  = getSessionDisplayValue("status" );
$created = getSessionDisplayValue("created");

echo <<<HTML
    <div class="container col-md-6">
        <div class="profile-view">
            <h3>{$name} {$page_title}</h3>
            <dl class="row">
                <dt class="profile-label col-sm-4">User ID</dt>
                <dd class="col-sm-8">$id</dd>
                <dt class="profile-label col-sm-4">Name</dt>
                <dd class="col-sm-8">$name</dd>
                <dt class="profile-label col-sm-4">Email</dt>
                <dd class="col-sm-8">$email</dd>
                <dt class="profile-label col-sm-4">Group</dt>
                <dd class="col-sm-8">$group</dd>
                <dt class="profile-label col-sm-4">Status</dt>
                <dd class="col-sm-8">$status</dd>
                <dt class="profile-label col-sm-4">Join Date</dt>
                <dd class="col-sm-8">$created</dd>
            </dl>
        </div>
    </div>
HTML;
include_once "includes/foot.php";
?>