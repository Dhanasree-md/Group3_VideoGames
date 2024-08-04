<?php
require_once("db_conn.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Games Store</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .game-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Video Games Store</h1>
        <div class="row mb-4">
            <div class="col-md-3">
                <h4>Filter by Genre</h4>
                <form id="filter-form" method="GET" action="">
                    <div class="form-group">
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
                    <button type="submit" class="btn btn-primary">Filter</button>
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
                                <a href='add_to_cart.php?game_id=" . $row['GameID'] . "' class='btn btn-primary'>Add to Cart</a>
                            </div>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
