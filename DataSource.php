<?php
namespace csvImport;

use mysqli;
use mysqli_stmt;

/**
 * Generic datasource class for handling DB operations.
 * Uses MySqli and PreparedStatements.
 *
 */
class DataSource
{
    public const HOST = 'localhost';
    public const USERNAME = 'root';
    public const PASSWORD = 'root';
    public const DATABASENAME = 'csvImport';

    private $connection;

    public function __construct()
    {
        $this->connection = $this->getConnection();
    }

    /**
     * If connection object is needed use this method and get access to it.
     * Otherwise, use the below methods for insert / update / etc.
     *
     * @return mysqli
     */
    final public function getConnection(): mysqli
    {
        $connection = new mysqli(self::HOST, self::USERNAME, self::PASSWORD, self::DATABASENAME);

        if (mysqli_connect_errno()) {
            trigger_error("Problem with connecting to database.");
        }

        $connection->set_charset("utf8");
        return $connection;
    }

    /**
     * To get database results
     *
     * @param string $query
     * @param string $paramType
     * @param array $paramArray
     * @return array
     */
    final public function select(string $query, string $paramType = "", array $paramArray = array()): array
    {
        $statement = $this->connection->prepare($query);

        if (! empty($paramType) && ! empty($paramArray)) {

            $this->bindQueryParams($statement, $paramType, $paramArray);
        }
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        if (! empty($resultset)) {
            return $resultset;
        }

        return [];
    }

    /**
     * To insert
     *
     * @param string $query
     * @param string $paramType
     * @param array $paramArray
     * @return int
     */
    final public function insert(string $query, string $paramType, array $paramArray): int
    {
        $statement = $this->connection->prepare($query);
        $this->bindQueryParams($statement, $paramType, $paramArray);

        $statement->execute();
        return $statement->insert_id;
    }

    /**
     * To execute query
     *
     * @param string $query
     * @param string $paramType
     * @param array $paramArray
     */
    final public function execute(string $query, string $paramType = "", array $paramArray = array()): void
    {
        $statement = $this->connection->prepare($query);

        if (! empty($paramType) && ! empty($paramArray)) {
            $this->bindQueryParams($statement, $paramType, $paramArray);
        }
        $statement->execute();
    }

    /**
     * 1. Prepares parameter binding
     * 2. Bind parameters to the sql statement
     *
     * @param  mysqli_stmt $statement
     * @param string $paramType
     * @param array $paramArray
     */
    final public function bindQueryParams( mysqli_stmt $statement, string $paramType, array $paramArray = array()): void
    {
        $paramValueReference[] = & $paramType;
        foreach ($paramArray as $i => $iValue) {
            $paramValueReference[] = &$iValue;
        }
        call_user_func_array(array(
            $statement,
            'bind_param'
        ), $paramValueReference);
    }

    /**
     * To get database results
     *
     * @param string $query
     * @param string $paramType
     * @param array $paramArray
     * @return int
     */
    final public function getRecordCount(string $query, string $paramType = "", array $paramArray = array()): int
    {
        $statement = $this->connection->prepare($query);
        if (! empty($paramType) && ! empty($paramArray)) {

            $this->bindQueryParams($statement, $paramType, $paramArray);
        }
        $statement->execute();
        $statement->store_result();
        return $statement->num_rows;
    }
}