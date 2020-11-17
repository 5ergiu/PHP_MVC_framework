<?php
namespace App\Component;

use App\Repository\AbstractRepository as Repository;
use Exception;
use PDOStatement;
/**
 * Builds queries.
 * @property Repository $repository
 * @property string $table           The name of the table.
 * @property string $alias           The table alias.
 * @property array $selections       The selected fields('*' by default).
 * @property array $params           Query parameters.
 * @property array $conditions       Query conditions.
 * @property array $orderBy          Query order by conditions.
 * @property array $groupBy          Query group by conditions.
 * @property int $limit              Query limit.
 * @property string $query           Query to be executed on the database.
 * @property PDOStatement $statement PDO Statement to be ran to get results.
 */
class QueryBuilderComponent
{
    private Repository $repository;
    private string $table;
    private string $alias;
    private array $selections;
    private array $params = [];
    private array $conditions = [];
    private array $orderBy;
    private array $groupBy;
    private int $limit;
    private string $query;
    private PDOStatement $statement;

    public function __construct(Repository $repository, string $table)
    {
        $this->repository = $repository;
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return QueryBuilderComponent
     */
    public function setAlias(string $alias): QueryBuilderComponent
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * @return array
     */
    public function getSelections(): array
    {
        return $this->selections;
    }

    /**
     * @param array $selections
     * @return QueryBuilderComponent
     */
    public function select(array $selections): QueryBuilderComponent
    {
        foreach ($selections as $selection) {
            $this->selections[] = $selection;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return QueryBuilderComponent
     */
    public function setParams(array $params): QueryBuilderComponent
    {
        foreach ($params as $param) {
            $this->params[] = $param;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param array $conditions
     * @return QueryBuilderComponent
     */
    public function addConditions(array $conditions): QueryBuilderComponent
    {
        foreach ($conditions as $attribute => $condition) {
            $this->conditions[$attribute] = $condition;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @param array $orderBy
     * @return QueryBuilderComponent
     */
    public function orderBy(array $orderBy): QueryBuilderComponent
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @return array
     */
    public function getGroupBy(): array
    {
        return $this->groupBy;
    }

    /**
     * @param array $groupBy
     */
    public function groupBy(array $groupBy): void
    {
        $this->groupBy = $groupBy;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return QueryBuilderComponent
     */
    public function setMaxResults(int $limit): QueryBuilderComponent
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Returns the repository instance after the query is built,
     * to get the results.
     * @return string
     */
    public function getQuery(): string
    {
        $this->setQuery();
        return $this->query;
    }

    /**
     * Builds the final query string to ensure the correct order is made.
     * @return void
     */
    public function setQuery(): void
    {
        $this->query = "SELECT ";
        $selections = null;
        if (!empty($this->selections)) {
            $selections = implode(',', $this->selections);
            $this->query .= "$selections FROM $this->table as $this->alias ";
        } else {
            $this->query .= "* FROM $this->table as $this->alias ";
        }
        if (!empty($this->params)) {
            $this->query .= "WHERE ";
            foreach ($this->params as $key => $param) {
                if ($key !== array_key_last($this->params)) {
                    $this->query .= "$param AND ";
                } else {
                    $this->query .= "$param ";
                }
            }
        }
        if (!empty($this->groupBy)) {
            $this->query .= "GROUP BY ";
            foreach ($this->groupBy as $key => $option) {
                if ($key !== array_key_last($this->groupBy)) {
                    $this->query .= "$option, ";
                } else {
                    $this->query .= "$option ";
                }
            }
        }
        if (!empty($this->orderBy)) {
            $this->query .= "ORDER BY ";
            foreach ($this->orderBy as $type => $attribute) {
                if (is_int($type)) {
                    if ($type !== array_key_last($this->orderBy)) {
                        $this->query .= "$attribute, ";
                    } else {
                        $this->query .= "$attribute ";
                    }
                } else {
                    if ($type !== array_key_last($this->orderBy)) {
                        $this->query .= "$attribute $type, ";
                    } else {
                        $this->query .= "$attribute $type ";
                    }
                }
            }
        }
        if (!empty($this->limit)) {
            $this->query .= "LIMIT $this->limit";
        }
        $this->query .= ';';
    }

    /**
     * Prepares the query and binds the values.
     * @return QueryBuilderComponent
     */
    public function prepareStatement(): QueryBuilderComponent
    {
        $this->setQuery();
        $query = $this->repository->prepareStatement($this->query);
        $attributes = $this->getParams();
        $values = $this->getConditions();
        if (!empty($attributes) && !empty($values)) {
            foreach ($attributes as $key => $attribute) {
                $attribute = explode('=', str_replace(' ', '', $attribute));
                $query->bindValue("$attribute[1]", $values[$attribute[0]]);
            }
        }
        if ($query !== false && $query instanceof PDOStatement) {
            $this->statement = $query;
        }
        return $this;
    }

    /**
     * Returns one or more rows from the table.
     * @return array|null
     * @throws Exception
     */
    public function getResults(): ?array
    {
        if (isset($this->statement)) {
            return $this->repository->fetchAll($this->statement);
        } else {
            throw new Exception('something went wrong when trying to bind values');
        }
    }

    /**
     * Returns a row from the table.
     * @return array|null
     * @throws Exception
     */
    public function firstOrNull(): ?array
    {
        if (isset($this->statement)) {
            return $this->repository->fetch($this->statement);
        } else {
            throw new Exception('something went wrong when trying to bind values');
        }
    }
}
