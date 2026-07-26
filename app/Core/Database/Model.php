<?php
/**
 * ORM Base Model - کلاس پایه برای مدل‌های پایگاه داده
 */

namespace OmniCMS\Core\Database;

use OmniCMS\Core\Database\Connection;
use OmniCMS\Core\Event\Dispatcher;

class Model
{
    protected static $table;
    protected static $primaryKey = 'id';
    protected static $connection;
    protected $attributes = [];
    
    /**
     * Get database connection
     */
    protected static function getConnection()
    {
        if (!self::$connection) {
            self::$connection = Connection::getInstance();
        }
        return self::$connection;
    }
    
    /**
     * Get table name
     */
    protected static function getTable()
    {
        if (!static::$table) {
            // Auto-detect table name from class name
            $className = explode('\\', get_called_class());
            $className = end($className);
            static::$table = strtolower($className) . 's';
        }
        return static::$table;
    }
    
    /**
     * Find record by ID
     */
    public static function find($id)
    {
        $table = static::getTable();
        $primaryKey = static::$primaryKey;
        
        $sql = "SELECT * FROM {$table} WHERE {$primaryKey} = :id LIMIT 1";
        $stmt = self::getConnection()->query($sql, ['id' => $id]);
        $data = $stmt->fetch();
        
        if ($data) {
            return new static($data);
        }
        
        return null;
    }
    
    /**
     * Get all records
     */
    public static function all()
    {
        $table = static::getTable();
        $sql = "SELECT * FROM {$table}";
        $stmt = self::getConnection()->query($sql);
        
        return static::hydrateCollection($stmt->fetchAll());
    }
    
    /**
     * Find records by condition
     */
    public static function where($column, $operator, $value = null)
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $table = static::getTable();
        $sql = "SELECT * FROM {$table} WHERE {$column} {$operator} :value";
        $stmt = self::getConnection()->query($sql, ['value' => $value]);
        
        return static::hydrateCollection($stmt->fetchAll());
    }
    
    /**
     * Create new record
     */
    public static function create($data)
    {
        $table = static::getTable();
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $id = self::getConnection()->insert($sql, $data);
        
        // Fire event
        Dispatcher::fire('model.created', [
            'model' => get_called_class(),
            'id' => $id,
            'data' => $data
        ]);
        
        return static::find($id);
    }
    
    /**
     * Update record
     */
    public function update($data)
    {
        $table = static::getTable();
        $primaryKey = static::$primaryKey;
        
        $sets = [];
        foreach ($data as $key => $value) {
            $sets[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $sets);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$primaryKey} = :id";
        $data['id'] = $this->attributes[$primaryKey];
        
        self::getConnection()->query($sql, $data);
        
        // Update attributes
        $this->attributes = array_merge($this->attributes, $data);
        
        // Fire event
        Dispatcher::fire('model.updated', [
            'model' => get_called_class(),
            'id' => $this->attributes[$primaryKey],
            'data' => $data
        ]);
        
        return true;
    }
    
    /**
     * Delete record
     */
    public function delete()
    {
        $table = static::getTable();
        $primaryKey = static::$primaryKey;
        
        $sql = "DELETE FROM {$table} WHERE {$primaryKey} = :id";
        self::getConnection()->query($sql, ['id' => $this->attributes[$primaryKey]]);
        
        // Fire event
        Dispatcher::fire('model.deleted', [
            'model' => get_called_class(),
            'id' => $this->attributes[$primaryKey]
        ]);
        
        return true;
    }
    
    /**
     * Get attribute
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }
    
    /**
     * Set attribute
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }
    
    /**
     * Check if attribute exists
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }
    
    /**
     * Constructor
     */
    public function __construct($data = [])
    {
        $this->attributes = $data;
    }
    
    /**
     * Hydrate collection of models
     */
    protected static function hydrateCollection($data)
    {
        $collection = [];
        foreach ($data as $item) {
            $collection[] = new static($item);
        }
        return $collection;
    }
    
    /**
     * Convert to array
     */
    public function toArray()
    {
        return $this->attributes;
    }
    
    /**
     * Convert to JSON
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
