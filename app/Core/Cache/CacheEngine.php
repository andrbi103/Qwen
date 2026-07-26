<?php
/**
 * Cache Engine - موتور کش چندلایه
 * پشتیبانی از کش حافظه، فایل و توزیع شده
 */

namespace OmniCMS\Core\Cache;

class CacheEngine
{
    private static $instance = null;
    private $drivers = [];
    private $defaultDriver = 'file';
    private $config = [];
    
    /**
     * Constructor
     */
    private function __construct()
    {
        $this->config = [
            'file' => [
                'path' => STORAGE_PATH . DS . 'cache' . DS . 'file',
                'ttl' => 3600
            ],
            'memory' => [
                'ttl' => 300
            ],
            'redis' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'ttl' => 3600
            ]
        ];
        
        $this->initializeDrivers();
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize cache drivers
     */
    private function initializeDrivers()
    {
        $this->drivers['file'] = new FileCache($this->config['file']);
        $this->drivers['memory'] = new MemoryCache($this->config['memory']);
        
        // Redis driver (optional)
        if (extension_loaded('redis')) {
            $this->drivers['redis'] = new RedisCache($this->config['redis']);
        }
    }
    
    /**
     * Get value from cache
     * 
     * @param string $key
     * @param mixed $default
     * @param string|null $driver
     * @return mixed
     */
    public function get($key, $default = null, $driver = null)
    {
        $driver = $driver ?: $this->defaultDriver;
        
        // Try memory cache first (fastest)
        if (isset($this->drivers['memory'])) {
            $value = $this->drivers['memory']->get($key);
            if ($value !== null) {
                return $value;
            }
        }
        
        // Try main driver
        if (isset($this->drivers[$driver])) {
            $value = $this->drivers[$driver]->get($key);
            if ($value !== null) {
                // Store in memory cache for faster access
                if (isset($this->drivers['memory'])) {
                    $this->drivers['memory']->set($key, $value, 60);
                }
                return $value;
            }
        }
        
        return $default;
    }
    
    /**
     * Set value in cache
     * 
     * @param string $key
     * @param mixed $value
     * @param int $ttl Time to live in seconds
     * @param string|null $driver
     * @return bool
     */
    public function set($key, $value, $ttl = 3600, $driver = null)
    {
        $driver = $driver ?: $this->defaultDriver;
        
        if (isset($this->drivers[$driver])) {
            return $this->drivers[$driver]->set($key, $value, $ttl);
        }
        
        return false;
    }
    
    /**
     * Delete value from cache
     * 
     * @param string $key
     * @param string|null $driver
     * @return bool
     */
    public function delete($key, $driver = null)
    {
        $deleted = false;
        
        // Delete from all drivers
        foreach ($this->drivers as $driverName => $driverInstance) {
            if ($driverInstance->delete($key)) {
                $deleted = true;
            }
        }
        
        return $deleted;
    }
    
    /**
     * Clear all cache
     * 
     * @param string|null $driver
     * @return bool
     */
    public function clear($driver = null)
    {
        if ($driver) {
            if (isset($this->drivers[$driver])) {
                return $this->drivers[$driver]->clear();
            }
            return false;
        }
        
        // Clear all drivers
        $cleared = true;
        foreach ($this->drivers as $driverInstance) {
            if (!$driverInstance->clear()) {
                $cleared = false;
            }
        }
        
        return $cleared;
    }
    
    /**
     * Remember - get from cache or execute callback
     * 
     * @param string $key
     * @param int $ttl
     * @param callable $callback
     * @param string|null $driver
     * @return mixed
     */
    public function remember($key, $ttl, callable $callback, $driver = null)
    {
        $value = $this->get($key, null, $driver);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $ttl, $driver);
        
        return $value;
    }
    
    /**
     * Set default driver
     * 
     * @param string $driver
     */
    public function setDefaultDriver($driver)
    {
        if (isset($this->drivers[$driver])) {
            $this->defaultDriver = $driver;
        }
    }
}
