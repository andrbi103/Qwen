<?php
/**
 * File Cache Driver
 */

namespace OmniCMS\Core\Cache;

class FileCache
{
    private $path;
    private $defaultTtl;
    
    public function __construct($config)
    {
        $this->path = $config['path'] ?? STORAGE_PATH . DS . 'cache' . DS . 'file';
        $this->defaultTtl = $config['ttl'] ?? 3600;
        
        // Create cache directory if not exists
        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }
    
    public function get($key)
    {
        $file = $this->getFilePath($key);
        
        if (!file_exists($file)) {
            return null;
        }
        
        $data = unserialize(file_get_contents($file));
        
        if ($data === false) {
            return null;
        }
        
        // Check if expired
        if (isset($data['expires']) && time() > $data['expires']) {
            $this->delete($key);
            return null;
        }
        
        return $data['value'];
    }
    
    public function set($key, $value, $ttl = null)
    {
        $ttl = $ttl ?: $this->defaultTtl;
        $file = $this->getFilePath($key);
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        return file_put_contents($file, serialize($data), LOCK_EX) !== false;
    }
    
    public function delete($key)
    {
        $file = $this->getFilePath($key);
        
        if (file_exists($file)) {
            return unlink($file);
        }
        
        return true;
    }
    
    public function clear()
    {
        $files = glob($this->path . DS . '*.cache');
        
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        
        return true;
    }
    
    private function getFilePath($key)
    {
        return $this->path . DS . md5($key) . '.cache';
    }
}
