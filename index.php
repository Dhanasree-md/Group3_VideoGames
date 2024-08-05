<?php
session_start();
require_once("db_conn.php");

// Initialize the cart session variable if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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
                            <?php
                            $result = $dbc->query("SELECT * FROM Genre");
                            while ($row = $result->fetch_assoc()) {
                                $selected = (isset($_GET['genre']) && $_GET['genre'] == $row['GenreID']) ? "selected" : "";
                                echo "<option value='" . $row['GenreID'] . "' $selected>" . $row['GenreName'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Filter</button>
                </form>
            </div>
        </div>
        <div class="row" id="games-list">
            <?php
            $query = "SELECT Game.*, Genre.GenreName, Platform.PlatformName 
                      FROM Game 
                      JOIN Genre ON Game.GenreID = Genre.GenreID 
                      JOIN Platform ON Game.PlatformID = Platform.PlatformID";

            if (isset($_GET['genre']) && !empty($_GET['genre'])) {
                $query .= " WHERE Game.GenreID = ?";
                $stmt = $dbc->prepare($query);
                $stmt->bind_param('i', $_GET['genre']);
            } else {
                $stmt = $dbc->prepare($query);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-4 game-card'>
                        <div class='card'>
                            <img src='" . $row['Image'] . "' class='card-img-top' alt='" . $row['Title'] . "'>
                            <div class='card-body'>
                                <h5 class='card-title'>" . $row['Title'] . "</h5>
                                <p class='card-text'>Genre: " . $row['GenreName'] . "</p>
                                <p class='card-text'>Platform: " . $row['PlatformName'] . "</p>
                                <p class='card-text'>Price: $" . $row['Price'] . "</p>
                                <p class='card-text'>" . $row['Description'] . "</p>
                                <form action='add_to_cart.php' method='POST'>
                                    <input type='hidden' name='game_id' value='" . $row['GameID'] . "'>
                                    <input type='hidden' name='price' value='" . $row['Price'] . "'>
                                    <button type='submit' class='btn btn-primary'>Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
