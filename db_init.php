<!DOCTYPE html>
<html>
    <body>
        <?php
        require_once("DBHelper.php");
        if($_SERVER["REQUEST_METHOD"]=='POST'){
            define("INITIALIZING_DB",1);
            
            $db = new DBHelper();
            $db::initializeDatabase();

        }
        
        ?>
        <form method="POST">
            <input type="submit" value="Initialize Database">
        </form>
    </body>
</html>
