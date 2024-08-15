<?php
session_start();
require_once 'generate_invoice.php';
require_once 'OrderHandler.php';
$orderHandler = new OrderHandler();
if (isset($_SESSION['order_id'])) {
 
    $orderId = $_SESSION['order_id']; 
    $orderDetails = $orderHandler->getOrderDetails($orderId);
$customerDetails = $orderHandler->getCustomerDetails($orderDetails['CustomerID']);
$orderItems = $orderHandler->getOrderItems($orderId);
}
if (isset($_POST['download_invoice'])) {
    if (isset($_SESSION['order_id'])) {
       $orderId = $_SESSION['order_id']; 
    generateInvoice($orderId);
    exit; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXPLAY</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="../styles.css" rel="stylesheet">
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
                        <a class="nav-link" href="../Joemol-8912316/index.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Alex-8912704/cart.php">Cart</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['FirstName'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['FirstName']); ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../Joemol-8912316/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../Alitta-8910283/login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">
        <div class="row mb-4 justify-content-center">
        
            <div class="col-md-6">
            <h1>Thank You for ordering .!!</h1> 
               <div class="card mb-4">
                        <div class="card-header">
                           <h2>Order Details</h2> 
                        </div>
                        <div class="card-body">
                            <ul>
                                <?php 
                                $totalAmount = 0;
                                while ($item = $orderItems->fetch_assoc()) { 
                                    $itemTotal = $item['Quantity'] * $item['UnitPrice'];
                                    $totalAmount += $itemTotal;
                                ?>
                                    <li>
                                        <strong><?php echo $item['Quantity'] . ' x ' . $item['Title']; ?></strong><br>
                                        <small>Genre: <?php echo $item['GenreName']; ?> | Platform: <?php echo $item['PlatformName']; ?></small><br>
                                        <small>Price: $<?php echo number_format($itemTotal, 2); ?></small>
                                    </li>
                                <?php } ?>
                            </ul>
                            <h4 class="mt-3">Total Amount: $<?php echo number_format($totalAmount, 2); ?></h4>
                        </div>
                    </div>
               
               <form method="POST" action="" target="_blank">
               <button type="submit" name="download_invoice" class="btn btn-primary">Download Invoice</button>
               </form><br>
               <p><a class="btn btn-primary" href="../Joemol-8912316/index.php"> Browse More Games</a></p>
            </div>
        </div>
        
    </div>
</body>
</html>
