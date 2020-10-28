<?php
namespace App\Core\Model;

use App\Core\Helper\LoggerHelper;
use PDO;
use PDOException;
/**
 * The framework's main repository which will be extended by all the app's repositories.
 * Used for entire table queries.
 * @property PDO $pdo
 */
abstract class AbstractRepository
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    /**
     * Finds a result by a specific column.
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
//            Logger::logError($e->getMessage(), "{$model}_{$column}_findBy_{$value}");
            return false;
        }
    }

    /**
     * Gets everything from a specific table.
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
//            Logger::logError($e->getMessage(), "{$table}_findAll");
            return false;
        }
    }
}