<?php
namespace App\Core\Model;

use App\Core\Model\AbstractEntity as Entity;
use Exception;
use PDO;
use PDOException;
use PDOStatement;
/**
 * The framework's main repository which will be extended by all the app's repositories.
 * Used for entire table queries.
 * @property PDO $pdo
 * @property string $table     The name of the table.
 * @property array $attributes The attributes/columns of a table.
 */
abstract class AbstractRepository
{
    protected PDO $pdo;
    private string $table;
    private array $attributes;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->pdo = Database::connect();
        $this->table = $this->getTable();
        $this->__setAttributesFromDatabaseSchema();
    }

    /**
     * Saves the entity in the database.
     * @param Entity $entity The entity to be saved.
     * @return string
     * @throws Exception
     */
    public function save(Entity $entity): string
    {
        $values = $this->__getValuesFromEntity($entity, $this->attributes);
        $query = $this->__prepareStatement($this->table, $values);
        if ($query !== false) {
            try {
                $query->execute();
                return $this->pdo->lastInsertId();
            } catch (PDOException $e) {
                throw new Exception($e);
            }
        } else {
            throw new Exception('Something went wrong!');
        }
    }

    /**
     * Queries the database for the table's attributes and sets them.
     * @throws Exception
     * @return void
     */
    private function __setAttributesFromDatabaseSchema(): void
    {
        $sql = "
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = :db_name AND 
            TABLE_NAME = :table_name AND 
            COLUMN_KEY <> 'PRI'
        ";
        try {
            $query = $this->pdo->prepare($sql);
            $query->execute([
                ':db_name' => $_ENV['DB_NAME'],
                ':table_name' => $this->table,
            ]);
            $result = $query->fetchAll();
            $this->attributes = array_map('array_pop', $result);
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Returns an array with the values stored in an entity.
     * @param Entity $entity
     * @param array $attributes
     * @return array
     */
    private function __getValuesFromEntity(Entity $entity, array $attributes): array
    {
        $values = [];
        foreach ($attributes as $attribute) {
            $funcName = 'get' . ucwords($attribute);
            $values[$attribute] = $entity->{$funcName}();
        }
        return $values;
    }

    /**
     * Prepares the SQL statement.
     * @param string $table
     * @param array $values
     * @return bool|PDOStatement
     */
    private function __prepareStatement(string $table, array $values)
    {
        $attributes = implode(', ', $this->attributes);
        $prepareAttributes = implode(', ', array_map(fn($attr) => ":$attr", $this->attributes));
        $sql = "INSERT INTO $table($attributes) VALUES ($prepareAttributes)";
        $query = $this->pdo->prepare($sql);
        foreach ($this->attributes as $key => $attribute) {
            $query->bindValue(":$attribute", $values[$attribute]);
        }
        return $query;
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