<?php
require_once 'RegistrationHandler.php';

$registrationHandler = new RegistrationHandler();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'zipcode' => $_POST['zipcode'],
        'country' => $_POST['country'],
        'password' => $_POST['password']
    ];

    $result = $registrationHandler->register($data);

    if ($result['success']) {
        header("Location: login.php");
        exit();
    } else {
        $errors = $result['errors'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NEXPLAY</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" class="mt-5 p-4 border rounded bg-dark text-white">
                    <h1 class="text-center mb-4">Register</h1>
                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter your first name" value="<?php echo htmlspecialchars($_POST['firstname'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['firstname'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter your last name" value="<?php echo htmlspecialchars($_POST['lastname'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['lastname'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Enter your email address" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['email'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number:</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="1234567890" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['phone'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" name="address" id="address" class="form-control" placeholder="Enter your address" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['address'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" name="city" id="city" class="form-control" placeholder="Enter your city" value="<?php echo htmlspecialchars($_POST['city'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['city'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="state">State:</label>
                        <input type="text" name="state" id="state" class="form-control" placeholder="Enter your state" value="<?php echo htmlspecialchars($_POST['state'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['state'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="zipcode">Zipcode:</label>
                        <input type="text" name="zipcode" id="zipcode" class="form-control" placeholder="A1B2C3" value="<?php echo htmlspecialchars($_POST['zipcode'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['zipcode'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="country">Country:</label>
                        <input type="text" name="country" id="country" class="form-control" placeholder="Enter your country" value="<?php echo htmlspecialchars($_POST['country'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['country'] ?? ''; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                        <div style="color:red"><?php echo $errors['password'] ?? ''; ?></div>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" value="Register" class="btn btn-primary">
                    </div>
                    <div class="text-center mt-5">
                        <a href="login.php" class="text-light">Already Registered? Login Here!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
