<?php
namespace App\Repository;

use App\Core\Database;
use App\Core\QueryBuilder;
use App\Entity\AbstractEntity as Entity;
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
    private PDO $pdo;
    private string $table;
    private array $attributes;
    protected QueryBuilder $QueryBuilder;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->pdo = Database::connect();
        $this->__setTable();
        $this->__setAttributesFromDatabaseSchema();
        $this->QueryBuilder = new QueryBuilder($this, $this->table);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Sets the table's name.
     * @return void
     */
    private function __setTable(): void
    {
        $className = get_class($this);
        $this->table = strtolower(chop(substr($className, strrpos($className, '\\') + 1), 'Repo'));
    }

    /**
     * Get the last inserted id.
     * @return string
     */
    public function lastInsertedId(): string
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Saves the entity in the database.
     * @param Entity $entity The entity to be saved.
     * @param array $data    The data that will be bound to the entity.
     * @return bool
     * @throws Exception
     */
    public function save(Entity $entity, array $data): bool
    {
        if (!$entity->bindValues($data)) {
            return false;
        }
        $values = $this->__getValuesFromEntity($entity, $this->attributes);
        $query = $this->__prepareSaveStatement($this->table, $values);
        if ($query !== false) {
            try {
                $query->execute();
                return true;
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
            // Default values set in entities are easy to access because of this.
            $funcName = 'get' . ucwords($attribute);
            if (property_exists($entity, $attribute)) {
                $values[$attribute] = $entity->{$funcName}();
            } else {
                unset($this->attributes[array_search($attribute, $this->attributes)]);
            }
        }
        return $values;
    }

    /**
     * Prepares the insert SQL statement.
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
    public function findBy(string $criteria, $value)
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
    public function findAll(int $limit = null)
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
    public function createQueryBuilder(string $alias): QueryBuilder
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
