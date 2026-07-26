<?php
/**
 * Migration System - سیستم مهاجرت پایگاه داده
 */

namespace OmniCMS\Core\Database;

use OmniCMS\Core\Database\Connection;

class Migration
{
    protected $connection;
    
    public function __construct()
    {
        $this->connection = Connection::getInstance();
    }
    
    /**
     * Create table
     */
    protected function create($table, callable $callback)
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        
        $sql = $blueprint->toSql();
        
        foreach ($sql as $statement) {
            $this->connection->execute($statement);
        }
        
        return true;
    }
    
    /**
     * Drop table
     */
    protected function drop($table)
    {
        $this->connection->execute("DROP TABLE IF EXISTS {$table}");
        return true;
    }
    
    /**
     * Drop table if exists
     */
    protected function dropIfExists($table)
    {
        $this->connection->execute("DROP TABLE IF EXISTS {$table}");
        return true;
    }
    
    /**
     * Add column to table
     */
    protected function addColumn($table, $column, $type, $options = [])
    {
        $blueprint = new Blueprint($table);
        $blueprint->addColumn($column, $type, $options);
        
        $sql = $blueprint->toAlterSql();
        $this->connection->execute($sql);
        
        return true;
    }
    
    /**
     * Drop column from table
     */
    protected function dropColumn($table, $column)
    {
        $this->connection->execute("ALTER TABLE {$table} DROP COLUMN {$column}");
        return true;
    }
    
    /**
     * Rename table
     */
    protected function rename($from, $to)
    {
        $this->connection->execute("ALTER TABLE {$from} RENAME TO {$to}");
        return true;
    }
    
    /**
     * Check if table exists
     */
    protected function hasTable($table)
    {
        $sql = "SELECT COUNT(*) as count FROM information_schema.tables 
                WHERE table_schema = DATABASE() AND table_name = :table";
        $stmt = $this->connection->query($sql, ['table' => $table]);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    /**
     * Check if column exists
     */
    protected function hasColumn($table, $column)
    {
        $sql = "SELECT COUNT(*) as count FROM information_schema.columns 
                WHERE table_schema = DATABASE() AND table_name = :table AND column_name = :column";
        $stmt = $this->connection->query($sql, [
            'table' => $table,
            'column' => $column
        ]);
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }
    
    /**
     * Run migration up
     */
    public function up()
    {
        // Override in child class
    }
    
    /**
     * Run migration down (rollback)
     */
    public function down()
    {
        // Override in child class
    }
}
