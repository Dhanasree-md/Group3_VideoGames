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

    static protected $connection = null;

    // static function initializeDatabase()
    // {
    //     try
    //     {
    //         $data_source_name="mysql:host=".self::DB_HOST.";charset=".self::CHARSET;
    //         $pdo=new PDO($data_source_name,self::DB_USER,self::DB_PASSWORD);
    //         $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    //         $pdo->query("drop database if exists registration");
    //         $pdo->query("create database registration");
    //         $pdo->query("use registration");

    //         $pdo->query("create table users (
    //             user_id mediumint(8) unsigned not null auto_increment,
    //             name varchar(100) not null,
    //             email varchar(255) not null,
    //             phone varchar(20) not null,
    //             province char(2) not null,
    //             Primary Key(user_id)
    //         ) Engine=InnoDB auto_increment=84 default charset=utf8mb4");

    //         echo "<h3>Database Initialized</h3>";
    //     }
    //     catch(PDOException $e)
    //     {
    //         echo "Connection failed: " . $e->getMessage();
    //     }
    // }

    function __construct()
    {
        if (self::$connection == null) {
            try {
                self::$connection = new mysqli(self::DB_HOST, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);

                if (self::$connection->connect_error) {
                    throw new Exception("Connection failed: " . self::$connection->connect_error);
                }

                self::$connection->set_charset(self::CHARSET);
            } catch (Exception $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    function getConnection()
    {
        return self::$connection;
    }

    function getRowCount()
    {
        return $this->stmt->num_rows;
    }

    function reset()
    {
        $this->sqlStatement = "";
        $this->params = null;
        $this->stmt = null;
    }

    function statement($sqlStatement)
    {
        $this->reset();
        $this->sqlStatement = $sqlStatement;
        return $this;
    }

    function params($params)
    {
        $this->params = $params;
        return $this;
    }

    function execute($sqlStatement = "")
    {
        if (!empty($sqlStatement)) {
            $this->sqlStatement = $sqlStatement;
        }

        if (is_array($this->params)) {
            $stmt = self::$connection->prepare($this->sqlStatement);

            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . self::$connection->error);
            }

            // Dynamically bind parameters
            $types = str_repeat('s', count($this->params));
            $stmt->bind_param($types, ...array_values($this->params));

            $stmt->execute();
            $this->stmt = $stmt->get_result();
        } else {
            $this->stmt = self::$connection->query($this->sqlStatement);
        }
    }
    

    // function execute($sqlStatement="")
    // {
    //     if(!empty($sqlStatement))
    //     {
    //         $this->sqlStatement=$sqlStatement;
    //     }
    //     if(is_array($this->params))
    //     {
    //         $this->stmt=self::$connection->prepare($this->sqlStatement);
    //         $this->stmt->execute($this->params);
    //     }
    //     else
    //     {
    //         $this->stmt=self::$connection->query($this->sqlStatement);
    //     }
    // }
}

