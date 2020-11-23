<?php
namespace App\Core\Model;

use Exception;
use PDOException;
use PDOStatement;
class Query
{
    /**
     * Returns one or more rows from the table.
     * @param PDOStatement $statement PDO Statement to be executed.
     * @return array|null
     * @throws Exception
     */
    protected function fetchAll(PDOStatement $statement): ?array
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
     * @param PDOStatement $statement PDO Statement to be executed.
     * @return array|null
     * @throws Exception
     */
    protected function fetch(PDOStatement $statement): ?array
    {
        try {
            $statement->execute();
            $result = $statement->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Returns the count of a specific query.
     * @param PDOStatement $statement PDO Statement to be executed.
     * @return mixed
     * @throws Exception
     */
    protected function fetchColumn(PDOStatement $statement)
    {
        try {
            $statement->execute();
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Saves a new record to the database.
     * @param PDOStatement $statement PDO Statement to be executed.
     * @return bool
     * @throws Exception
     */
    protected function insert(PDOStatement $statement): bool
    {
        try {
            return $statement->execute();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }
}
