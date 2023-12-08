<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="style.css">
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
        <th></th>

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
          <td class = "td_buttons">
                <form method="post" action="">
                    <input type="hidden" name="product_id" value="<?=$id?>">
                    <input type="hidden" name="current_quantity" value="<?=$cant?>">
                    <button type="submit" name="add" class ="button">+</button>
                    <button type="submit" name="subtract" class ="button">-</button>
                </form> 
          </td>
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
    emptyCart($link);
}

// Función para vaciar el carrito
function emptyCart($link) {
    if (!isset($_SESSION['cart'])) {
        return; // No hay nada que hacer si el carrito está vacío
    }

    // Obtener la cantidad de cada producto antes de vaciar el carrito
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Sumar la cantidad al stock en la base de datos
        $sql = "UPDATE products SET amount = amount + ? WHERE id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        $stmt->close();
    }

    // Elimina la sesión asociada al carrito
    unset($_SESSION['cart']);
    // Reinicia el contador total a cero
    $_SESSION['stock'] = 0;
}


//FUNCIONALIDADES DE LOS BOTONES DE + Y -
if(isset($_POST["add"])){
    addCant($_POST['product_id']);
}


if(isset($_POST["subtract"])){
    subtract($_POST['product_id']);
}

//--------------------------



function addCant($product_id) {
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

        $productId = $_POST["product_id"];
        $currentQuantity = $_POST["current_quantity"];
        $_SESSION["cart"][$productId] = $currentQuantity + 1;
      
        } else {
        echo "<p class='error'>No hay stock suficiente</p>";
        }
    }
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
        //sumo uno al stock
        $sql2 = "UPDATE products SET amount = amount +1 WHERE id=$product_id";
        $result = $link->query($sql2);

        $productId = $_POST["product_id"];
        $currentQuantity = $_POST["current_quantity"];
        
        //Si la cantidad del producto en el carrito es mayor de 0
        if(isset($_SESSION["cart"][$productId]) && $_SESSION["cart"][$productId] > 0){
            //Se resta uno a la cantidad de ese producto en el carrito
            $_SESSION["cart"][$productId] = $currentQuantity - 1;
         //Si la cantidad llega a cero   
         if ($_SESSION["cart"][$productId] == 0) {
            //Se elimina del carrito
                unset($_SESSION["cart"][$productId]);
            }
        }
        
      
        }
    }
}

if(empty($_SESSION["cart"])){
    emptyCart($link);
}
    


?>
</body>
</html>
