<?php
namespace App\Core\Model;

use App\Core\Exception\HaveToWorkOnTheseExceptions;
use Exception;
use PDO;
use PDOException;
use PDOStatement;
/**
 * @property PDO $pdo
 * @property PDOStatement $statement
 * @property string $table
 */
class Query extends Database
{
    protected PDO $pdo;
    private PDOStatement $statement;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    /**
     * Queries the database for the table's attributes and sets them.
     * @param string $table
     * @return array
     * @throws Exception
     */
    protected function setAttributesFromDatabaseSchema(string $table): array
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
                ':table_name' => $table,
            ]);
            $result = $query->fetchAll();
            $result = array_map('array_pop', $result);
            foreach ($result as &$column) {
                $column = str_replace('_', '', lcfirst(ucwords($column, '_')));
            }
            return $result;
        } catch (PDOException $e) {
            throw new HaveToWorkOnTheseExceptions($e);
        }
    }

    /**
     * Prepares the query and binds the values.
     * @param string $query
     * @param array $parameters
     * @return void
     * @throws HaveToWorkOnTheseExceptions
     */
    public function prepareStatement(string $query, array $parameters): void
    {
        $statement = $this->pdo->prepare($query);
        if (!empty($parameters)) {
            foreach ($parameters as $parameter => $value) {
                $statement->bindValue(":$parameter", $value);
            }
        }
        if ($statement !== false && $statement instanceof PDOStatement) {
            $this->statement = $statement;
        } else {
            throw new HaveToWorkOnTheseExceptions;
        }
    }

    /**
     * Returns one or more rows from the table.
     * @return array|null
     * @throws Exception
     */
    public function fetchAll(): ?array
    {
        try {
            $this->statement->execute();
            $result = $this->statement->fetchAll();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Returns a row from the table.
     * @return array|null
     * @throws Exception
     */
    public function fetch(): ?array
    {
        try {
            $this->statement->execute();
            $result = $this->statement->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Returns the count of a specific query.
     * @return mixed
     * @throws Exception
     */
    public function fetchColumn()
    {
        try {
            $this->statement->execute();
            return $this->statement->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }

    /**
     * Saves a new record to the database.
     * @throws Exception
     */
    public function getLastInsertedId(): int
    {
        try {
            $this->statement->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception($e);
        }
    }
}
