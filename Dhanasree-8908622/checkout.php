<?php
session_start();
require_once 'OrderHandler.php';
//require_once 'generate_invoice.php';
$orderHandler = new OrderHandler();
if (isset($_SESSION['order_id'])) {
 
    $orderId = $_SESSION['order_id']; 
    $orderDetails = $orderHandler->getOrderDetails($orderId);
$customerDetails = $orderHandler->getCustomerDetails($orderDetails['CustomerID']);
$orderItems = $orderHandler->getOrderItems($orderId);
}



$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_order'])) {
    $shippingAddress = $_POST['shipping_address'];
    $billingAddress = $_POST['billing_address'];
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];
    $currentDate = new DateTime();
    $expiryDateObject = DateTime::createFromFormat('m/y', $expiryDate);

 
    if (empty($shippingAddress)) {
        $errors[] = "Shipping address is required.";
    }
    if (empty($billingAddress)) {
        $errors[] = "Billing address is required.";
    }
    if (empty($cardNumber) || !preg_match('/^\d{16}$/', $cardNumber)) {
        $errors[] = "Valid 16 digit card number is required.";
    }
    // if (empty($expiryDate) || !preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) {
    //     $errors[] = "Valid expiry date is required (MM/YY).";
    // }
    if (empty($expiryDate) || !preg_match('/^\d{2}\/\d{2}$/', $expiryDate) || $expiryDateObject < $currentDate) {
        $errors[] = "Valid expiry date is required (MM/YY) and should not be earlier than the current month.";
    }
    if (empty($cvv) || !preg_match('/^\d{3}$/', $cvv)) {
        $errors[] = "Valid CVV is required.";
    }
    if (empty($errors)) { 
           
        $orderHandler->updateOrder($orderId, $shippingAddress, $billingAddress);
        if (isset($_SESSION['cart'])) {
            if (is_string($_SESSION['cart'])) {
                
                //$cart->clearCart();
                $_SESSION['cart'] = [] ;
            } 
        }
      
        header("Location: success.php");
        exit();
       
    }
    }    
    
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - NEXPLAY</title>
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
            <div class="col-md-8">
                <h1>Checkout</h1>
                <?php if (!empty($errors)) { ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error) { ?>
                                <li><?php echo $error; ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <form method="POST" action="">
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

                    <div class="card mb-4">
                        <div class="card-header">
                            Customer Details
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> <?php echo $customerDetails['FirstName'] . ' ' . $customerDetails['LastName']; ?></p>
                            <p><strong>Email:</strong> <?php echo $customerDetails['Email']; ?></p>
                            <p><strong>Phone:</strong> <?php echo $customerDetails['Phone']; ?></p>
                        </div>
                  
                        <div class="card-header">
                            Shipping Address
                        </div>
                        <div class="card-body">
                            <input type="text" name="shipping_address" class="form-control" value="<?php echo htmlspecialchars($customerDetails['Address'] . ', ' . $customerDetails['City'] . ', ' . $customerDetails['State'] . ', ' . $customerDetails['ZipCode'] . ', ' . $customerDetails['Country']); ?>" >
                        </div>
                    
                        <div class="card-header">
                            Billing Address
                        </div>
                        <div class="card-body">
                            <input type="text" name="billing_address" class="form-control" value="<?php echo htmlspecialchars($customerDetails['Address'] . ', ' . $customerDetails['City'] . ', ' . $customerDetails['State'] . ', ' . $customerDetails['ZipCode'] . ', ' . $customerDetails['Country']); ?>" >
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            Payment Details
                        </div>
                        <div class="card-body">
                            <input type="text" name="card_number" class="form-control mb-2" placeholder="Card Number" value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>"  >
                            <input type="text" name="expiry_date" class="form-control mb-2" placeholder="Expiry Date (MM/YY)" value="<?php echo isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : ''; ?>"  >
                            <input type="text" name="cvv" class="form-control mb-2" placeholder="CVV" value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>"  >
                        </div>
                    </div>

                    <button type="submit" name="submit_order" class="btn btn-primary btn-block">Submit Order</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
