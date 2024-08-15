<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXPLAY</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<header>
        <div class="banner">
            <h1>NEXPLAY</h1>
        </div>
        </header>
    <body>
    <h1>Welcome to NEXPLAY</h1>
        <form method="POST">
            <input type="submit" value="Initialize Database" class='btn btn-primary'>
        </form>
        <?php
        require_once("DBHelper.php");
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            define("INITIALIZING_DB",1);
            
            $db = new DBHelper();
            $db::initializeDatabase();
            session_start();
            session_unset();
            session_destroy();
            

        }
        
        ?>
       
    </body>
</html>
