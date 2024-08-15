<?php

require_once '../Dhanasree-8908622/DBHelper.php';

class RegistrationHandler {
    private $conn;

    public function __construct() {
        $db = new DBHelper();
        $this->conn = $db->getConnection();
    }

    public function validate($fieldvalue) {
        $errors = [];

        if (empty($fieldvalue['firstname'])) {
            $errors['firstname'] = 'First name is required.';
        } elseif (!preg_match('/^[a-zA-Z]+$/', $fieldvalue['firstname'])) {
            $errors['firstname'] = 'First name can only contain alphabets.';
        }

        if (empty($fieldvalue['lastname'])) {
            $errors['lastname'] = 'Last name is required.';
        } elseif (!preg_match('/^[a-zA-Z]+$/', $fieldvalue['lastname'])) {
            $errors['lastname'] = 'Last name can only contain alphabets.';
        }

        if (empty($fieldvalue['email'])) {
            $errors['email'] = 'Email is required.';
        } elseif (!filter_var($fieldvalue['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }

        if (empty($fieldvalue['phone'])) {
            $errors['phone'] = 'Phone number is required.';
        } elseif (!preg_match('/^[0-9]{10}$/', $fieldvalue['phone'])) {
            $errors['phone'] = 'Invalid phone number format.';
        }

        if (empty($fieldvalue['address'])) {
            $errors['address'] = 'Address is required.';
        }

        if (empty($fieldvalue['city'])) {
            $errors['city'] = 'City is required.';
        } elseif (!preg_match('/^[a-zA-Z]+$/', $fieldvalue['city'])) {
            $errors['city'] = 'City can only contain alphabets.';
        }

        if (empty($fieldvalue['state'])) {
            $errors['state'] = 'State is required.';
        } elseif (!preg_match('/^[a-zA-Z]+$/', $fieldvalue['state'])) {
            $errors['state'] = 'State can only contain alphabets.';
        }

        if (empty($fieldvalue['zipcode'])) {
            $errors['zipcode'] = 'Zipcode is required.';
        } elseif (!preg_match('/^[A-Z0-9]{6}$/', $fieldvalue['zipcode'])) {
            $errors['zipcode'] = 'Invalid zipcode format.';
        }

        if (empty($fieldvalue['country'])) {
            $errors['country'] = 'Country is required.';
        } elseif (!preg_match('/^[a-zA-Z]+$/', $fieldvalue['country'])) {
            $errors['country'] = 'Country can only contain alphabets.';
        }

        if (empty($fieldvalue['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($fieldvalue['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long.';
        }

        return $errors;
    }

    public function register($fieldvalue) {
        $errors = $this->validate($fieldvalue);

        if (count($errors) > 0) {
            return ['success' => false, 'errors' => $errors];
        }

        $fieldvalue['password'] = password_hash($fieldvalue['password'], PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare(
            "INSERT INTO Customer (FirstName, LastName, Email, Phone, Address, City, State, ZipCode, Country, PasswordHash) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            'ssssssssss',
            $fieldvalue['firstname'],
            $fieldvalue['lastname'],
            $fieldvalue['email'],
            $fieldvalue['phone'],
            $fieldvalue['address'],
            $fieldvalue['city'],
            $fieldvalue['state'],
            $fieldvalue['zipcode'],
            $fieldvalue['country'],
            $fieldvalue['password']
        );

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => ['database' => 'Registration failed. Please try again later.']];
        }
    }
}
?>
