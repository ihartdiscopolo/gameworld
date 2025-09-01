<?php
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // when the empty cart button is used
    if (isset($_POST['emptyCart'])) {
        emptyCart();
    } elseif (isset($_POST['placeOrder'])) {
        placeOrder();
    }
}