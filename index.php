<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
  </style>
</head>
<body>
  <h2>Tienda</h2>

    <table>
    <tr>
      <th><a href="?sort=id">Id</a></th>
      <th><a href="?sort=name">Nombre</a></th>
      <th><a href="?sort=price">Precio</a></th>
      <th><a href="?sort=stock">Stock</a></th>
    </tr>
     <tr>

<?php

$sort = isset($_GET['sort']) ? $_GET['sort'] : "id";
setcookie('sort', $sort);


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
   <td id = "<?=$id?>"> <?=$amount?></td>
 <?php
   $field = $result->fetch_assoc();
   ?>
   </tr>
   <?php
 }
 $result->close();

}
?> 
     
     </table>
</body>
</html>