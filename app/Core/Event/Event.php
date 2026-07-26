<?php
/**
 * Event - کلاس پایه رویدادها
 */

namespace OmniCMS\Core\Event;

class Event
{
    protected $name;
    protected $data;
    protected $stopped = false;
    
    public function __construct($name, $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    public function stopPropagation()
    {
        $this->stopped = true;
        return $this;
    }
    
    public function isPropagationStopped()
    {
        return $this->stopped;
    }
}
