<?php
include_once "cfg/core.php";
$page_title = $register_title;

include_once "includes/head.php";

$require_login = false;
include_once "includes/login_check.php";

errorCheck();

use com\mxtof\sql as sql;
if (isset($_POST["submit"])) {
	require_once "cfg/connect.php";
    require_once "lib/sql/User.class.php";
    require_once "lib/sql/FieldException.class.php";
    require_once "lib/sql/QueryException.class.php";

	$user = sql\User::create();
	try {
		$user->setName(    $_POST["username"])
			 ->setEmail(   $_POST["email"   ])
			 ->setPassword($_POST["password"])
			 ->setStatus(sql\User::STATUS_ENABLED)
		;

	} catch (sql\FieldException $x) {
        fail($x->getDisplayMessage(), $register_url);
    }

	if ($user->nameExists()) {
		$name = $user->getName();
		fail("User name '{$name}' is already in use: Please choose an other one.", $register_url);
	}

	if ($user->emailExists()) {
		$email = $user->getEmail();
		fail("Email '{$email}' is already in use: Maybe have you registered before?", $register_url);
	}

	if ($user->getPassword() !== $_POST["confirm_password"]) {
		fail("Password inputs don't match, please retry.", $register_url);
	}

	try {
		$user->register();

	} catch (sql\QueryException $y) {
        fail($y->getMessage(), $register_url);
	}

	$user->dumpToSession();
	header("Location: {$home_url}?action=welcome");
}
?>

<!-- Registration Form -->
<div class="signup-form">
    <form action="register.php" method="POST">
		<h2>New Account</h2>
		<hr>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<input type="text" class="form-control" name="username" placeholder="Username" required="required" autofocus>
			</div>
        </div>
        <div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
				<input type="email" class="form-control" name="email" placeholder="Email Address" required="required">
			</div>
        </div>
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				<input type="password" class="form-control" name="password" placeholder="Password" required="required">
			</div>
        </div>
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-addon">
					<i class="fa fa-lock"></i>
					<i class="fa fa-check"></i>
				</span>
				<input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required="required">
			</div>
        </div>
        <div class="form-group">
			<label class="checkbox-inline">
				<input type="checkbox" required="required">
				 I hereby accept the <a href="#terms-modal" data-toggle="modal" data-target="#terms-modal">Terms of Use</a><br>
				 and the <a href="#privacy-modal" data-toggle="modal" data-target="#privacy-modal">Privacy Policy</a>
			</label>
		</div>
		<div class="form-group">
            <button type="submit" name="submit" class="btn btn-primary btn-lg">Sign Up</button>
        </div>
    </form>
	<p>Already have an account? <a href="<?php echo $login_url; ?>">Login here</a></p>
</div>
<!-- /Registration Form -->

<!-- Terms Modal -->
<div class="modal fade" id="terms-modal" tabindex="-1" role="dialog" aria-labelledby="terms-modal-title"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="terms-modal-title">Terms</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php include "includes/terms.txt"; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /Terms Modal -->

<!-- Privacy Modal -->
<div class="modal fade" id="privacy-modal" tabindex="-1" role="dialog" aria-labelledby="privacy-modal-title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="privacy-modal-title">Privacy</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php include "includes/privacy.txt"; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- /Privacy Modal -->

<?php include_once "includes/foot.php"; ?>