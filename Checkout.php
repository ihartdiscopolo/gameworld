<?php

include 'Functions.php';
htmlHead();
// start or resume session
if (!isset($_SESSION['cart'])) {
    // if not, create index cart in $_SESSION array
    $_SESSION['cart'] = []; // empty array
}
?>
<table>
    <thead>
        <tr>
            <th>Game Id</th>
            <th>Game Name</th>
            <th>Game Image</th>
            <th>Game Price</th>
            <th>Quantity</th>
            <th>Sub Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($_SESSION['cart']) > 0) {

            $totalPrice = 0;
            foreach ($_SESSION['cart'] as $gameId => $quantity) {
                $game = getGame($gameId);
                $subTotal = $game['gamePrice'] * $quantity;
                ?>
                <tr>
                    <td><?php echo $game['gameId']; ?></td>
                    <td><?php echo $game['gameName']; ?></td>
                    <td><img src="images/<?php echo $game['gameImage']; ?>" style="width:100px;" /></td>
                    <td>&euro; <?php echo $game['gamePrice']; ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td>&euro; <?php echo $subTotal; ?></td>
                </tr>
            <?php
                $totalPrice += $subTotal;
            }
            ?>
    </tbody>
    <tfoot>
        <tr>
        <tr>
            <td colspan="5">Total:</td>
            <td>&euro; <?php echo $totalPrice; ?></td>
        <tr>
            <?php

            if (isset($_SESSION['user'])) {
            ?>
                <td>
                    <form method="post" action="Order.php">
                        <button type="submit" name="placeOrder" onclick="return confirm('This will place your order to the database!\nContinue?');">
                            Place order!
                        </button>
                    </form>
                </td>
            <?php
            } else {
                ?>
                <td colspan='6'>Please <a href="login.php">log in</a> to place an order.</td>
                <?php
            }
            ?>
            <td>
                <a href="Products.php" class="btn btn-primary">Continue</a>
            </td>
            <td>
                <form method="post" action="Order.php">
                    <button type="submit" name="emptyCart" onclick="return confirm('Are you sure you want to empty your shopping cart?');">
                        Empty shopping cart
                    </button>
                </form>
            </td>
        </tr>
    </tfoot>
    <?php
        } else 
        {
            echo "<tr><td colspan='6'>No items in the shopping cart.</td></tr></tbody>";  // display message if cart is empty
        }
    ?>
    </table>

