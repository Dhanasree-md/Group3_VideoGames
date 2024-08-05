<?php
session_start();
require_once("DBHelper.php");
$db = new DBHelper();
$dbc = $db->getConnection();

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - NEXPLAY</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <header>
        <div class="banner">
            <h1>NEXPLAY</h1>
        </div>
        <nav class="navbar navbar-expand-lg">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">Cart</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">
        <h2>Your Shopping Cart</h2>
        <?php if (!empty($cart)): ?>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalAmount = 0;
                    foreach ($cart as $gameId => $item) {
                        $itemTotal = $item['quantity'] * $item['unit_price'];
                        $totalAmount += $itemTotal;
                        echo "<tr>
                            <td>{$item['title']}</td>
                            <td>{$item['quantity']}</td>
                            <td>\${$item['unit_price']}</td>
                            <td>\${$itemTotal}</td>
                        </tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                        <td><strong>$<?php echo number_format($totalAmount, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
            <div class="text-center">
                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is empty. <a href="index.php">Continue shopping</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>
