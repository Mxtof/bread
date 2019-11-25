<?php
include_once "cfg/core.php";
$page_title = $login_title;

include_once "includes/head.php";

$require_login = false;
include_once "includes/login_check.php";

$action = $_GET["action"] ?? "";
if ($action === "need_login") {
    error("Please login to access that page.");
}

errorCheck();

use com\mxtof\sql as sql;
if (isset($_POST["submit"])) {

    require_once "cfg/connect.php";
    require_once "lib/sql/User.class.php";
    require_once "lib/sql/FieldException.class.php";
    require_once "lib/sql/QueryException.class.php";

    $user = sql\User::create();
    try {
        $doSaveSession = (isset($_POST["do-save-session"])) ? true : false;
        $user->setName($_POST["username"])->setPassword($_POST["password"]);
        $authenticated = $user->login($doSaveSession);

    } catch (sql\FieldException $x) {
        fail($x->getDisplayMessage(), $login_url);

    } catch (sql\QueryException $y) {
        fail($y->getMessage(), $login_url);
    }

    if ($authenticated) {
        $user->dumpToSession();
        header("Location: {$home_url}?action=login_success");
        die;
    }

    error("Login failed: invalid user name / password combination.");
}
?>

<!-- Login Form -->
<div class="login-form">
  <h2>Login Panel</h2>
  <hr>
  <form action="login.php" method="POST">
    <div class="form-group">
      <input type="text" class="form-control" name="username" placeholder="Username" required="required" autofocus />
    </div>
    <div class="form-group">
      <input type="password" class="form-control" name="password" placeholder="Password" required="required" />
    </div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary btn-block" name="submit">Log in</button>
    </div>
    <div class="clearfix">
      <label class="pull-left checkbox-inline">
        <input type="checkbox" name="do-save-session"> Remember me
      </label>
      <hr>
      <a href="#unsupported" data-toggle="modal" data-target="#unsupported">Forgot Password?</a>
    </div>
  </form>
  <p><a href="<?php echo $register_url; ?>">Create an Account</a></p>
</div>
<!-- /Login Form -->

<!-- Forgotten Password Modal -->
<div class="modal fade" id="unsupported" tabindex="-1" role="dialog" aria-labelledby="unsupportedLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="unsupportedLabel">Sorry!</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Alas, this functionality is not implemented yet.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- /Forgotten Password Modal -->

<?php include_once "includes/foot.php"; ?>