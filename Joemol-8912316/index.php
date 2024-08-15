<?php
session_start();
require_once 'GenreHandler.php';
require_once 'ProductHandler.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$genreHandler = new GenreHandler();
$productHandler = new ProductHandler();

$genres = $genreHandler->getGenres();$products = isset($_GET['genre']) ? $productHandler->getProducts($_GET['genre']) : $productHandler->getProducts();
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
                        <a class="nav-link" href="index.php">Shop</a>
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
                            <a class="nav-link" href="logout.php">Logout</a>
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
                <form id="filter-form" method="GET" action="" class="form-inline justify-content-center">
                    <div class="form-group mx-sm-3 mb-2">
                        <select class="form-control" id="genre-filter" name="genre">
                            <option value="">All Genres</option>
                            <?php foreach ($genres as $g): ?>
                                <option value="<?php echo $g['GenreID']; ?>" 
                                    <?php echo (isset($_GET['genre']) && $_GET['genre'] == $g['GenreID']) ? "selected" : ""; ?>>
                                    <?php echo htmlspecialchars($g['GenreName']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                </form>
            </div>
        </div>
        
        <div class="row" id="games-list">
            <?php foreach ($products as $p): ?>
                <div class="col-md-4 game-card">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($p['Image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($p['Title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($p['Title']); ?></h5>
                            <p class="card-text">Genre: <?php echo htmlspecialchars($p['GenreName']); ?></p>
                            <p class="card-text">Platform: <?php echo htmlspecialchars($p['PlatformName']); ?></p>
                            <p class="card-text">Price: $<?php echo htmlspecialchars($p['Price']); ?></p>
                            <p class="card-text"><?php echo htmlspecialchars($p['Description']); ?></p>
                            <form action="../Alex-8912704/add_to_cart.php" method="POST">
                                <input type='hidden' name='game_id' value='<?php echo $p['GameID']; ?>'>
                                <input type='hidden' name='price' value='<?php echo $p['Price']; ?>'>
                                <button type='submit' class='btn btn-primary'>Add to Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
