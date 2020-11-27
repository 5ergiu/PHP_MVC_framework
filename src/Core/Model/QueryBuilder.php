<?php
namespace App\Core\Model;

use App\Core\Exception\HaveToWorkOnTheseExceptions;
use App\Entity\AbstractEntity as Entity;
use Exception;
use PDO;
use PDOException;
use PDOStatement;
/**
 * Builds queries.
 * @property string $table                The name of the table.
 * @property PDO $pdo                     PDO instance.
 * @property array $attributes            The table's attributes.
 * @property string $alias                The table alias.
 * @property string|null $selections      The selected fields('*' by default).
 * @property string|null $joins           Query joins.
 * @property array|null $parameters       Query parameters.
 * @property string|null $conditions      Query conditions.
 * @property string|null $groupBy         Query group by conditions.
 * @property string|null $orderBy         Query order by conditions.
 * @property string|null $limit           Query limit.
 * @property string $query                Query to be executed on the database.
 * @property PDOStatement|null $statement PDO Statement to be executed.
 */
class QueryBuilder extends Query
{
    private string $table;
    private PDO $pdo;
    private array $attributes;
    private string $alias;
    private ?string $selections = null;
    private ?string $joins = null;
    private ?array $parameters = null;
    private ?string $conditions = null;
    private ?string $groupBy = null;
    private ?string $orderBy = null;
    private ?string $limit = null;
    private string $query;
    public PDOStatement $statement;

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->pdo = $this->__connect();
        $this->__setAttributesFromDatabaseSchema();
    }

    /**
     * Connects to the database and returns the PDO object.
     * @return PDO
     * @throws Exception
     */
    private static function __connect(): PDO
    {
        $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}";
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

    /**
     * Queries the database for the table's attributes and sets them.
     * @return void
     * @throws Exception
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
            throw new HaveToWorkOnTheseExceptions($e);
        }
    }

    /**
     * @param string $alias
     * @return $this
     */
    public function setAlias(string $alias): QueryBuilder
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @param array $selections
     * @return $this
     */
    public function select(array $selections): QueryBuilder
    {
        $tempSelections = [];
        foreach ($selections as $selection => $alias) {
            if (is_int($selection)) {
                $tempSelections[] = "$alias";
            } else {
                $tempSelections[] = "$selection as $alias";
            }
        }
        $this->selections = implode(', ', $tempSelections);
        $this->selections = "{$this->selections} FROM {$this->table} as {$this->alias} ";
        return $this;
    }

    /**
     * @param array $joins
     * @return $this
     */
    public function joins(array $joins): QueryBuilder
    {
        foreach ($joins as $join) {
            $this->joins .= "{$join['type']} JOIN {$join['table']} as {$join['alias']} ON ";
            foreach ($join['conditions'] as $type => $condition) {
                if (!is_array($condition)) {
                    if ($type !== array_key_last($join['conditions'])) {
                        $this->joins .= "$condition AND";
                    } else {
                        $this->joins .= "$condition ";
                    }
                } else {
                    foreach ($condition as $key => $value) {
                        if ($key !== array_key_last($condition)) {
                            $this->joins .= "$value $type";
                        } else {
                            $this->conditions .= "$value ";
                        }
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param array $conditions
     * @return $this
     */
    public function where(array $conditions): QueryBuilder
    {
        $this->conditions .= 'WHERE ';
        foreach ($conditions as $type => $condition) {
            if (!is_array($condition)) {
                if ($type !== array_key_last($conditions)) {
                    $this->conditions .= "$condition $type ";
                } else {
                    $this->conditions .= "$condition ";
                }
            } else {
                foreach ($condition as $key => $value) {
                    if ($key !== array_key_last($condition)) {
                        $this->conditions .= "$value $type ";
                    } else {
                        $this->conditions .= "$value ";
                    }
                }
            }
        }
        return $this;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters): QueryBuilder
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param array $group
     * @return $this
     */
    public function groupBy(array $group): QueryBuilder
    {
        $this->groupBy .= 'GROUP BY ';
        foreach ($group as $key => $groupBy) {
            if ($key !== array_key_last($group)) {
                $this->groupBy .= "$groupBy, ";
            } else {
                $this->groupBy .= "$groupBy ";
            }
        }
        return $this;
    }

    /**
     * @param array $orderBy
     * @return $this
     */
    public function orderBy(array $orderBy): QueryBuilder
    {
        $this->orderBy .= 'ORDER BY ';
        foreach ($orderBy as $sort => $order) {
            if (is_int($sort)) {
                if ($sort !== array_key_last($orderBy)) {
                    $this->orderBy .= "$order, ";
                } else {
                    $this->orderBy .= "$order ";
                }
            } else {
                if ($sort !== array_key_last($orderBy)) {
                    $this->orderBy .= "$sort $order, ";
                } else {
                    $this->orderBy .= "$sort $order ";
                }
            }
        }
        return $this;
    }

    /**
     * @param int|null $limit
     * @return $this
     */
    public function setMaxResults(int $limit): QueryBuilder
    {
        $this->limit .= "LIMIT $limit";
        return $this;
    }

    /**
     * Builds the final query string to ensure the correct order is followed.
     * @return $this
     */
    public function getQuery(): QueryBuilder
    {
        $this->query = 'SELECT ';
        if (!empty($this->selections)) {
            $this->query .= $this->selections;
        } else {
            $this->query .= "* FROM $this->table as $this->alias ";
        }
        if (!empty($this->joins)) {
            $this->query .= $this->joins;
        }
        if (!empty($this->conditions)) {
            $this->query .= $this->conditions;
        }
        if (!empty($this->groupBy)) {
            $this->query .= $this->groupBy;
        }
        if (!empty($this->orderBy)) {
            $this->query .= $this->orderBy;
        }
        if (!empty($this->limit)) {
            $this->query .= $this->limit;
        }
        $this->query = rtrim($this->query);
        $this->query .= ';';
        $this->__reset();
        return $this;
    }

    /**
     * Reset everything in case we create another query from another method right after using the previous query.
     * @return void
     */
    private function __reset(): void
    {
        $this->selections = null;
        $this->joins = null;
        $this->conditions = null;
        $this->groupBy = null;
        $this->orderBy = null;
        $this->limit = null;
    }

    /**
     * Returns the formatted query string.
     * @return string
     */
    public function toQueryString(): string
    {
        return $this->query;
    }

    /**
     * Prepares the query and binds the values.
     * @return void
     * @throws HaveToWorkOnTheseExceptions
     */
    private function __prepareStatement(): void
    {
        $statement = $this->pdo->prepare($this->query);
        if (!empty($this->parameters)) {
            foreach ($this->parameters as $parameter => $value) {
                $statement->bindValue(":$parameter", $value);
            }
        }
        if ($statement !== false && $statement instanceof PDOStatement) {
            $this->statement = $statement;
        } else {
            throw new HaveToWorkOnTheseExceptions;
        }
        $this->__reset();
    }

    /**
     * Returns an array with the values stored in an entity.
     * @param Entity $entity
     * @return array
     */
    private function __getValuesFromEntity(Entity $entity): array
    {
        $values = [];
        foreach ($this->attributes as $attribute) {
            $attribute = str_replace('_', '', lcfirst(ucwords($attribute, '_')));
            // Default values set in entities are easy to access because of this.
            $funcName = 'get' . ucwords($attribute);
            if (property_exists($entity, $attribute)) {
                if (method_exists($entity, $funcName)) {
                    $values[$attribute] = $entity->{$funcName}();
                }
            }
        }
        return $values;
    }

    /**
     * Saves a new record to the database.
     * @param Entity $entity
     * @return int|null
     * @throws Exception
     */
    public function getLastInsertedId(Entity $entity): ?int
    {
        $parameters = $this->__getValuesFromEntity($entity);
        foreach ($parameters as $attribute => $value) {
            $formattedAttribute = strtolower(preg_replace('/(?<!^)[A-Z]/','_$0', $attribute));
            $this->parameters[$formattedAttribute] = $value;
        }
        $attributes = implode(', ', array_keys($this->parameters));
        $prepareAttributes = implode(', ', array_map(fn($attr) => ":$attr", array_keys($this->parameters)));
        $this->query = "INSERT INTO $this->table($attributes) VALUES ($prepareAttributes)";
        $this->__prepareStatement();
        if ($this->insert($this->statement)) {
            return $this->pdo->lastInsertId();
        } else {
            return null;
        }
    }

    /**
     * Returns one or more rows from the table.
     * @return array|null
     * @throws Exception
     */
    public function getResults(): ?array
    {
        $this->__prepareStatement();
        return $this->fetchAll($this->statement);
    }

    /**
     * Returns a row from the table.
     * @return array|null
     * @throws Exception
     */
    public function firstOrNull(): ?array
    {
        $this->__prepareStatement();
        return $this->fetch($this->statement);
    }

    /**
     * Returns the count of a specific query.
     * @return mixed
     * @throws Exception
     */
    public function count()
    {
        $this->__prepareStatement();
        return $this->fetchColumn($this->statement);
    }

    /**
     * Deletes a record from the database.
     * @param int $id The id based on which the record will be deleted.
     * @return bool
     * @throws Exception
     */
    public function remove(int $id): bool
    {
        $this->setParameters(['id' => $id]);
        $prepareAttributes = "{$this->table}.id = :id";
        $this->query = "DELETE FROM {$this->table} WHERE $prepareAttributes LIMIT 1";
        $this->__prepareStatement();
        return $this->delete($this->statement);
    }
}
