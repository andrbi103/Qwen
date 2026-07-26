<?php
/**
 * PSR-4 Style Autoloader
 * 
 * @package OmniCMS\Core
 */

namespace OmniCMS\Core;

class Autoloader
{
    /**
     * @var array Namespace prefixes to directory mappings
     */
    private static $prefixes = [];

    /**
     * Register autoloader with SPL
     */
    public static function register()
    {
        // Register base namespace
        self::addNamespace('OmniCMS\\', APP_PATH . DS);
        
        // Register module namespaces dynamically
        self::registerModuleNamespaces();
        
        // Register plugin namespaces dynamically
        self::registerPluginNamespaces();
        
        spl_autoload_register([__CLASS__, 'autoload'], true, true);
    }

    /**
     * Add a namespace prefix to directory mapping
     * 
     * @param string $prefix Namespace prefix
     * @param string $baseDir Base directory
     */
    public static function addNamespace($prefix, $baseDir)
    {
        $prefix = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, DS) . DS;
        
        if (!isset(self::$prefixes[$prefix])) {
            self::$prefixes[$prefix] = [];
        }
        
        self::$prefixes[$prefix][] = $baseDir;
    }

    /**
     * Autoload function
     * 
     * @param string $class Fully qualified class name
     * @return bool True if loaded, false otherwise
     */
    public static function autoload($class)
    {
        $prefix = '';
        $relativeClass = '';
        
        // Get the prefix and relative class name
        foreach (array_keys(self::$prefixes) as $prefixTest) {
            if (strpos($class, $prefixTest) === 0) {
                $prefix = $prefixTest;
                $relativeClass = substr($class, strlen($prefix));
                break;
            }
        }
        
        if (!$prefix) {
            return false;
        }
        
        // Convert namespace separators to directory separators
        $file = str_replace('\\', DS, $relativeClass) . '.php';
        
        // Look through all base directories for this prefix
        foreach (self::$prefixes[$prefix] as $baseDir) {
            $filePath = $baseDir . $file;
            
            if (file_exists($filePath)) {
                require_once $filePath;
                return true;
            }
        }
        
        return false;
    }

    /**
     * Register module namespaces
     */
    private static function registerModuleNamespaces()
    {
        if (!is_dir(MODULES_PATH)) {
            return;
        }
        
        $modules = scandir(MODULES_PATH);
        foreach ($modules as $module) {
            if ($module === '.' || $module === '..' || !is_dir(MODULES_PATH . DS . $module)) {
                continue;
            }
            
            $namespace = 'Modules\\' . $module . '\\';
            $basePath = MODULES_PATH . DS . $module . DS;
            self::addNamespace($namespace, $basePath);
        }
    }

    /**
     * Register plugin namespaces
     */
    private static function registerPluginNamespaces()
    {
        if (!is_dir(PLUGINS_PATH)) {
            return;
        }
        
        $plugins = scandir(PLUGINS_PATH);
        foreach ($plugins as $plugin) {
            if ($plugin === '.' || $plugin === '..' || !is_dir(PLUGINS_PATH . DS . $plugin)) {
                continue;
            }
            
            $namespace = 'Plugins\\' . $plugin . '\\';
            $basePath = PLUGINS_PATH . DS . $plugin . DS;
            self::addNamespace($namespace, $basePath);
        }
    }
}
