<?php
require_once 'LoginHandler.php';

$loginHandler = new LoginHandler();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];

    $errors = $loginHandler->validate($data);

    if (empty($errors)) {
        if ($loginHandler->login($data['email'], $data['password'])) {
            header("Location: index.php");
            exit();
        } else {
            $errors['login'] = "<h4>Login Failed</h4>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NEXPLAY</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST" class="mt-5 p-4 border rounded bg-dark text-white">
                    <h1 class="text-center mb-4">Login</h1>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['email'] ?? ""; ?></div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>">
                        <div style="color:red"><?php echo $errors['password'] ?? ""; ?></div>
                    </div>
                    <div class="form-group text-center">
                        <input type="submit" value="Login" class="btn btn-primary">
                    </div>
                    <div style="color:red" class="text-center"><?php echo $errors['login'] ?? ""; ?></div>
                    <div class="text-center mt-5">
                        <a href="register.php" class="text-light">New User? Register Here!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
