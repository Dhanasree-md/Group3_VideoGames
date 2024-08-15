<?php
require_once '../Dhanasree-8908622/DBHelper.php';

class ProductHandler {
    private $db;

    public function __construct() {
        $dbHelper = new DBHelper();
        $this->db = $dbHelper->getConnection();
    }

    public function getProducts($genreId = null) {
        $query = "SELECT Game.*, Genre.GenreName, Platform.PlatformName 
                  FROM Game 
                  JOIN Genre ON Game.GenreID = Genre.GenreID 
                  JOIN Platform ON Game.PlatformID = Platform.PlatformID";
        
        if ($genreId) {
            $query .= " WHERE Game.GenreID = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param('i', $genreId);
        } else {
            $stmt = $this->db->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>