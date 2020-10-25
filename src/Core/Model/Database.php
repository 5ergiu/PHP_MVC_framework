<?php

namespace App\Core\Model;

use Exception;
use PDO;
use PDOException;

class Database
{
    /**
     * Connect to the database.
     * @return PDO
     * @throws Exception
     */
    public static function connect(): PDO
    {
        $dsn = $_ENV['DSN'];
        try {
            $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);
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