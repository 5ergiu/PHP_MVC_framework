<?php
namespace App\Core;

use Exception;
use PDO;
use PDOException;
/**
 * Connects to the database.
 */
class Database
{
    /**
     * Connects to the database and returns the PDO object.
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