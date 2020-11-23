<?php
namespace App\Core\Model;

use App\Entity\AbstractEntity as Entity;
use Exception;
/**
 * Builds queries.
 * @property string $table           The name of the table.
 * @property array $attributes       The table's attributes.
 * @property string $alias           The table alias.
 * @property array $selections       The selected fields('*' by default).
 * @property array $joins            Query joins.
 * @property array $parameters       Query parameters.
 * @property array $conditions       Query conditions.
 * @property array $orderBy          Query order by conditions.
 * @property array $groupBy          Query group by conditions.
 * @property int $limit              Query limit.
 * @property string $query           Query to be executed on the database.
 */
class QueryBuilder extends Query
{
    private string $table;
    private array $attributes;
    private string $alias;
    private array $selections = [];
    private array $joins = [];
    private array $parameters = [];
    private array $conditions = [];
    private array $orderBy = [];
    private array $groupBy = [];
    private int $limit;
    private string $query;

    public function __construct(string $table)
    {
        $this->table = $table;
        parent::__construct();
        $this->__setAttributes();
    }

    /**
     * Sets the table's attributes.
     * @return void
     * @throws Exception
     */
    private function __setAttributes(): void
    {
        $this->attributes = $this->setAttributesFromDatabaseSchema($this->table);
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
        $this->selections = $selections;
        return $this;
    }

    /**
     * @param array $joins
     * @return $this
     */
    public function joins(array $joins): QueryBuilder
    {
        $this->joins = $joins;
        return $this;
    }

    /**
     * @param array $conditions
     * @return $this
     */
    public function where(array $conditions): QueryBuilder
    {
        $this->conditions = $conditions;
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
     * @param array $orderBy
     * @return $this
     */
    public function orderBy(array $orderBy): QueryBuilder
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @param array $groupBy
     * @return $this
     */
    public function groupBy(array $groupBy): QueryBuilder
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setMaxResults(int $limit): QueryBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Builds the final query string to ensure the correct order is followed.
     * @return $this
     */
    public function getQuery(): QueryBuilder
    {
        $this->query = "SELECT ";
        if (!empty($this->selections)) {
            $selections = implode(', ', $this->selections);
            $this->query .= "$selections FROM $this->table as $this->alias ";
        } else {
            $this->query .= "* FROM $this->table as $this->alias ";
        }
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $this->query .= "{$join['type']} JOIN {$join['table']} as {$join['alias']} ON ";
                foreach ($join['conditions'] as $key => $condition) {
                    if ($key !== array_key_last($join['conditions'])) {
                        $this->query .= "$condition AND";
                    } else {
                        $this->query .= "$condition ";
                    }
                }
            }
        }
        if (!empty($this->conditions)) {
            $this->query .= "WHERE ";
            foreach ($this->conditions as $key => $condition) {
                if ($key !== array_key_last($this->conditions)) {
                    $this->query .= "$condition AND ";
                } else {
                    $this->query .= "$condition ";
                }
            }
        }
        if (!empty($this->groupBy)) {
            $this->query .= "GROUP BY ";
            foreach ($this->groupBy as $key => $groupBy) {
                if ($key !== array_key_last($this->groupBy)) {
                    $this->query .= "$groupBy, ";
                } else {
                    $this->query .= "$groupBy ";
                }
            }
        }
        if (!empty($this->orderBy)) {
            $this->query .= "ORDER BY ";
            foreach ($this->orderBy as $sort => $order) {
                if (is_int($sort)) {
                    if ($sort !== array_key_last($this->orderBy)) {
                        $this->query .= "$sort, ";
                    } else {
                        $this->query .= "$sort ";
                    }
                } else {
                    if ($sort !== array_key_last($this->orderBy)) {
                        $this->query .= "$sort $order, ";
                    } else {
                        $this->query .= "$sort $order ";
                    }
                }
            }
        }
        if (!empty($this->limit)) {
            $this->query .= "LIMIT $this->limit";
        }
        $this->query = rtrim($this->query);
        $this->query .= ';';
        return $this;
    }

    /**
     * Returns the formatted query string.
     * @return string
     */
    public function toQueryString(): string
    {
        $query = substr($this->query, 0, -1);
        return "($query)";
    }

    /**
     * Saves a new record to the database.
     * @param Entity $entity
     * @return int
     * @throws Exception
     */
    public function saveRecord(Entity $entity): int
    {
        $parameters = $this->__getValuesFromEntity($entity);
        $attributes = implode(', ', array_keys($parameters));
        $prepareAttributes = implode(', ', array_map(fn($attr) => ":$attr", array_keys($parameters)));
        $query = "INSERT INTO $this->table($attributes) VALUES ($prepareAttributes)";
        $this->prepareStatement($query, $parameters);
        return $this->getLastInsertedId();
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
     * Returns one or more rows from the table.
     * @return array|null
     * @throws Exception
     */
    public function getResults(): ?array
    {
        $this->prepareStatement($this->query, $this->parameters);
        return $this->fetchAll();
    }

    /**
     * Returns a row from the table.
     * @return array|null
     * @throws Exception
     */
    public function firstOrNull(): ?array
    {
        $this->prepareStatement($this->query, $this->parameters);
        return $this->fetch();
    }

    /**
     * Returns the count of a specific query.
     * @return mixed
     * @throws Exception
     */
    public function count()
    {
        $this->prepareStatement($this->query, $this->parameters);
        return $this->fetchColumn();
    }
}
