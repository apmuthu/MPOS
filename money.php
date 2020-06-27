<?php
$site_title = "Cash flow";
include 'inc/header.inc.php';
$user = check_user();
if (isset($_POST['sum'])) {
  if (!empty($_POST['sum'])) {
    $money_to_add = $_POST['sum'];
    $userid = $user['id'];

    $money_to_add = floatval($money_to_add);

    global $pdo;
    $sql = "SELECT `balance` FROM `money` ORDER BY `id` DESC LIMIT 1";
    $curr_balance = $pdo->query($sql)->fetch();

    $curr_balance = floatval($curr_balance['balance']);
    $new_balance = $curr_balance + $money_to_add;

    $statement = $pdo->prepare( "INSERT INTO `money` (`id`, `change_price`, `balance`, `updated_by`, `created_at`) VALUES (NULL, :money, :new_balance, :user, CURRENT_TIMESTAMP);" );
		$result    = $statement->execute( array(
			'money'    => $money_to_add,
			'new_balance' => $new_balance,
			'user'  => $userid
		) );
  }
}
if (isset($_POST['diff'])) {
  if (!empty($_POST['diff'])) {
    $money_to_substract = $_POST['diff'];
    $userid = $user['id'];

    $money_to_substract = floatval($money_to_substract);

    global $pdo;
    $sql = "SELECT `balance` FROM `money` ORDER BY `id` DESC LIMIT 1";
    $curr_balance = $pdo->query($sql)->fetch();

    $curr_balance = floatval($curr_balance['balance']);
    $new_balance = $curr_balance - $money_to_substract;

    $statement = $pdo->prepare( "INSERT INTO `money` (`id`, `change_price`, `balance`, `updated_by`, `created_at`) VALUES (NULL, :money, :new_balance, :user, CURRENT_TIMESTAMP);" );
		$result    = $statement->execute( array(
			'money'    => $money_to_substract,
			'new_balance' => $new_balance,
			'user'  => $userid
		) );
  }
}
?>
<?php
//############################################################################################################################
// IDEA:  Make table smaller, then split into several tables across pages (refer https://materializecss.com/pagination.html)
//############################################################################################################################
?>
<script>
name_data = $.getJSON("money_ajax.php?action=get_users");

function updateTable() {
  $.getJSON("money_ajax.php?action=get_balance", function (data) {
      console.log(data);
      $("#money-table tbody").empty();
      $.each(data, function (index, value) {
          $("#money-table tbody").append("<tr>" +
              "<td>" +
              value.id +
              "</td>" +
              "<td>" +
              value.change_price + "€" +
              "</td>" +
              "<td>" +
              value.balance + "€" +
              "</td>" +
              "<td class= 'id" + value.updated_by + "'>" +
              value.updated_by +
              "</td>" +
              "<td>" +
              value.created_at +
              "</td>" +
              "</tr>")
      });
  });
  updateUsers();
}
function updateUsers() {
  $.getJSON("money_ajax.php?action=get_users", function (name_data) {
    $.each(name_data, function (index, value) {
      console.log("id" + value.id);
      $(".id"+value.id).text(value.vorname + " " + value.nachname);
    });
  });
}
function getCurrBalance(id) {
  $.getJSON("money_ajax.php?action=get_curr_balance", function (data) {
      $('#currBalance').text(data.balance + "€");
  });
}
$(document).ready(function () {
        getCurrBalance();
        updateTable();
        $("#reload").click(function(){
          getCurrBalance();
          updateTable();
        });
});
</script>
<div class="row">
  <div class="col s8">
    <table class="highlight" id="money-table">
      <thead>
        <tr>
          <th>Booking-ID</th>
          <th>Changes</th>
          <th>Amount Balance</th>
          <th>Changed by</th>
          <th>Changed on</th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td colspan="5">
            No payments available!
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="col s4">
    <div class="card grey lighten-3 card_padding">
      <h6 class="<?=$site_color_text?> center">CURRENT ACCOUNT:</h6>
      <h1 class="<?=$site_color_text?> center" id="currBalance"></h1>
      <a class="btn-floating halfway-fab waves-effect waves-light <?=$site_color_accent?> btn-large" id="reload"><i class="material-icons">refresh</i></a>
    </div>
    <div class="collection">
        <a href="#add_money" class="collection-item modal-trigger">
          <i class="material-icons red-text">add</i>
          <span class="grey-text">Deposit money</span>
        </a>
        <a href="#substract_money" class="collection-item modal-trigger">
          <i class="material-icons blue-text">remove</i>
          <span class="grey-text">Pay out money</span>
        </a>
        <a href="#!" class="collection-item">
          <i class="material-icons green-text">print</i>
          <span class="grey-text">Print List</span>
        </a>
    </div>
  </div>
</div>

<!-- MODALS-->
<div id="add_money" class="modal ">
    <div class="modal-content">
      <h4>Deposit money</h4>
      <p>Fill in all fields!</p>
      <div class="row">
        <form class="col s12" id="add-article" action="money.php" method="post">
          <div class="row">
            <div class="input-field col s6">
              <input id="sum" name="sum" type="text" class="validate" required>
              <label for="sum">Total</label>
            </div>
          </div>
          <div class="divider"></div>
          <br>
          <button type="submit" class="waves-effect waves-light green btn-flat col s12">Deposit</button>
        </form>
      </div>
    </div>
  </div>
  <div id="substract_money" class="modal ">
      <div class="modal-content">
        <h4>Pay out money</h4>
        <p>Fill in all fields!</p>
        <div class="row">
          <form class="col s12" id="add-article" action="money.php" method="post">
            <div class="row">
              <div class="input-field col s6">
                <input id="diff" name="diff" type="text" class="validate" required>
                <label for="sum">Money</label>
              </div>
            </div>
            <div class="divider"></div>
            <br>
            <button type="submit" class="waves-effect waves-light orange btn-flat col s12">Pay off</button>
          </form>
        </div>
      </div>
    </div>
<?php
include 'inc/footer.inc.php';
?>
