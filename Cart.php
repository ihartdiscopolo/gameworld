<?php
include 'Functions.php';
// start or resume session

// check if there is a cart
if (!isset($_SESSION['cart'])) {
    // if not, create index cart in $_SESSION array
    $_SESSION['cart'] = []; 
}

// if there is something posted (gameId and gameQuantity)
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // get gameId and quantity from POST request
    // save them in variables to use them later in the script
    $gameId = $_POST['gameId'];    $_SESSION['cart'][$gameId] = $_POST['gameQuantity'];
}
header("location: Checkout.php");