<?php
/**
 * Blueprint - کلاس تعریف ساختار جدول‌ها
 */

namespace OmniCMS\Core\Database;

class Blueprint
{
    protected $table;
    protected $columns = [];
    protected $indexes = [];
    protected $primaryKeys = [];
    
    public function __construct($table)
    {
        $this->table = $table;
    }
    
    /**
     * Add ID column (auto increment primary key)
     */
    public function id($name = 'id')
    {
        $this->columns[$name] = [
            'type' => 'INT',
            'unsigned' => true,
            'auto_increment' => true,
            'primary' => true
        ];
        return $this;
    }
    
    /**
     * Add string column
     */
    public function string($name, $length = 255)
    {
        $this->columns[$name] = [
            'type' => 'VARCHAR',
            'length' => $length
        ];
        return $this;
    }
    
    /**
     * Add text column
     */
    public function text($name)
    {
        $this->columns[$name] = [
            'type' => 'TEXT'
        ];
        return $this;
    }
    
    /**
     * Add integer column
     */
    public function integer($name, $length = 11)
    {
        $this->columns[$name] = [
            'type' => 'INT',
            'length' => $length
        ];
        return $this;
    }
    
    /**
     * Add big integer column
     */
    public function bigInteger($name)
    {
        $this->columns[$name] = [
            'type' => 'BIGINT'
        ];
        return $this;
    }
    
    /**
     * Add float column
     */
    public function float($name, $total = 8, $places = 2)
    {
        $this->columns[$name] = [
            'type' => 'FLOAT',
            'total' => $total,
            'places' => $places
        ];
        return $this;
    }
    
    /**
     * Add decimal column
     */
    public function decimal($name, $total = 10, $places = 2)
    {
        $this->columns[$name] = [
            'type' => 'DECIMAL',
            'total' => $total,
            'places' => $places
        ];
        return $this;
    }
    
    /**
     * Add boolean column
     */
    public function boolean($name)
    {
        $this->columns[$name] = [
            'type' => 'TINYINT',
            'length' => 1
        ];
        return $this;
    }
    
    /**
     * Add date column
     */
    public function date($name)
    {
        $this->columns[$name] = [
            'type' => 'DATE'
        ];
        return $this;
    }
    
    /**
     * Add datetime column
     */
    public function dateTime($name)
    {
        $this->columns[$name] = [
            'type' => 'DATETIME'
        ];
        return $this;
    }
    
    /**
     * Add timestamp column
     */
    public function timestamp($name, $useCurrent = false)
    {
        $this->columns[$name] = [
            'type' => 'TIMESTAMP',
            'default' => $useCurrent ? 'CURRENT_TIMESTAMP' : null
        ];
        return $this;
    }
    
    /**
     * Add JSON column
     */
    public function json($name)
    {
        $this->columns[$name] = [
            'type' => 'JSON'
        ];
        return $this;
    }
    
    /**
     * Set column as nullable
     */
    public function nullable()
    {
        $lastColumn = end($this->columns);
        $lastKey = key($this->columns);
        $this->columns[$lastKey]['nullable'] = true;
        return $this;
    }
    
    /**
     * Set default value
     */
    public function default($value)
    {
        $lastColumn = end($this->columns);
        $lastKey = key($this->columns);
        $this->columns[$lastKey]['default'] = $value;
        return $this;
    }
    
    /**
     * Set unique constraint
     */
    public function unique($column)
    {
        $this->indexes[] = "UNIQUE KEY `{$column}_unique` (`{$column}`)";
        return $this;
    }
    
    /**
     * Add index
     */
    public function index($column)
    {
        $this->indexes[] = "INDEX `{$column}_index` (`{$column}`)";
        return $this;
    }
    
    /**
     * Generate CREATE TABLE SQL
     */
    public function toSql()
    {
        $sql = [];
        $columnsSql = [];
        
        foreach ($this->columns as $name => $options) {
            $columnsSql[] = $this->buildColumnDefinition($name, $options);
        }
        
        // Add indexes
        foreach ($this->indexes as $index) {
            $columnsSql[] = $index;
        }
        
        $columnsString = implode(",\n  ", $columnsSql);
        $sql[] = "CREATE TABLE `{$this->table}` (\n  {$columnsString}\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        return $sql;
    }
    
    /**
     * Generate ALTER TABLE SQL
     */
    public function toAlterSql()
    {
        $sql = [];
        
        foreach ($this->columns as $name => $options) {
            $columnDef = $this->buildColumnDefinition($name, $options);
            $sql[] = "ALTER TABLE `{$this->table}` ADD COLUMN {$columnDef};";
        }
        
        return implode("\n", $sql);
    }
    
    /**
     * Build column definition
     */
    protected function buildColumnDefinition($name, $options)
    {
        $definition = "`{$name}` {$options['type']}";
        
        if (isset($options['length'])) {
            $definition .= "({$options['length']})";
        } elseif (isset($options['total']) && isset($options['places'])) {
            $definition .= "({$options['total']},{$options['places']})";
        }
        
        if (isset($options['unsigned']) && $options['unsigned']) {
            $definition .= " UNSIGNED";
        }
        
        if (isset($options['nullable']) && $options['nullable']) {
            $definition .= " NULL";
        } else {
            $definition .= " NOT NULL";
        }
        
        if (isset($options['auto_increment']) && $options['auto_increment']) {
            $definition .= " AUTO_INCREMENT";
        }
        
        if (isset($options['default']) && $options['default'] !== null) {
            if ($options['default'] === 'CURRENT_TIMESTAMP') {
                $definition .= " DEFAULT CURRENT_TIMESTAMP";
            } else {
                $definition .= " DEFAULT '{$options['default']}'";
            }
        }
        
        if (isset($options['primary']) && $options['primary']) {
            $definition .= " PRIMARY KEY";
        }
        
        return $definition;
    }
}
