<?php
session_start();
require_once( "inc/config.inc.php" );
require_once( "inc/functions.inc.php" );

//Check that the user is logged in
//The call to check_user () must be built into all internal pages
$user = check_user();
$company = getCompany();
$site_title = "Settings";
include "inc/header.inc.php";

if ( isset( $_GET['save'] ) ) {
	$save = $_GET['save'];
	switch ($save) {
		case 'personal_data':
			$vorname  = trim( $_POST['vorname'] );
			$nachname = trim( $_POST['nachname'] );

			if ( $vorname == "" || $nachname == "" ) {
				$error_msg = "Please fill in First and Last name.";
			} else {
				$statement = $pdo->prepare( "UPDATE users SET vorname = :vorname, nachname = :nachname, updated_at=NOW() WHERE id = :userid" );
				$result    = $statement->execute( array(
					'vorname'  => $vorname,
					'nachname' => $nachname,
					'userid'   => $user['id']
				));
				$success_msg = "Data saved successfully.";
			}
			break;

		case 'email':
			$passwort = $_POST['passwort'];
			$email    = trim( $_POST['email'] );
			$email2   = trim( $_POST['email2'] );

			if ( $email != $email2 ) {
				$error_msg = "The EMail Addresses you entered do not match.";
			} else if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
				$error_msg = "Please enter a valid EMail Address.";
			} else if ( ! password_verify( $passwort, $user['passwort'] ) ) {
				$error_msg = "Please enter the correct password.";
			} else {
				$statement = $pdo->prepare( "UPDATE users SET email = :email WHERE id = :userid" );
				$result    = $statement->execute( array( 'email' => $email, 'userid' => $user['id'] ) );

				$success_msg = "EMail Address saved successfully.";
			}
			break;

		case 'passwort':
			$passwortAlt  = $_POST['passwortAlt'];
			$passwortNeu  = trim( $_POST['passwortNeu'] );
			$passwortNeu2 = trim( $_POST['passwortNeu2'] );

			if ( $passwortNeu != $passwortNeu2 ) {
				$error_msg = "The passwords entered do not match.";
			} else if ( $passwortNeu == "" ) {
				$error_msg = "The password must not be empty.";
			} else if ( ! password_verify( $passwortAlt, $user['passwort'] ) ) {
				$error_msg = "Please enter the correct password.";
			} else {
				$passwort_hash = password_hash( $passwortNeu, PASSWORD_DEFAULT );

				$statement = $pdo->prepare( "UPDATE users SET passwort = :passwort WHERE id = :userid" );
				$result    = $statement->execute( array( 'passwort' => $passwort_hash, 'userid' => $user['id'] ) );

				$success_msg = "Password saved successfully.";
			}
			break;

		case 'name_g':
			$name_g = $_POST['name_g'];
			if ($name_g) {
				$statement = $pdo->prepare("UPDATE company SET name = :name_g");
				$statement->execute(array('name_g' => $name_g));

				$success_msg = "Company name saved successfully.";
			} else {
				$error_msg = "Please enter a company name.";
			}
			break;

		case 'adress':
			$street 	= $_POST['street'];
			$number 	= $_POST['number'];
			$postcode = $_POST['postcode'];
			$city 		= $_POST['city'];
			$state 		= $_POST['state'];
			if (! empty ( $street ) && ! empty ( $number) && ! empty ( $postcode) && ! empty ( $city) && ! empty ( $state ) ) {
				$statement = $pdo->prepare("UPDATE company SET street = :street, number = :number, postcode = :postcode, city = :city, state = :state");
				$statement->execute(array('street' => $street, 'number' => $number, 'postcode' => $postcode, 'city' => $city, 'state' => $state));

				$success_msg = "Address saved successfully.";
			} else {
				$error_msg = "Please provide a full address.";
			}
			break;

		case 'contact':
			$email 		= $_POST['email'];
			$tel 		= $_POST['tel'];
			if (! empty ( $email ) && ! empty ( $tel) ) {
				$statement = $pdo->prepare("UPDATE company SET email = :email, tel = :tel");
				$statement->execute(array('email' => $email, 'tel' => $tel));

				$success_msg = "Contact data saved successfully.";
			} else {
				$error_msg = "Please provide correct contact details.";
			}
			break;

		case 'logo':
			$logo = $_POST['logo'];
			if ($logo) {
				$statement = $pdo->prepare("UPDATE company SET logo = :logo");
				$statement->execute(array('logo' => $logo));

				$success_msg = "Successfully saved path to logo.";
			} else {
				$error_msg = "Please enter a valid path to logo.";
			}
			break;
		default:
			// code...
			break;
	}
}

$user = check_user();
$company = getCompany();
?>


<h1 class="<?php echo $site_color_accent_text; ?>">Settings</h1>
<?php
if ( isset( $success_msg ) && ! empty( $success_msg ) ):
	?>
<script>
		M.toast({html: '<i class="material-icons">check</i> <?=$success_msg?>'});
</script>
<?php
endif;
?>

<?php
if ( isset( $error_msg ) && ! empty( $error_msg ) ):
	?>
	<script>
			M.toast({html: '<i class="material-icons">error_outline</i> <?=$error_msg?>'});
	</script>
<?php
endif;
?>
<div class="row">
	<div class="col s12 m4">
		<h3>Personal Settings</h3>
		<p>Change your Name, Password and EMail Address</p>
	</div>
	<div class="col s12 m8">
		<ul class="collapsible">
    	<li>
      	<div class="collapsible-header"><i class="material-icons">account_circle</i>Name</div>
      	<div class="collapsible-body">
					<form action="?save=personal_data" method="post" class="col s12">
	            <p>To change your name, please enter the new one and your email address.</p>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputVorname" name="vorname" type="text"
	                       value="<?php echo htmlentities( $user['vorname'] ); ?>" required>
	                <label for="inputVorname">First Name</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputNachname" name="nachname" type="text"
	                       value="<?php echo htmlentities( $user['nachname'] ); ?>" required>
	                <label for="inputNachname">Last Name</label>
	            </div>
							<div class="input-field col s12 m6">
	                <input class="validate" id="inputEmail" name="email" type="email"
	                       value="<?php echo htmlentities( $user['email'] ); ?>" required>
	                <label for="inputEmail">EMail Address</label>
	            </div>

	            <button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 m6 btn-large">Save</button>
	        </form>
					&nbsp;
				</div>
    	</li>
			<li>
				<div class="collapsible-header"><i class="material-icons">email</i>EMail Address</div>
      	<div class="collapsible-body">
					<form action="?save=email" method="post" class="col s12">
	            <p>To change your email address, please enter your current password and the new email address.</p>

	            <div class="input-field col s12">
	                <input class="validate" id="inputPasswort" name="passwort" type="password" required>
	                <label for="inputPasswort">Password</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputEmail" name="email" type="email"
	                       value="<?php echo htmlentities( $user['email'] ); ?>" required>
	                <label for="inputEmail">EMail Address</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputEmail2" name="email2" type="email" required>
	                <label for="inputEmail2">EMail Address (Confirm)</label>
	            </div>

	           <button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Save</button>
	        </form>
					&nbsp;
				</div>
			</li>
			<li>
				<div class="collapsible-header"><i class="material-icons">security</i>Password</div>
      	<div class="collapsible-body">
					<form action="?save=passwort" method="post" class="col s12">
	            <p>To change your password, please enter your current password and the new password.</p>

	            <div class="input-field col s12">
	                <input class="validate" id="inputPasswort" name="passwortAlt" type="password" required>
	                <label for="inputPasswort">Old Password</label>
	            </div>

	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputPasswortNeu" name="passwortNeu" type="password" required>
	                <label for="inputPasswortNeu">New Password</label>
	            </div>


	            <div class="input-field col s12 m6">
	                <input class="validate" id="inputPasswortNeu2" name="passwortNeu2" type="password" required>
	                <label for="inputPasswortNeu2">New Password (Confirm)</label>
	            </div>

	            <button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Save</button>
	        </form>
					&nbsp;
				</div>
			</li>
		</ul>
	</div>
</div>
<div class="row">
	<div class="col s12 m4">
		<h3>Company Settings</h3>
		<p>Change the name, address and other details of your company</p>
	</div>
	<div class="col s12 m8">
		<ul class="collapsible">
    <li>
      <div class="collapsible-header"><i class="material-icons">store</i>Company Name</div>
      <div class="collapsible-body">
				<form action="?save=name_g" method="post" class="col s12">
					<div class="input-field col s12">
							<input class="validate" id="id" name="name_g" type="text" required value="<?=$company['name']?>">
							<label for="id">Company Name</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Save</button>
				</form>
				&nbsp;
			</div>
    </li>
		<li>
      <div class="collapsible-header"><i class="material-icons">location_on</i>Address</div>
      <div class="collapsible-body">
				<form action="?save=adress" method="post" class="col s12">
					<div class="input-field col s10">
							<input class="validate" id="id" name="street" type="text" required value="<?=$company['street']?>">
							<label for="id">Street</label>
					</div>
					<div class="input-field col s2">
							<input class="validate" id="id" name="number" type="text" required value="<?=$company['number']?>">
							<label for="id">Plot No.</label>
					</div>
					<div class="input-field col s4">
							<input class="validate" id="id" name="postcode" type="text" required value="<?=$company['postcode']?>">
							<label for="id">Post Code</label>
					</div>
					<div class="input-field col s4">
							<input class="validate" id="id" name="city" type="text" required value="<?=$company['city']?>">
							<label for="id">City</label>
					</div>
					<div class="input-field col s4">
							<input class="validate" id="id" name="state" type="text" required value="<?=$company['state']?>">
							<label for="id">Country</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Save</button>
				</form>
				&nbsp;
			</div>
    </li>
		<li>
      <div class="collapsible-header"><i class="material-icons">contact_mail</i>Contact</div>
      <div class="collapsible-body">
				<form action="?save=contact" method="post" class="col s12">
					<div class="input-field col s12">
							<input class="validate" id="id" name="email" type="email" required value="<?=$company['email']?>">
							<label for="id">EMail Address</label>
					</div>
					<div class="input-field col s12">
							<input class="validate" id="id" name="tel" type="tel" required value="<?=$company['tel']?>">
							<label for="id">Phone</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Save</button>
				</form>
				&nbsp;
			</div>
    </li>
		<li>
      <div class="collapsible-header"><i class="material-icons">image</i>Logo</div>
      <div class="collapsible-body">
				<form action="?save=logo" method="post" class="col s12">
					<div class="input-field col s12">
							<input class="validate" id="id" name="logo" type="text" required value="<?=$company['logo']?>">
							<label for="id">Path to Logo</label>
					</div>
					<button type="submit" class="<?=$site_color_accent?> btn btn-primary col s12 btn-large">Save</button>
				</form>
				&nbsp;
			</div>
    </li>
	</div>
</div>
<?php
include( "inc/footer.inc.php" )
?>
