<?php
session_start();
require_once("inc/config.inc.php");
require_once("inc/functions.inc.php");

$error_msg = "";
if(isset($_POST['email']) && isset($_POST['passwort'])) {
	$email = $_POST['email'];
	$passwort = $_POST['passwort'];

	$statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
	$result = $statement->execute(array('email' => $email));
	$user = $statement->fetch();

	//Überprüfung des Passworts
	if ($user !== false && password_verify($passwort, $user['passwort'])) {
		$_SESSION['userid'] = $user['id'];

		//Möchte der Nutzer angemeldet beleiben?
		if(isset($_POST['angemeldet_bleiben'])) {
			$identifier = random_string();
			$securitytoken = random_string();

			$insert = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (:user_id, :identifier, :securitytoken)");
			$insert->execute(array('user_id' => $user['id'], 'identifier' => $identifier, 'securitytoken' => sha1($securitytoken)));
			setcookie("identifier",$identifier,time()+(3600*24*365)); //Valid for 1 year
			setcookie("securitytoken",$securitytoken,time()+(3600*24*365)); //Valid for 1 year
		}

		header("location: index.php?msg=luck");
		exit;
	} else {
		$error_msg =  "E-Mail oder Passwort war ungültig";
	}

}

$email_value = "";
if(isset($_POST['email']))
	$email_value = htmlentities($_POST['email']);

include("inc/header.inc.php");
?>
	<div class="row">
  <form action="login.php" method="post" class="col s12">
	<h4>Login</h4>

<?php
if(isset($error_msg) && !empty($error_msg)) {
	echo $error_msg;
}
?>
	<div class="row">
		<div class="chip">
			Noch kein Mitglied? <a href="register.php">Jetzt Registrieren!</a>
		</div>
		<div class="chip">
			Passwort vergessen?<a href="passwortvergessen.php">Jetzt neues anfordern!</a>
		</div>
	</div>

	<div class="row">
		<div class="input-field col s12">
			<input type="email" name="email" id="inputEmail" class="validate" value="<?php echo $email_value; ?>" required autofocus>
			<label for="inputEmail">E-Mail</label>
		</div>

		<div class="input-field col s12">
			<input type="password" name="passwort" id="inputPassword" class="validate" required>
			<label for="inputPassword">Passwort</label>
		</div>

		<input type="checkbox" value="remember-me" id="remember-me" name="angemeldet_bleiben" value="1" checked="checked">
		<label for="remember-me">Angemeldet bleiben</label>
	</div>

	<button class="<?php echo $site_color_accent; ?> btn waves-effect waves-light" type="submit" name="action">Login
		<i class="material-icons right">send</i>
	</button>
  </form>
	</div>

<?php
include("inc/footer.inc.php")
?>