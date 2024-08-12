<?php
require_once 'DBHelper.php';

class LoginHandler {
    private $db;

    public function __construct() {
        $this->db = new DBHelper();
    }

    public function validate($fieldvalue) {
        $errors = [];

        if (empty($fieldvalue['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($fieldvalue['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($fieldvalue['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($fieldvalue['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long.';
        }

        return $errors;
    }

    public function login($email, $password) {
        $connection = $this->db->getConnection();
        $stmt = $connection->prepare("SELECT PasswordHash FROM Customer WHERE Email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['PasswordHash'])) {
                return true;
            }
        }

        return false;
    }
}
