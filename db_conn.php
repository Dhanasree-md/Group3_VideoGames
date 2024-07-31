<?php
define('DB_USER','root');
define('DB_PASSWORD','');
define('DB_HOST','localhost');
define('DB_NAME','Videogames');
define('CHARSET','utf8mb4');
if(defined ("INITIALIZING_DB")){
    $dbc=new MySQLi(DB_HOST,DB_USER,DB_PASSWORD) OR 
    die("Connection Failed:".mysqli_connect_error());
}
else{
     $dbc=new MySQLi(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) OR 
    die("Connection Failed:".mysqli_connect_error());
}


