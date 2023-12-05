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
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda</title>
  <style>
   table, td, th {
  border: 1px solid;
}

th {
  background-color: lightblue;
}

table {
  width: 70%;
  border-collapse: collapse;
}
/* 
input {
  border: none;
  background: none;
} */
  </style>
</head>
<body>
  <h2>Tienda</h2>
  <h3>Carrito: <?=$_SESSION['stock']?></h3>

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
   <!-- No funciona el carrito -->
   <td><input type="button" name="add_cart_button" value="&#128722;" onclick="location.href='addCart.php'"></td> 
   <!-- <a href="prueba.php"><span style='font-size:20px;'>&#128722;</span></a> -->
 <?php
   $field = $result->fetch_assoc();
   ?>
   </tr>
   <?php
 }
 $result->close();

}

function addCart() {
  $_SESSION['stock']++;

}

?> 
     
     </table>
</body>
</html>