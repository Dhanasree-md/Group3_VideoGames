<?php
require_once '../Dhanasree-8908622/DBHelper.php';

class GenreHandler {
    private $db;

    public function __construct() {
        $dbHelper = new DBHelper();
        $this->db = $dbHelper->getConnection();
    }

    public function getGenres() {
        $query = "SELECT * FROM Genre";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>