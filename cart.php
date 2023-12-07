<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
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

        #total {
            background-color: lightcoral;
        }
  </style>
</head>
<body>
    <h2>Carrito</h2>


<?php
session_start();


$total = 0;

require "connection.php";
include "top.php";

//Si hay algo en el carrito lo muestra
if($_SESSION['stock'] != 0){
    ?>
        <table>
        <tr>
        <th>Id</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Subtotal</th>

        </tr>
        <tr>

    <?php

    foreach ($_SESSION["cart"] as $ids => $cant) {
        $sql = "SELECT * FROM products WHERE id=$ids";
    
        $result = $link->query($sql);
        $field = $result->fetch_assoc();
       
        while ($field !== null) {
         $id = $field['id'];
         $name = $field['name'];
         $price = $field['price']; 
         $subtotal = $price * $cant;
         $total += $subtotal;
         ?>
         <td><?=$id?></td>
          <td><?=$name?></td>
          <td><?=$price?></td>
          <td><?=$cant?></td>
          <td><?=$subtotal?></td>
        <?php 
          $field = $result->fetch_assoc();
          ?>
          </tr>
          <?php
       }
       $result->close();
    }
    ?>
    <tr>
    <td colspan="2"></td>
    <td colspan="2" id="total">Suma total del carrito</td>
    <td><?=$total?></td>
    
    </tr>
    </table>
    
<?php    

//Si el carrito está vacio, muestra un mensaje
} else {
    echo "<h3>El carrito está vacio</h3>";
}
?>

<br>
<a href="index.php">Volver a la página principal</a>

<form method="post" action="">
<button type="submit" name="empty_cart">Vaciar carrito</button>
</form>

<?php
if (isset($_POST['empty_cart'])) {
    // Llama a la función para vaciar el carrito
    emptyCart();
}

// Función para vaciar el carrito
function emptyCart() {
    // Elimina la sesión asociada al carrito
    unset($_SESSION['cart']);
    // Reinicia el contador total a cero
    $_SESSION['stock'] = 0;
}

?>
</body>
</html>
