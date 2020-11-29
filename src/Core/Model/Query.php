<?php
namespace App\Core\Model;

use App\Component\Log;
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
            $this->__logErrors($e->getMessage(), $statement->queryString);
            return null;
        }
    }

    /**
     * Returns a row from the table.
     * @param PDOStatement $statement PDO Statement to be executed.
     * @return mixed
     * @throws Exception
     */
    protected function fetch(PDOStatement $statement): mixed
    {
        try {
            $statement->execute();
            $result = $statement->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            $this->__logErrors($e->getMessage(), $statement->queryString);
            return null;
        }
    }

    /**
     * Returns the count of a specific query.
     * @param PDOStatement $statement PDO Statement to be executed.
     * @return mixed
     * @throws Exception
     */
    protected function fetchColumn(PDOStatement $statement): mixed
    {
        try {
            $statement->execute();
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            $this->__logErrors($e->getMessage(), $statement->queryString);
            return null;
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
            $this->__logErrors($e->getMessage(), $statement->queryString);
            return false;
        }
    }

    /**
     * Deletes a record from the database.
     * @param PDOStatement $statement PDO Statement to be executed.
     * @return bool
     * @throws Exception
     */
    protected function delete(PDOStatement $statement): bool
    {
        try {
            return $statement->execute();
        } catch (PDOException $e) {
            $this->__logErrors($e->getMessage(), $statement->queryString);
            return false;
        }
    }

    /**
     * @param string $error The error message.
     * @param string $query The query ran on the database.
     * @return void
     */
    private function __logErrors(string $error, string $query): void
    {
        Log::error("$error \n $query", LOG::MYSQL_ERRORS);
    }
}
