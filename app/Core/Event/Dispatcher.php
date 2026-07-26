<?php
/**
 * Event Dispatcher - Event Driven Architecture
 * 
 * @package OmniCMS\Core\Event
 */

namespace OmniCMS\Core\Event;

class Dispatcher
{
    /**
     * @var array Registered listeners
     */
    private $listeners = [];

    /**
     * @var array Event queue for async processing
     */
    private $eventQueue = [];

    /**
     * Subscribe to an event
     * 
     * @param string $eventName Event name
     * @param callable $listener Listener callback
     * @param int $priority Priority (higher = earlier)
     */
    public function listen($eventName, callable $listener, $priority = 0)
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = [];
        }
        
        $this->listeners[$eventName][] = [
            'callback' => $listener,
            'priority' => $priority
        ];
        
        // Sort by priority
        usort($this->listeners[$eventName], function($a, $b) {
            return $b['priority'] - $a['priority'];
        });
    }

    /**
     * Dispatch an event
     * 
     * @param string $eventName Event name
     * @param mixed $data Event data
     * @return array Responses from listeners
     */
    public function dispatch($eventName, $data = null)
    {
        $responses = [];
        
        if (!isset($this->listeners[$eventName])) {
            return $responses;
        }
        
        foreach ($this->listeners[$eventName] as $listener) {
            try {
                $response = call_user_func($listener['callback'], $data, $eventName);
                if ($response !== null) {
                    $responses[] = $response;
                }
            } catch (\Exception $e) {
                // Log error but continue with other listeners
                \OmniCMS\Core\Log\Logger::error(
                    "Event listener error: {$e->getMessage()}",
                    ['event' => $eventName, 'listener' => get_class($listener['callback'])]
                );
            }
        }
        
        return $responses;
    }

    /**
     * Queue event for async processing
     * 
     * @param string $eventName Event name
     * @param mixed $data Event data
     */
    public function queue($eventName, $data = null)
    {
        $this->eventQueue[] = [
            'event' => $eventName,
            'data' => $data,
            'queued_at' => time()
        ];
    }

    /**
     * Process queued events
     * 
     * @param int $limit Max events to process
     * @return int Number of processed events
     */
    public function processQueue($limit = 100)
    {
        $processed = 0;
        
        while ($processed < $limit && !empty($this->eventQueue)) {
            $event = array_shift($this->eventQueue);
            $this->dispatch($event['event'], $event['data']);
            $processed++;
        }
        
        return $processed;
    }

    /**
     * Check if event has listeners
     * 
     * @param string $eventName Event name
     * @return bool
     */
    public function hasListeners($eventName)
    {
        return isset($this->listeners[$eventName]) && !empty($this->listeners[$eventName]);
    }

    /**
     * Remove listener
     * 
     * @param string $eventName Event name
     * @param callable $listener Listener to remove
     */
    public function removeListener($eventName, callable $listener)
    {
        if (!isset($this->listeners[$eventName])) {
            return;
        }
        
        $this->listeners[$eventName] = array_filter(
            $this->listeners[$eventName],
            function($registered) use ($listener) {
                return $registered['callback'] !== $listener;
            }
        );
    }

    /**
     * Clear all listeners for an event
     * 
     * @param string $eventName Event name
     */
    public function clearListeners($eventName)
    {
        unset($this->listeners[$eventName]);
    }

    /**
     * Get all registered events
     * 
     * @return array
     */
    public function getEvents()
    {
        return array_keys($this->listeners);
    }

    /**
     * Create and dispatch action event
     * 
     * @param string $actionName Action name
     * @param mixed $data Data
     * @return array Responses
     */
    public function action($actionName, $data = null)
    {
        return $this->dispatch('action.' . $actionName, $data);
    }

    /**
     * Create and dispatch filter event (modify data through chain)
     * 
     * @param string $filterName Filter name
     * @param mixed $value Value to filter
     * @return mixed Filtered value
     */
    public function filter($filterName, $value = null)
    {
        if (!isset($this->listeners['filter.' . $filterName])) {
            return $value;
        }
        
        foreach ($this->listeners['filter.' . $filterName] as $listener) {
            $value = call_user_func($listener['callback'], $value, $filterName);
        }
        
        return $value;
    }

    /**
     * Create and dispatch hook event
     * 
     * @param string $hookName Hook name
     * @param mixed $data Data
     * @return array Responses
     */
    public function hook($hookName, $data = null)
    {
        return $this->dispatch('hook.' . $hookName, $data);
    }
}
