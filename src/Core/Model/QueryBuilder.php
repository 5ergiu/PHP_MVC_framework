<?php
namespace App\Core\Model;

use App\Core\Model\AbstractRepository as Repository;
/**
 * Builds queries.
 * @property Repository $repository
 * @property string $table     The name of the table.
 * @property string $alias     The table alias.
 * @property array $selections The selected fields('*' by default).
 * @property array $params     Query parameters.
 * @property array $conditions Query conditions.
 * @property array $orderBy    Query order by conditions.
 * @property array $groupBy    Query group by conditions.
 * @property int $limit        Query limit.
 * @property string $query     Query to be executed on the database.
 */
class QueryBuilder
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
    public string $query;

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
     * @return QueryBuilder
     */
    public function setAlias(string $alias): QueryBuilder
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
     * @return QueryBuilder
     */
    public function select(array $selections): QueryBuilder
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
     * @return QueryBuilder
     */
    public function setParams(array $params): QueryBuilder
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
     * @return QueryBuilder
     */
    public function addConditions(array $conditions): QueryBuilder
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
     * @return QueryBuilder
     */
    public function orderBy(array $orderBy): QueryBuilder
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
     * @return QueryBuilder
     */
    public function setMaxResults(int $limit): QueryBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Returns the repository instance after the query is built,
     * to get the results.
     * @return Repository
     */
    public function getQuery(): Repository
    {
        $this->setQuery();
        return $this->repository;
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
}
