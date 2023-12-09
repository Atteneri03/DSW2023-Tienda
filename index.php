<?php
// $sort = isset($_GET['sort']) ? $_GET['sort'] : isset($_COOKIE["sort"]) ? $_COOKIE['sort'] :"id";
if(isset($_GET['sort'])){
  $sort = $_GET['sort'];
} else if ( isset($_COOKIE["sort"])){
  $sort = $_COOKIE['sort'];
} else {
  $sort = "id";
}
setcookie('sort', $sort);

// Iniciamos la sesión o recuperamos la anterior existente
session_start();
// Comprobamos si la variable existe
if (!isset($_SESSION['stock'])){
  $_SESSION['stock']=0;
  $_SESSION['cart'] = array();
} 


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>Tienda</h2>
  <a href="cart.php">Ver Carrito</a>
  <a href="logout.php">Cerrar Sesión</a>


    <table>
    <tr>
      <th><a href="?sort=id">Id</a></th>
      <th><a href="?sort=name">Nombre</a></th>
      <th><a href="?sort=price">Precio</a></th>
      <th><a href="?sort=stock">Stock</a></th>
      <th> </th>

    </tr>
     <tr>

<?php

orderBy($sort);

function orderBy($sort){
  require "connection.php";
  include "top.php";

  //Por defecto ordena por id
  $sql = "SELECT * FROM products ORDER BY id";

  switch($sort){
    case "id": $sql = "SELECT * FROM products ORDER BY id";
      break;
    case "name": $sql = "SELECT * FROM products ORDER BY name";
      break;
    case "price":$sql = "SELECT * FROM products ORDER BY price";
      break;
    case "stock": $sql = "SELECT * FROM products ORDER BY amount";
      break; 
}
  $result = $link->query($sql);
  $field = $result->fetch_assoc();
 
  while ($field !== null) {
   $id = $field['id'];
   $name = $field['name'];
   $price = $field['price']; 
   $amount = $field['amount']; 
 
 ?>
   <td id = "<?=$id?>"><?=$id?></td>
   <td id = "<?=$id?>"><?=$name?></td>
   <td id = "<?=$id?>"><?=$price?></td>
   <td id = "<?=$id?>"><?=$amount?></td>
   
    <form method="post" action="">
      <input type="hidden" name="product_id" value="<?=$id?>">
      <td>
      <button type="submit" name="add_cart_button">&#128722;</button>
      </td>
    </form>
   
   <!-- <a href="prueba.php"><span style='font-size:20px;'>&#128722;</span></a> -->
   <!-- <input type="submit" name="add_cart_button" value="&#128722;"> -->
 <?php
   $field = $result->fetch_assoc();
   ?>
   </tr>
   <?php
 }
 $result->close();

}

if (isset($_POST['add_cart_button'])) {
  // Llama a la función cuando se pulsa el botón
   subtract($_POST['product_id']);

}



function subtract($product_id) {
  require "connection.php";
  include "top.php";

  //Busco cuanto stock hay de este producto
  $sql1 = "SELECT amount FROM products WHERE id = $product_id";
  $result = $link->query($sql1);

  $field = $result->fetch_assoc();
   $amount = $field['amount']; 

   //si no da error
  if($result){
    //y si el stock es mayor de 0
    if($amount > 0){
      //resto uno al stock
      $sql2 = "UPDATE products SET amount = amount -1 WHERE id=$product_id";
      $result = $link->query($sql2);
      echo "<p class='success'>Se ha añadido al carrito el producto con id $product_id</p>";
      //y lo añado al carrito
      addCart($_POST['product_id']);
    } else {
      echo "<p class='error'>No hay stock suficiente</p>";
    }
  }


}


function addCart($product_id) {
  $_SESSION['stock']++;
  if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Aumenta la cantidad del producto en el carrito
if (isset($_SESSION['cart'][$product_id])) {
  $_SESSION['cart'][$product_id]++;

} else {
  $_SESSION['cart'][$product_id] = 1;

}

}




// if(isset($_SESSION["cart"])){
// print_r($_SESSION["cart"]);
// }

?> 
     
     </table>
</body>
</html>