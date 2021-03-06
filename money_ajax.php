<?php
@session_start();
include_once "inc/functions.inc.php";


header( "Content-Type: application/json" );

function give_result() {
	global $result;
	echo json_encode( $result );
	die();
}

function give_error( $message ) {
	global $result;
	$result = array(
		"error"   => true,
		"success" => false,
		"message" => $message
	);
	give_result();
}

function give_success( $message ) {
	global $result;
	$result = array(
		"error"   => false,
		"success" => true,
		"message" => $message
	);
	give_result();
}

$result = false;
if ( ! check_user() ) {
	give_error( "No registered logged in user" );
}

if ( empty( $_GET["action"] ) ) {
	give_error( "No action specified." );
}

if ( ! isset( $_SESSION["cart"] ) ) {
	$_SESSION["cart"] = array();
}

$result = array();
$action = $_GET["action"];
switch ( $action ) {
	case "get_curr_balance":
    global $pdo;
    $sql = "SELECT * FROM `money` ORDER BY `id` DESC LIMIT 1";
    $result = $pdo->query($sql)->fetch();
		give_result();
		break;

  case "get_balance":
    global $pdo;
    $sql = "SELECT * FROM `money` ORDER BY `id` DESC";
    foreach ($pdo->query($sql) as $row){
      array_push($result, $row);
    }
		give_result();
		break;

  case 'get_users':
    global $pdo;
    $sql = "SELECT * FROM `users` ORDER BY `id`";
    foreach ($pdo->query($sql) as $row){
      array_push($result, array(
        'id' => $row['id'],
        'vorname' => $row['vorname'],
        'nachname' => $row['nachname']
      ));
    }
    give_result();
    break;

  case 'add_money':
    $money_to_add = $_GET['money'];
    $userid = $_GET['user'];
    if (empty($money_to_add)) {
      give_error("No amount specified");
    }
    if (empty($userid)) {
      give_error("No user specified");
    }

    $money_to_add = floatval($money_to_add);

    global $pdo;
    $sql = "SELECT `balance` FROM `money` ORDER BY `id` DESC LIMIT 1";
    $curr_balance = $pdo->query($sql)->fetch();

    $curr_balance = floatval($curr_balance['balance']);
    //give_error(var_dump($curr_balance));
    $new_balance = $curr_balance + $money_to_add;

    $statement = $pdo->prepare( "INSERT INTO `money` (`id`, `change_price`, `balance`, `updated_by`, `created_at`) VALUES (NULL, :money, :new_balance, :user, CURRENT_TIMESTAMP);" );
		$result    = $statement->execute( array(
			'money'    => $money_to_add,
			'new_balance' => $new_balance,
			'user'  => $userid
		) );
    give_success("Amount added");
    break;

	case 'delete_money':
    $money_to_add = $_GET['money'];
    $userid = $_GET['user'];
    if (empty($money_to_add)) {
      give_error("No difference specified");
    }
    if (empty($userid)) {
      give_error("No user specified");
    }

    $money_to_substract = floatval($money_to_add);

    global $pdo;
    $sql = "SELECT `balance` FROM `money` ORDER BY `id` DESC LIMIT 1";
    $curr_balance = $pdo->query($sql)->fetch();

    $curr_balance = floatval($curr_balance['balance']);
    //give_error(var_dump($curr_balance));
    $new_balance = $curr_balance - $money_to_substract;

    $statement = $pdo->prepare( "INSERT INTO `money` (`id`, `change_price`, `balance`, `updated_by`, `created_at`) VALUES (NULL, :money, :new_balance, :user, CURRENT_TIMESTAMP);" );
		$result    = $statement->execute( array(
			'money'    => $money_to_substract,
			'new_balance' => $new_balance,
			'user'  => $userid
		) );
    give_success("Amount added");
    break;
  default:
    //code...
    break;
}
give_result();
?>
