<!DOCTYPE html>
<html>
    <body>
        <?php
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            define("INITIALIZING_DB",1);
            require_once("db_conn.php");

            // Drop the existing database if it exists
            $dbc->query("DROP DATABASE IF EXISTS Videogames");

            // Create the Videogames database
            if($dbc->query("CREATE DATABASE Videogames") === TRUE){
                echo "Database Videogames created successfully.<br>";
            } else {
                echo "Error creating database: " . $dbc->error . "<br>";
            }

            // Use the Videogames database
            $dbc->query("USE Videogames");

            // Create the Customer table
            $createCustomerTable = "
                CREATE TABLE Customer (
                    CustomerID INT NOT NULL AUTO_INCREMENT,
                    FirstName VARCHAR(50) NOT NULL,
                    LastName VARCHAR(50) NOT NULL,
                    Email VARCHAR(100) NOT NULL,
                    Phone VARCHAR(15) NOT NULL,
                    Address VARCHAR(255) NOT NULL,
                    City VARCHAR(50) NOT NULL,
                    State VARCHAR(50) NOT NULL,
                    ZipCode VARCHAR(10) NOT NULL,
                    Country VARCHAR(50) NOT NULL,
                    PRIMARY KEY (CustomerID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if($dbc->query($createCustomerTable) === TRUE){
                echo "Table Customer created successfully.<br>";
            } else {
                echo "Error creating Customer table: " . $dbc->error . "<br>";
            }

            $dbc->query("INSERT INTO Customer (FirstName, LastName, Email, Phone, Address, City, State, ZipCode, Country) VALUES 
                ('John', 'Doe', 'john.doe@example.com', '1234567890', '123 Main St', 'Anytown', 'Anystate', '12345', 'USA'),
                ('Jane', 'Smith', 'jane.smith@example.com', '0987654321', '456 Elm St', 'Othertown', 'Otherstate', '54321', 'USA'),
                ('Alice', 'Johnson', 'alice.johnson@example.com', '1112223333', '789 Oak St', 'Anycity', 'Anystate', '11122', 'USA'),
                ('Bob', 'Brown', 'bob.brown@example.com', '4445556666', '321 Pine St', 'Somewhere', 'Somerstate', '22233', 'USA'),
                ('Charlie', 'Davis', 'charlie.davis@example.com', '7778889999', '654 Maple St', 'Elsewhere', 'Elsestate', '33344', 'USA')
            ");

            $createGenreTable = "
                CREATE TABLE Genre (
                    GenreID INT NOT NULL AUTO_INCREMENT,
                    GenreName VARCHAR(50) NOT NULL,
                    PRIMARY KEY (GenreID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if($dbc->query($createGenreTable) === TRUE){
                echo "Table Genre created successfully.<br>";
            } else {
                echo "Error creating Genre table: " . $dbc->error . "<br>";
            }

            $dbc->query("INSERT INTO Genre (GenreName) VALUES 
                ('Action'),
                ('Adventure'),
                ('RPG'),
                ('Simulation'),
                ('Strategy')
            ");

            $createPlatformTable = "
                CREATE TABLE Platform (
                    PlatformID INT NOT NULL AUTO_INCREMENT,
                    PlatformName VARCHAR(50) NOT NULL,
                    PRIMARY KEY (PlatformID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if($dbc->query($createPlatformTable) === TRUE){
                echo "Table Platform created successfully.<br>";
            } else {
                echo "Error creating Platform table: " . $dbc->error . "<br>";
            }

            
            $dbc->query("INSERT INTO Platform (PlatformName) VALUES 
                ('PC'),
                ('PlayStation'),
                ('Xbox'),
                ('Nintendo Switch'),
                ('Mobile')
            ");

            $createGameTable = "
                CREATE TABLE Game (
                    GameID INT NOT NULL AUTO_INCREMENT,
                    Title VARCHAR(100) NOT NULL,
                    Description TEXT NOT NULL,
                    GenreID INT NOT NULL,
                    PlatformID INT NOT NULL,
                    ReleaseDate DATE NOT NULL,
                    Price DECIMAL(10,2) NOT NULL,
                    StockQuantity INT NOT NULL,
                    Image VARCHAR(255) NOT NULL,
                    PRIMARY KEY (GameID),
                    FOREIGN KEY (GenreID) REFERENCES Genre(GenreID),
                    FOREIGN KEY (PlatformID) REFERENCES Platform(PlatformID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if($dbc->query($createGameTable) === TRUE){
                echo "Table Game created successfully.<br>";
            } else {
                echo "Error creating Game table: " . $dbc->error . "<br>";
            }

            
            $dbc->query("INSERT INTO Game (Title, Description, GenreID, PlatformID, ReleaseDate, Price, StockQuantity, Image) VALUES 
                ('Game One', 'Description for Game One', 1, 1, '2023-01-01', 59.99, 100, 'images/game1.jpg'),
                ('Game Two', 'Description for Game Two', 2, 2, '2023-02-01', 49.99, 150, 'images/game2.jpg'),
                ('Game Three', 'Description for Game Three', 3, 3, '2023-03-01', 39.99, 200, 'images/game3.jpg'),
                ('Game Four', 'Description for Game Four', 4, 4, '2023-04-01', 29.99, 250, 'images/game4.jpg'),
                ('Game Five', 'Description for Game Five', 5, 5, '2023-05-01', 19.99, 300, 'images/game5.jpg')
            ");

            
            $createOrderTable = "
                CREATE TABLE `Order` (
                    OrderID INT NOT NULL AUTO_INCREMENT,
                    CustomerID INT NOT NULL,
                    OrderDate DATE NOT NULL,
                    TotalAmount DECIMAL(10,2) NOT NULL,
                    ShippingAddress VARCHAR(255) NOT NULL,
                    BillingAddress VARCHAR(255) NOT NULL,
                    OrderStatus VARCHAR(50) NOT NULL,
                    PRIMARY KEY (OrderID),
                    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if($dbc->query($createOrderTable) === TRUE){
                echo "Table Order created successfully.<br>";
            } else {
                echo "Error creating Order table: " . $dbc->error . "<br>";
            }

            // Create the OrderItem table
            $createOrderItemTable = "
                CREATE TABLE OrderItem (
                    OrderItemID INT NOT NULL AUTO_INCREMENT,
                    OrderID INT NOT NULL,
                    GameID INT NOT NULL,
                    Quantity INT NOT NULL,
                    UnitPrice DECIMAL(10,2) NOT NULL,
                    PRIMARY KEY (OrderItemID),
                    FOREIGN KEY (OrderID) REFERENCES `Order`(OrderID),
                    FOREIGN KEY (GameID) REFERENCES Game(GameID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if($dbc->query($createOrderItemTable) === TRUE){
                echo "Table OrderItem created successfully.<br>";
            } else {
                echo "Error creating OrderItem table: " . $dbc->error . "<br>";
            }

            echo "<h2>Database Initialization Completed</h2><br>";
            echo "<a href='index.php'>Go to Home Page</a><br><br>";
        }
        ?>
        <form method="POST">
            <input type="submit" value="Initialize Database">
        </form>
    </body>
</html>
