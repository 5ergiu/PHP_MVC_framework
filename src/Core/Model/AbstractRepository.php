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
 * @property QueryBuilder $QueryBuilder
 */
abstract class AbstractRepository
{
    protected PDO $pdo;
    private string $table;
    private array $attributes;
    protected QueryBuilder $QueryBuilder;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->pdo = Database::connect();
        $this->table = $this->getTable();
        $this->__setAttributesFromDatabaseSchema();
        $this->QueryBuilder = new QueryBuilder($this, $this->table);
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
        $query = $this->__prepareSaveStatement($this->table, $values);
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
    private function __prepareSaveStatement(string $table, array $values)
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
     * Returns a row from the table.
     * @param string $criteria
     * @param int|string $value
     * @return array|false
     * @throws Exception
     */
    protected function findBy(string $criteria, $value)
    {
        $sql = "SELECT * FROM `$this->table` WHERE $criteria=:$criteria";
        $query = $this->pdo->prepare($sql);
        $query->bindValue(":$criteria", $value);
        try {
            $query->execute();
            return $query->fetch();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Returns everything from the table.
     * @param int|null $limit (optional) A limit, if it's necessary.
     * @return array|false
     * @throws Exception
     */
    protected function findAll(int $limit = null)
    {
        $sql = "SELECT * FROM `$this->table`";
        if ($limit !== null) {
            $sql .= " LIMIT $limit";
        }
        try {
            $query = $this->pdo->query($sql);
            return $query->fetchAll();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Adds the alias for the table in the query builder and returns it back
     * to be able to add more properties.
     * @param string $alias
     * @return QueryBuilder
     */
    protected function createQueryBuilder(string $alias): QueryBuilder
    {
        return $this->QueryBuilder->setAlias($alias);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getResults(): array
    {
        $query = $this->pdo->prepare($this->QueryBuilder->query);
        $attributes = $this->QueryBuilder->getParams();
        $values = $this->QueryBuilder->getConditions();
        if (!empty($attributes) && !empty($values)) {
            foreach ($attributes as $key => $attribute) {
                $attribute = explode('=', str_replace(' ', '', $attribute));
                $query->bindValue("$attribute[1]", $values[$attribute[0]]);
            }
        }
        if ($query !== false) {
            try {
                $query->execute();
                return $query->fetchAll();
            } catch (PDOException $e) {
                throw new Exception($e);
            }
        } else {
            throw new Exception('Something went wrong!');
        }
    }
}