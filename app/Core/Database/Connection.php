<?php
/**
 * Database Connection Class
 * 
 * @package OmniCMS\Core\Database
 */

namespace OmniCMS\Core\Database;

use PDO;
use PDOException;

class Connection
{
    /**
     * @var PDO PDO instance
     */
    private $pdo;

    /**
     * @var array Connection configuration
     */
    private $config;

    /**
     * Constructor
     * 
     * @param array $config Database configuration
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    /**
     * Establish database connection
     */
    private function connect()
    {
        try {
            $dsn = sprintf(
                '%s:host=%s;port=%d;dbname=%s;charset=%s',
                $this->config['driver'] ?? 'mysql',
                $this->config['host'] ?? 'localhost',
                $this->config['port'] ?? 3306,
                $this->config['database'],
                $this->config['charset'] ?? 'utf8mb4'
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->pdo = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $options
            );

        } catch (PDOException $e) {
            throw new PDOException(
                "Database connection failed: " . $e->getMessage()
            );
        }
    }

    /**
     * Get PDO instance
     * 
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Execute a query
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return \PDOStatement
     */
    public function query($sql, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Fetch single row
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return array|null
     */
    public function fetchOne($sql, array $params = [])
    {
        $result = $this->query($sql, $params);
        $row = $result->fetch();
        return $row ?: null;
    }

    /**
     * Fetch all rows
     * 
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return array
     */
    public function fetchAll($sql, array $params = [])
    {
        $result = $this->query($sql, $params);
        return $result->fetchAll();
    }

    /**
     * Insert record
     * 
     * @param string $table Table name
     * @param array $data Data to insert
     * @return int Last insert ID
     */
    public function insert($table, array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->query($sql, $data);
        
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Update records
     * 
     * @param string $table Table name
     * @param array $data Data to update
     * @param string $where WHERE clause
     * @param array $whereParams WHERE parameters
     * @return int Number of affected rows
     */
    public function update($table, array $data, $where, array $whereParams = [])
    {
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setParts);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        
        $params = array_merge($data, $whereParams);
        $stmt = $this->query($sql, $params);
        
        return $stmt->rowCount();
    }

    /**
     * Delete records
     * 
     * @param string $table Table name
     * @param string $where WHERE clause
     * @param array $params WHERE parameters
     * @return int Number of affected rows
     */
    public function delete($table, $where, array $params = [])
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        $this->pdo->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        $this->pdo->rollBack();
    }

    /**
     * Check if in transaction
     * 
     * @return bool
     */
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Get last insert ID
     * 
     * @param string|null $name Sequence name
     * @return string
     */
    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * Quote identifier
     * 
     * @param string $identifier Identifier to quote
     * @return string Quoted identifier
     */
    public function quoteIdentifier($identifier)
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }

    /**
     * Run migration
     * 
     * @param string $migrationClass Migration class name
     */
    public function migrate($migrationClass)
    {
        $migration = new $migrationClass($this);
        $migration->up();
    }

    /**
     * Rollback migration
     * 
     * @param string $migrationClass Migration class name
     */
    public function rollbackMigration($migrationClass)
    {
        $migration = new $migrationClass($this);
        $migration->down();
    }
}
