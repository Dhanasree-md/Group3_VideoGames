<?php
class DBHelper
{

    const DB_USER = 'root';
    const DB_PASSWORD = '';
    const DB_HOST = 'localhost';
    const DB_NAME = 'Videogames';
    const CHARSET = 'utf8mb4';

    protected $sqlStatement = "";
    protected $params = null;
    protected $stmt = null;

    static protected $dbc = null;

    static function initializeDatabase()
    {
        try {
            $dbc = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD);

            if ($dbc->connect_error) {
                throw new Exception("Connection failed: " . $dbc->connect_error);
            }
            $dbc->query("DROP DATABASE IF EXISTS Videogames");

            if ($dbc->query("CREATE DATABASE Videogames") === TRUE) {
                echo "Database Videogames created successfully.<br>";
            } else {
                echo "Error creating database: " . $dbc->error . "<br>";
            }

            $dbc->query("USE Videogames");

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
                    PasswordHash VARCHAR(255) NOT NULL,
                    PRIMARY KEY (CustomerID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if ($dbc->query($createCustomerTable) === TRUE) {
                echo "Table Customer created successfully.<br>";
            } else {
                echo "Error creating Customer table: " . $dbc->error . "<br>";
            }

           

            $createGenreTable = "
                CREATE TABLE Genre (
                    GenreID INT NOT NULL AUTO_INCREMENT,
                    GenreName VARCHAR(50) NOT NULL,
                    PRIMARY KEY (GenreID)
                ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
            ";
            if ($dbc->query($createGenreTable) === TRUE) {
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
            if ($dbc->query($createPlatformTable) === TRUE) {
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
            if ($dbc->query($createGameTable) === TRUE) {
                echo "Table Game created successfully.<br>";
            } else {
                echo "Error creating Game table: " . $dbc->error . "<br>";
            }


            $dbc->query("INSERT INTO Game (Title, Description, GenreID, PlatformID, ReleaseDate, Price, StockQuantity, Image) VALUES 
                ('The Legend of Zelda', 'An action-adventure game set in a large open world.', 2, 4, '2017-03-03', 59.99, 50, '../images/zelda.jpg'),
                ('Red Dead Redemption 2', 'An epic tale of life in America at the dawn of the modern age.', 1, 3, '2018-10-26', 59.99, 40, '../images/red_dead_redemption_2.jpg'),
                ('The Witcher 3', 'A story-driven, next-generation open world role-playing game.', 3, 1, '2015-05-18', 49.99, 60, '../images/witcher_3.jpg'),
                ('Stardew Valley', 'A farming simulation game with RPG elements.', 4, 1, '2016-02-26', 14.99, 100, '../images/stardew_valley.jpg'),
                ('Civilization VI', 'A turn-based strategy game where you build an empire to stand the test of time.', 5, 1, '2016-10-21', 59.99, 30, '../images/civilization_vi.jpg'),
                ('Minecraft', 'A sandbox game that allows players to build and explore their own worlds.', 4, 1, '2011-11-18', 26.95, 150, '../images/minecraft.jpg'),
                ('God of War', 'An action-adventure game based on Greek mythology.', 1, 2, '2018-04-20', 39.99, 70, '../images/god_of_war.jpg'),
                ('Hades', 'A rogue-like dungeon crawler where you defy the god of the dead.', 3, 1, '2020-09-17', 24.99, 80, '../images/hades.jpg'),
                ('Animal Crossing', 'A life simulation game where you develop a deserted island.', 4, 4, '2020-03-20', 59.99, 90, '../images/animal_crossing.jpg'),
                ('Halo Infinite', 'A first-person shooter game that continues the story of the Master Chief.', 1, 3, '2021-12-08', 59.99, 65, '../images/halo_infinite.jpg')
            ");


            $createOrderTable = "
                CREATE TABLE `Order` (
                 OrderID INT NOT NULL AUTO_INCREMENT,
                 CustomerID INT NOT NULL,
                 OrderDate DATE NOT NULL,
                TotalAmount DECIMAL(10,2) NOT NULL,
                 ShippingAddress VARCHAR(255) NOT NULL,
                BillingAddress VARCHAR(255) NOT NULL,
                 OrderStatus ENUM('Pending', 'Completed') NOT NULL,
                  PRIMARY KEY (OrderID),
              FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4
                    ";
            if ($dbc->query($createOrderTable) === TRUE) {
                echo "Table Order created successfully.<br>";
            } else {
                echo "Error creating Order table: " . $dbc->error . "<br>";
            }

          

            
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
            if ($dbc->query($createOrderItemTable) === TRUE) {
                echo "Table OrderItem created successfully.<br>";
            } else {
                echo "Error creating OrderItem table: " . $dbc->error . "<br>";
            }

            
            echo "<h2>Database Initialization Completed</h2><br>";
            echo "<a href='../Joemol-8912316/index.php'>Go to Home Page</a><br><br>";
            $dbc->close();
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
   


    function __construct()
    {
        if (self::$dbc == null) {
            try {
                
               // self::$dbc = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD);
                //self::$dbc = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);

                if (defined('INITIALIZING_DB')) {
                   
                    self::$dbc = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD);
                } else {
                    
                    self::$dbc = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);
                }
                if (self::$dbc->connect_error) {
                    throw new Exception("Connection failed: " . self::$dbc->connect_error);
                }

               self::$dbc->set_charset(self::CHARSET);
            } catch (Exception $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    function getConnection()
    {
        return self::$dbc;
    }

    function getRowCount()
    {
        return $this->stmt->num_rows;
    }


    


   
}

