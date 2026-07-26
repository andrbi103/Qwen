<?php
/**
 * Dependency Injection Container
 * 
 * @package OmniCMS\Core\DependencyInjection
 */

namespace OmniCMS\Core\DependencyInjection;

use Closure;
use Exception;

class Container
{
    /**
     * @var Container Singleton instance
     */
    private static $instance = null;

    /**
     * @var array Registered services
     */
    private $services = [];

    /**
     * @var array Resolved instances
     */
    private $instances = [];

    /**
     * @var array Service aliases
     */
    private $aliases = [];

    /**
     * Get singleton instance
     * 
     * @return Container
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register a service
     * 
     * @param string $name Service name
     * @param mixed $concrete Service definition (Closure, class name, or value)
     * @param bool $shared Is singleton (shared instance)
     */
    public function set($name, $concrete, $shared = true)
    {
        $this->services[$name] = [
            'concrete' => $concrete,
            'shared' => $shared
        ];
    }

    /**
     * Register a singleton service
     * 
     * @param string $name Service name
     * @param mixed $concrete Service definition
     */
    public function singleton($name, $concrete)
    {
        $this->set($name, $concrete, true);
    }

    /**
     * Register a transient service
     * 
     * @param string $name Service name
     * @param mixed $concrete Service definition
     */
    public function transient($name, $concrete)
    {
        $this->set($name, $concrete, false);
    }

    /**
     * Create an alias for a service
     * 
     * @param string $alias Alias name
     * @param string $original Original service name
     */
    public function alias($alias, $original)
    {
        $this->aliases[$alias] = $original;
    }

    /**
     * Resolve a service
     * 
     * @param string $name Service name
     * @return mixed Resolved service
     * @throws Exception If service not found
     */
    public function get($name)
    {
        // Resolve alias
        if (isset($this->aliases[$name])) {
            $name = $this->aliases[$name];
        }

        // Return existing instance if shared
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        // Build service if registered
        if (isset($this->services[$name])) {
            $service = $this->services[$name];
            $instance = $this->build($service['concrete']);

            if ($service['shared']) {
                $this->instances[$name] = $instance;
            }

            return $instance;
        }

        // Try to auto-resolve class
        if (class_exists($name)) {
            return $this->build($name);
        }

        throw new Exception("Service not found: {$name}");
    }

    /**
     * Check if service exists
     * 
     * @param string $name Service name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->services[$name]) || isset($this->instances[$name]);
    }

    /**
     * Build a service instance
     * 
     * @param mixed $concrete Service definition
     * @return mixed Built instance
     */
    private function build($concrete)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        if (is_string($concrete) && class_exists($concrete)) {
            return $this->resolveClass($concrete);
        }

        return $concrete;
    }

    /**
     * Resolve class dependencies automatically
     * 
     * @param string $class Class name
     * @return object Instance
     */
    private function resolveClass($class)
    {
        $reflector = new \ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Target [$class] is not instantiable.");
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * Resolve constructor parameter dependencies
     * 
     * @param array $parameters Reflection parameters
     * @return array Resolved dependencies
     */
    private function resolveDependencies($parameters)
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type && !$type->isBuiltin()) {
                $dependencies[] = $this->get($type->getName());
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                throw new Exception(
                    "Unresolvable dependency in [{$parameter->getDeclaringClass()->getName()}]: " .
                    "{$parameter->getName()}"
                );
            }
        }

        return $dependencies;
    }

    /**
     * Remove a service
     * 
     * @param string $name Service name
     */
    public function forget($name)
    {
        unset($this->services[$name], $this->instances[$name]);
    }

    /**
     * Flush all services and instances
     */
    public function flush()
    {
        $this->services = [];
        $this->instances = [];
        $this->aliases = [];
    }
}
