<?php

namespace App\Core\Http;

use App\Core\Logging\Logger;

class ViewRenderer
{
    private $viewPaths = [];
    
    public function __construct()
    {
        // Add default view paths
        $this->viewPaths[] = MODULES_PATH;
        $this->viewPaths[] = APP_PATH . DS . 'Views';
    }
    
    /**
     * Render a view
     * 
     * @param string $view View name (dot notation or path)
     * @param array $data Data to pass to view
     * @return string Rendered HTML
     */
    public function render($view, array $data = [])
    {
        $viewFile = $this->findView($view);
        
        if (!$viewFile) {
            Logger::error("View not found: $view");
            throw new \Exception("View '$view' not found");
        }
        
        Logger::debug("Rendering view: $view", ['file' => $viewFile]);
        
        extract($data);
        
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        
        return $content;
    }
    
    /**
     * Find view file
     * 
     * @param string $view View name
     * @return string|false View file path or false
     */
    private function findView($view)
    {
        // Convert dot notation to path
        $viewPath = str_replace('.', '/', $view) . '.php';
        
        foreach ($this->viewPaths as $basePath) {
            $fullPath = $basePath . DS . $viewPath;
            
            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }
        
        return false;
    }
    
    /**
     * Add view path
     * 
     * @param string $path View path
     */
    public function addViewPath($path)
    {
        if (!in_array($path, $this->viewPaths)) {
            $this->viewPaths[] = $path;
        }
    }
}
