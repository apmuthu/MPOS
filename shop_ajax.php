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


function get_article( $barcode ) {
	global $pdo;
	$statement = $pdo->prepare( "SELECT * FROM articles WHERE barcode = :barcode" );
	$statement->execute( array( "barcode" => $barcode ) );
	$result = $statement->fetch( PDO::FETCH_ASSOC );
	if ( $result != [] ) {
		return $result;
	} else {
		return false;
	}
}

$result = false;
if ( ! check_user() ) {
	give_error( "No registered user logged in" );
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
	case "get_cart":
		$result = $_SESSION["cart"];
		give_result();
		break;
	case "add_to_cart":
		if ( empty( $_GET["barcode"] ) ) {
			give_error( "A barcode must be specified." );
		}
		if ( empty( $_GET["quantity"] ) ) {
			give_error( "Quantity has to be specified." );
		}
		$barcode  = $_GET["barcode"];
		$quantity = $_GET["quantity"];

		$article = get_article( $barcode );
		if ( ! $article ) {
			give_error( "Unfortunately this article does not exist." );
		}

		if ( ! is_numeric( $quantity ) || ! ( doubleval( $quantity ) > 0 ) ) {
			give_error( "The quantity must be a positive numerical value." );
		}
		$quantity = doubleval( $quantity );

		$needed_quantity = $quantity;
		$key             = array_search( $barcode, array_column( $_SESSION["cart"], "barcode" ) );
		if ( $key !== false ) {
			// Already exists
			$needed_quantity += $_SESSION["cart"][ $key ]["quantity"];
		}

		$available_quantity = $article["quantity"];

		if ( ! ( $quantity <= $available_quantity ) ) {
			give_error( "The selected quantity is unfortunately not in stock. Available stock:" . $available_quantity . " Stück" );
		}

		if ( $key === false ) {
			//Does not exist
			$cart_object = array(
				"barcode"  => $barcode,
				"quantity" => $quantity,
				"article"  => $article,

			);

			array_push( $_SESSION["cart"], $cart_object );

			give_success( "The Item was added successfully" );
		} else {
			$_SESSION["cart"][ $key ]["quantity"] += $quantity;
			give_success( "The quantity was successfully added to the existing Item." );
		}

		break;
	case "delete_cart":
		if ( isset( $_SESSION["cart"] ) ) {
			unset( $_SESSION["cart"] );
		}
		give_success( "The shopping cart has been emptied." );
		break;
	default:
		give_error( "The specified action does not exist." );
}

give_result();