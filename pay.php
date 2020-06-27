<?php
session_start();
$site_title = "Pay";
include 'inc/header.inc.php';
$user = check_user();
if (!$_SESSION['cart']) {
  header("Location:shop_form.php");
}
?>
<script>
function calculateChange(money_given) {
  var gpreis = <?=$_SESSION['gpreis']?>;
  document.getElementById('change').value = "Change: " + (money_given - gpreis) + "€";
}
  $(document).ready(function () {
    $('#bill').hide();
    $('#cash_given').on('input', function(){
      calculateChange(this.value)
    });
    $('#payment').on('change', function() {
      if (this.value == 'credit') {
        $('#cash_given').val(parseFloat(<?=$_SESSION['gpreis']?>));
        calculateChange($('#cash_given').value)
      }else if (this.value == 'cash') {
        $('#cash_given').val("");
        calculateChange($('#cash_given').value)
      }else if (this.value == 'gift') {
        $('#cash_given').val("");
        calculateChange($('#cash_given').value)
      }
    });
    $('#paid').change(function(){
      console.log("it changed");
      var c = this.checked;
      switch (c) {
        case true:
          $('#bill').show();
          break;
        default:
          $('#bill').hide();
        }
      });
      $('#pay_form').submit(function(e) {
        e.preventDefault();
        url = "money_ajax.php?action=add_money&money=" + <?=$_SESSION['gpreis']?> + "&user=" + "<?=$_SESSION['userid']?>";
        $.getJSON(url, function (data) {
            console.log("[ACTION RESULT OF add_money]");
            console.log(data);
            if (data.success || data.error) {
                var html = "";
                if (data.success) {
                    html += '<i class="material-icons left">check</i>';
                } else if (data.error) {
                    html += '<i class="material-icons left">error</i>';
                }
                html += data.message;
                M.toast({html: html});
                window.location.replace("print_bill.php?type=" + $("#payment option:selected").text());
            }
          });
      });
  });
</script>
<h1 class="<?=$site_color_accent_text?>">Pay</h1>
<p>On the left you can see the overview of purchased items, on the right you can cash in.</p>
<div class="row">
  <div class="col s12 m6">
    <table class="striped">
      <thead>
      <tr>
          <th>Quantity</th>
          <th>Barcode</th>
          <th>Item Name</th>
          <th>Rate</th>
          <th>Amount</th>
      </tr>
      </thead>
      <?php
      $gpreis = 0;
      foreach ($_SESSION['cart'] as $value) {
        $gpreis += $value['quantity'] * $value['article']['price'];
        echo "<tr>".
        "<td>" .
        $value['quantity'] .
        "</td>" .
        "<td>" .
        $value['article']['barcode'] .
        "</td>" .
        "<td>" .
        $value['article']['name'] .
        "</td>" .
        "<td>" .
        $value['article']['price'] . "€" .
        "</td>" .
        "<td>" .
        $value['quantity'] * $value['article']['price'] . "€" .
        "</td>" .
        "</tr>";
      }
      echo "<tr>" .
      "<th colspan='4'>" .
      "Total:" .
      "</th>" .
      "<th>" .
      $gpreis . "€" .
      "</th>" .
      "</tr>";
      $_SESSION['gpreis'] = $gpreis;
       ?>
    </table>
  </div>
  <div class="col s12 m6">
    <form method="post" action="print_bill.php" id="pay_form">
      <div class="input-field col s12">
        <select name="payment" id="payment" required>
          <option value="" disabled selected>Select a payment method</option>
          <option value="cash" data-icon="icons/payment_options/money.svg" class="left">Cash</option>
          <option value="credit" data-icon="icons/payment_options/credit_card.svg" class="left">Card payment</option>
          <option value="gift" data-icon="icons/payment_options/card_giftcard.svg" class="left">Gift Voucher</option>
        </select>
      </div>
      <div class="input-field col s12">
        <i class="material-icons prefix">attach_money</i>
        <input id="cash_given" type="text" name="money" placeholder="Given money" required>
        <!--<label for="cash_given">Given money</label>-->
      </div>
      <!-- Discount only in future, not now!-->
      <!--<div class="input-field col s12">
        <i class="material-icons prefix">stars</i>
        <input id="discount" type="text" name="discount" value="0%">
        <label for="discount">Discount for whole purchase</label>
      </div>-->
      <div class="input-field col s12">
        <i class="material-icons prefix">settings_backup_restore</i>
        <input id="change" type="text" name="discount" value="Rückgeld: " disabled>
      </div>
      <div class="col s12">
        <label>
          <input type="checkbox" name="paid" required id="paid"/>
          <span>Payment made</span>
        </label>
      </div>
      <button type="submit" class="btn waves-effect waves-light <?=$site_color?> col s12" id="bill"><i class="material-icons right">print</i>Print invoice</button>
    </form>
  </div>
</div>
<?php
include 'inc/footer.inc.php';
?>
