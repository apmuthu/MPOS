<?php
$site_title = "Products / Items";
include 'inc/header.inc.php';
$user = check_user();

if (isset($_POST["barcode"])) {
  if (!empty($_POST['barcode'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $barcode = $_POST['barcode'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $article_color = $_POST['color'];

    global $pdo;
    $statement = $pdo->prepare( "INSERT INTO `articles` (`id`, `name`, `description`, `barcode`, `price`, `quantity`, `color`) VALUES (NULL, :name, :description, :barcode, :price, :quantity, :color);" );
		$result    = $statement->execute( array(
			'name'    => $name,
			'description' => $description,
			'barcode'  => $barcode,
			'price'  => $price,
			'quantity'  => $quantity,
      'color' => $article_color
    ));
  }
}
?>
<div class="row">
  <div class="col s9">
    <div class="row article-row">
<?php
$sql = "SELECT * FROM articles";
foreach ($pdo->query($sql) as $row) {
  echo '
    <div class="">
      <div class="card-panel article" style="background-color: '.$row['color'].'!important;">
        <h6 class="white-text">Item '.$row['id'].': <i>'.$row['name'].'</i></h6>
        <span class="white-text">Description: '.$row['description'].'</span>
        <br>
        <span class="white-text">Rate: '.$row['price'].'€</span>
        <br>
        <span class="white-text">Barcode: '.$row['barcode'].'</span>
        <div class="divider"></div>
        <div class="card-action row card-row">
          <a href="update_article.php?action=delete_article&article_id='.$row['id'].'"  class="btn-flat  col s4"><i class="material-icons">delete</i></a>
          <a href="update_article.php?action=update_quantity&article_id='.$row['id'].'" class="btn-flat col s4"><i class="material-icons">library_add</i></a>
          <a href="update_article.php?action=update_article&article_id='.$row['id'].'"  class="btn-flat col s4"><i class="material-icons">edit</i></a>
        </div>
      </div>
    </div>
  ';
}
  ?>
    </div>
  </div>
  <div class="col s3">
    <div class="collection">
      <a href="#add-article" class="collection-item modal-trigger">
        <i class="material-icons green-text">add</i>
        <span class="grey-text">Add Item</span>
      </a>
      <a href="#!" class="collection-item">
        <i class="material-icons blue-text">receipt</i>
        <span class="grey-text">Generate barcode</span>
      </a>
    </div>
  </div>
</div>
<div id="add-article" class="modal ">
    <div class="modal-content">
      <h4>Add Item</h4>
      <p>Fill in all fields!</p>
      <div class="row">
        <form class="col s12" id="add-article" action="articles.php" method="post">
          <div class="row">
            <div class="input-field col s6">
              <input id="name" name="name" type="text" class="validate" required>
              <label for="name">Item Name</label>
            </div>
            <div class="input-field col s6">
              <input id="description" name="description" type="text" class="validate" required>
              <label for="description">Item Description</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s6">
              <input id="price" type="text" name="price" class="validate" required>
              <label for="price">Unit Price</label>
            </div>
            <div class="input-field col s6">
              <input id="quantity" name="quantity" type="text" class="validate" required>
              <label for="quantity">Quantity</label>
            </div>
          </div>
          <div class="row">
            <div class="input-field col s12">
              <input id="barcode" name="barcode" type="text" class="validate" required>
              <label for="barcode">Barcode of Item</label>
            </div>
          </div>
          <div class="row">
            <div class="input-fiel col s12 m3">
              <label for="color-picker">Choose color:</label>
	            <input type="color" value="#ff8a80" id="color-picker" name="color">
            </div>
          </div>
          <div class="divider"></div>
          <br>
          <button type="submit" class="waves-effect waves-light green btn-flat col s12">Submit</button>
        </form>
      </div>
    </div>
  </div>
<?php
include 'inc/footer.inc.php';
 ?>
