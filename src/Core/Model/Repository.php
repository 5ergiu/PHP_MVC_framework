<?php

namespace App\Core\Model;

use App\Core\Helper\Logger;
use PDO;
use PDOException;

class Repository
{
    protected PDO $pdo;
    protected Validator $validator;

    public function __construct()
    {
        $this->pdo = Database::connect();
        $this->validator = new Validator;
    }

    /**
     * Method to find a result by a specific column.
     * @param string $model
     * @param string $column
     * @param int|string $value
     * @return array|false
     */
    public function findBy(string $model, string $column, $value)
    {
        $sql = "SELECT * FROM {$model} WHERE {$column}=:column";
        try {
            $query = $this->pdo->prepare($sql);
            $query->execute([':column' => $value]);
            return $query->fetch();
        } catch (PDOException $e) {
            Logger::logError($e->getMessage(), "{$model}_{$column}_findBy_{$value}");
            return false;
        }
    }

    /**
     * Method used to get everything from a specific table.
     * @param string   $table The name of the table.
     * @param int|null $limit (optional) A limit, if it's necessary.
     * @return array|false
     */
    public function findAll(string $table, ?int $limit = null)
    {
        $sql = "SELECT * FROM {$table}";
        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
        }
        try {
            $query = $this->pdo->query($sql);
            return $query->fetchAll();
        } catch (PDOException $e) {
            Logger::logError($e->getMessage(), "{$table}_findAll");
            return false;
        }
    }
}