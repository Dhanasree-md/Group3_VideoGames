<?php
require_once 'OrderHandler.php';
require_once 'generate_invoice.php';

$orderHandler = new OrderHandler();
$orderId = 1; // Assuming an order ID is provided (this can be dynamic)
$orderDetails = $orderHandler->getOrderDetails($orderId);
$customerDetails = $orderHandler->getCustomerDetails($orderDetails['CustomerID']);
$orderItems = $orderHandler->getOrderItems($orderId);

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shippingAddress = $_POST['shipping_address'];
    $billingAddress = $_POST['billing_address'];
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Simple PHP validations
    if (empty($shippingAddress)) {
        $errors[] = "Shipping address is required.";
    }
    if (empty($billingAddress)) {
        $errors[] = "Billing address is required.";
    }
    if (empty($cardNumber) || !preg_match('/^\d{16}$/', $cardNumber)) {
        $errors[] = "Valid 16 digit card number is required.";
    }
    if (empty($expiryDate) || !preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) {
        $errors[] = "Valid expiry date is required (MM/YY).";
    }
    if (empty($cvv) || !preg_match('/^\d{3}$/', $cvv)) {
        $errors[] = "Valid CVV is required.";
    }

    if (empty($errors)) {
        if (isset($_POST['submit_order'])) {
           
            $orderHandler->updateOrder($orderId, $shippingAddress, $billingAddress);
            header("Location: success.php");
            exit();
           
        } elseif (isset($_POST['download_invoice'])) {
            // pdf - invoice generation
            generateInvoice($orderId);
            exit; 
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
        <div class="row mb-4 justify-content-center">
            <div class="col-md-8">
                <h2>Checkout</h2>
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
                    <h4>Order Details</h4>
                    <ul>
                        <?php 
                        $totalAmount = 0;
                        while ($item = $orderItems->fetch_assoc()) { 
                            $itemTotal = $item['Quantity'] * $item['UnitPrice'];
                            $totalAmount += $itemTotal;
                        ?>
                            <li>
                                <?php echo $item['Quantity'] . ' x ' . $item['Title']; ?><br>
                                <small>Genre: <?php echo $item['GenreName']; ?> | Platform: <?php echo $item['PlatformName']; ?></small><br>
                                <small>Price: $<?php echo number_format($itemTotal, 2); ?></small>
                            </li>
                        <?php } ?>
                    </ul>
                    <h4>Total Amount: $<?php echo number_format($totalAmount, 2); ?></h4>
                    <h4>Customer Details</h4>
                    <p>Name: <?php echo $customerDetails['FirstName'] . ' ' . $customerDetails['LastName']; ?></p>
                    <p>Email: <?php echo $customerDetails['Email']; ?></p>
                    <p>Phone: <?php echo $customerDetails['Phone']; ?></p>
                    <h4>Shipping Address</h4>
                    <input type="text" name="shipping_address" class="form-control" value="<?php echo htmlspecialchars($orderDetails['ShippingAddress']); ?>" >
                    <h4>Billing Address</h4>
                    <input type="text" name="billing_address" class="form-control" value="<?php echo htmlspecialchars($orderDetails['BillingAddress']); ?>" >
                    <h4>Payment Details</h4>
                    <input type="text" name="card_number" class="form-control" placeholder="Card Number" value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>"  >
                    <input type="text" name="expiry_date" class="form-control" placeholder="Expiry Date (MM/YY)" value="<?php echo isset($_POST['expiry_date']) ? htmlspecialchars($_POST['expiry_date']) : ''; ?>"  >
                    <input type="text" name="cvv" class="form-control" placeholder="CVV" value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>"  >
                    <br>
                    <button type="submit" name="submit_order" class="btn btn-primary">Submit Order</button>
                    <button type="submit" name="download_invoice" class="btn btn-secondary">Download Invoice</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
