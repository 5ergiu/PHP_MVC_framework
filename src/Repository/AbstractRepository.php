<?php
namespace App\Repository;

use App\Core\Model\QueryBuilder;
use App\Entity\AbstractEntity as Entity;
use Exception;
/**
 * The framework's main repository which will be extended by all the app's repositories.
 * Used for entire table queries.
 * @property array $attributes The attributes/columns of a table.
 * @property QueryBuilder $QueryBuilder
 */
abstract class AbstractRepository
{
    protected QueryBuilder $QueryBuilder;

    public function __construct()
    {
        $this->QueryBuilder = new QueryBuilder($this->getTable());
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
     * @param string $criteria
     * @param mixed $value
     * @return array|null
     * @throws Exception
     */
    public function findBy(string $criteria, $value): ?array
    {
        $table = $this->getTable();
        return $this->createQueryBuilder($table)
            ->where([
                "$table.$criteria = :$criteria"
            ])
            ->setParameters([
                "$criteria" => $value,
            ])
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
        $table = $this->getTable();
        return $this->createQueryBuilder($table)
            ->where([
                "$table.id = :id"
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
     * @param int|null $limit (optional) A limit, if it's necessary.
     * @return array|null
     * @throws Exception
     */
    public function findAll(?int $limit = null): ?array
    {
        return $this->createQueryBuilder($this->getTable())
            ->setMaxResults($limit)
            ->getQuery()
            ->getResults()
        ;
    }
}
