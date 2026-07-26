<?php
/**
 * Memory Cache Driver (Array-based)
 */

namespace OmniCMS\Core\Cache;

class MemoryCache
{
    private $cache = [];
    private $defaultTtl;
    
    public function __construct($config)
    {
        $this->defaultTtl = $config['ttl'] ?? 300;
    }
    
    public function get($key)
    {
        if (!isset($this->cache[$key])) {
            return null;
        }
        
        $data = $this->cache[$key];
        
        // Check if expired
        if (isset($data['expires']) && time() > $data['expires']) {
            unset($this->cache[$key]);
            return null;
        }
        
        return $data['value'];
    }
    
    public function set($key, $value, $ttl = null)
    {
        $ttl = $ttl ?: $this->defaultTtl;
        
        $this->cache[$key] = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        return true;
    }
    
    public function delete($key)
    {
        if (isset($this->cache[$key])) {
            unset($this->cache[$key]);
        }
        
        return true;
    }
    
    public function clear()
    {
        $this->cache = [];
        return true;
    }
}
