<?php
namespace App\Repository;

use App\Core\Model\QueryBuilder;
use App\Entity\AbstractEntity as Entity;
use Exception;
/**
 * The framework's main repository which will be extended by all the app's repositories.
 * Used for entire table queries.
 * @property int|null $userId  The authenticated user's id or null (this will make it a lot easier to use in queries).
 * @property string $table     The name of the table.
 * @property array $attributes The attributes/columns of a table.
 * @property QueryBuilder $QueryBuilder
 */
abstract class AbstractRepository
{
    public string $table;
    protected QueryBuilder $QueryBuilder;

    public function __construct()
    {
        $this->table = $this->getTable();
        $this->QueryBuilder = new QueryBuilder($this->table);
    }

    /**
     * Get the table's name.
     * @return string
     */
    public function getTable(): string
    {
        $className = get_class($this);
        return strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0', chop(
                    substr($className, strrpos($className, '\\') + 1),
                    'Repo'
                )
            )
        );
    }

    /**
     * Checks if a record exists in the database based on the provided conditions.
     * @param array $conditions
     * @return int|null
     * @throws Exception
     */
    public function exists(array $conditions): ?int
    {
        $result = $this->findBy($conditions);
        return !empty($result) ? $result['id'] : null;
    }

    /**
     * Sets sub queries on a specific repo instance.
     * @param string $name     The name of the sub query.
     * @param string $subQuery The sub query.
     * @return void
     */
    public function setSubQuery(string $name, string $subQuery): void
    {
        $subQuery = '(' . substr($subQuery, 0, -1) . ')';
        $this->{$name} = $subQuery;
    }

    /**
     * Saves a new record to the database.
     * @param Entity $entity The entity to be saved.
     * @param array $data    The data that will be bound to the entity.
     * @return int|null
     * @throws Exception
     */
    public function save(Entity $entity, array $data): ?int
    {
        if (!$entity->bindValues($data)) {
            return null;
        }
        return $this->QueryBuilder->getLastInsertedId($entity);
    }

    /**
     * Deletes a record from the database.
     * @param int $id The id based on which the record will be deleted.
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        return $this->QueryBuilder->remove($id);
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
     * Returns a row from the table.
     * @param array $conditions
     * @return array|null
     * @throws Exception
     */
    public function findBy(array $conditions): ?array
    {
        $parameters = $conditions;
        $conditions = array_map(fn($attr) => "{$this->table}.$attr = :$attr", array_keys($parameters));
        return $this->createQueryBuilder($this->table)
            ->where($conditions)
            ->setParameters($parameters)
            ->getQuery()
            ->firstOrNull()
        ;
    }

    /**
     * Returns a row from the table.
     * @param int $id
     * @return array|null
     * @throws Exception
     */
    public function findById(int $id): ?array
    {
        return $this->createQueryBuilder($this->table)
            ->where([
                'AND' => "{$this->table}.id = :id",
            ])
            ->setParameters([
                'id' => $id,
            ])
            ->getQuery()
            ->firstOrNull()
        ;
    }

    /**
     * Returns everything from the table.
     * @param int|null $limit (optional) Query limit.
     * @return array|null
     * @throws Exception
     */
    public function findAll(?int $limit = null): ?array
    {
        return $this->createQueryBuilder($this->table)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResults()
        ;
    }
}
