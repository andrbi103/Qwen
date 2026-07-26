<?php
/**
 * Redis Cache Driver
 */

namespace OmniCMS\Core\Cache;

class RedisCache
{
    private $redis;
    private $defaultTtl;
    
    public function __construct($config)
    {
        $this->defaultTtl = $config['ttl'] ?? 3600;
        
        try {
            $this->redis = new \Redis();
            $this->redis->connect(
                $config['host'] ?? '127.0.0.1',
                $config['port'] ?? 6379
            );
            
            if (isset($config['password'])) {
                $this->redis->auth($config['password']);
            }
            
            if (isset($config['database'])) {
                $this->redis->select($config['database']);
            }
        } catch (\Exception $e) {
            // Log error and disable redis driver
            \OmniCMS\Core\Log\Logger::error('Redis connection failed: ' . $e->getMessage());
            $this->redis = null;
        }
    }
    
    public function get($key)
    {
        if (!$this->redis) {
            return null;
        }
        
        $value = $this->redis->get($key);
        
        return $value !== false ? $value : null;
    }
    
    public function set($key, $value, $ttl = null)
    {
        if (!$this->redis) {
            return false;
        }
        
        $ttl = $ttl ?: $this->defaultTtl;
        
        return $this->redis->setex($key, $ttl, $value);
    }
    
    public function delete($key)
    {
        if (!$this->redis) {
            return false;
        }
        
        return $this->redis->del($key) > 0;
    }
    
    public function clear()
    {
        if (!$this->redis) {
            return false;
        }
        
        return $this->redis->flushDB();
    }
}
