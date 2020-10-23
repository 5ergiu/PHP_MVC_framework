<?php

namespace App\Core\Model;

use Exception;
use PDO;
use PDOException;

class Database
{
    /**
     * Method used to connect to the database.
     * @return PDO
     * @throws Exception
     */
    public static function connect(): PDO
    {
        $dsn = "mysql:host=localhost;dbname=blog";
        try {
            $pdo = new PDO($dsn, 'root', 'K!illerH!ills007');
            // set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // set default fetch mode to fetch associative
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}