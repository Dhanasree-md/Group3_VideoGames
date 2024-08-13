<?php
session_start();
require_once 'generate_invoice.php';
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
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['FirstName'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['FirstName']); ?>!</span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <div class="container">
        <div class="row mb-4 justify-content-center">
            <div class="col-md-6">
               <h4>Thank You for ordering .!!</h4> 
               
               <form method="POST" action="">
               <button type="submit" name="download_invoice" class="btn btn-secondary">Download Invoice</button>
               </form>
               <a class="nav-link" href="index.php"> Browse More Games</a>
            </div>
        </div>
        
    </div>
</body>
</html>
