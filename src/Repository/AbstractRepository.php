<?php
namespace App\Repository;

use App\Component\DatabaseComponent;
use App\Component\QueryBuilderComponent;
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
 * @property QueryBuilderComponent $QueryBuilder
 */
abstract class AbstractRepository
{
    private PDO $pdo;
    private string $table;
    private array $attributes;
    protected QueryBuilderComponent $QueryBuilder;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->pdo = DatabaseComponent::connect();
        $this->__setTable();
        $this->__setAttributesFromDatabaseSchema();
        $this->QueryBuilder = new QueryBuilderComponent($this, $this->table);
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
     * Returns a PDO statement.
     * @param string $query
     * @return PDOStatement
     */
    public function prepareStatement(string $query): PDOStatement
    {
        return $this->pdo->prepare($query);
    }

    /**
     * Returns a row from the table.
     * @param string $criteria
     * @param mixed $value
     * @return array|null
     * @throws Exception
     */
    public function findBy(string $criteria, $value): ?array
    {
        $table = $this->getTable();
        return $this->createQueryBuilder($table)
            ->setParams([
                "$table.$criteria = :$criteria"
            ])
            ->addConditions([
                "$table.$criteria" => $value,
            ])
            ->prepareStatement()
            ->firstOrNull();
    }

    /**
     * Returns everything from the table.
     * @param int|null $limit (optional) A limit, if it's necessary.
     * @return array|null
     * @throws Exception
     */
    public function findAll(?int $limit = null): ?array
    {
        return $this->createQueryBuilder($this->getTable())
            ->prepareStatement()
            ->setMaxResults($limit)
            ->getResults();
    }

    /**
     * Adds the alias for the table in the query builder and returns it back
     * to be able to add more properties.
     * @param string $alias
     * @return QueryBuilderComponent
     */
    public function createQueryBuilder(string $alias): QueryBuilderComponent
    {
        return $this->QueryBuilder->setAlias($alias);
    }

    /**
     * Returns one or more rows from the table.
     * @param PDOStatement $statement PDO Statement.
     * @return array|null
     * @throws Exception
     */
    public function fetchAll(PDOStatement $statement): ?array
    {
        try {
            $statement->execute();
            $result = $statement->fetchAll();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Returns a row from the table.
     * @param PDOStatement $statement PDO Statement.
     * @return array|null
     * @throws Exception
     */
    public function fetch(PDOStatement $statement): ?array
    {
        try {
            $statement->execute();
            $result = $statement->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }
}
