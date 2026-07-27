<?php

namespace OmniCMS\Core\Http;

use OmniCMS\Core\Log\Logger;

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
        
        // Try direct paths first
        foreach ($this->viewPaths as $basePath) {
            $fullPath = $basePath . DS . $viewPath;
            
            if (file_exists($fullPath)) {
                return $fullPath;
            }
        }
        
        // Try Module structure: Modules/{Module}/Views/{type}/{controller}/{action}.php
        // Expected format: type.module.controller.action or type.controller.action
        $parts = explode('.', $view);
        
        if (count($parts) >= 3) {
            // Format: type.module.controller.action (e.g., front.core.home.index)
            $type = $parts[0];
            $module = ucfirst($parts[1]);
            $controller = $parts[2];
            $action = $parts[3] ?? 'index';
            
            $moduleViewPath = MODULES_PATH . DS . $module . DS . 'Views' . DS . $type . DS . $controller . DS . $action . '.php';
            
            if (file_exists($moduleViewPath)) {
                return $moduleViewPath;
            }
            
            // Also try without module in path for Core module views
            // Format: front.home.index -> Modules/Core/Views/front/home/index.php
            if (count($parts) == 3) {
                $type = $parts[0];
                $controller = $parts[1];
                $action = $parts[2];
                
                $coreViewPath = MODULES_PATH . DS . 'Core' . DS . 'Views' . DS . $type . DS . $controller . DS . $action . '.php';
                
                if (file_exists($coreViewPath)) {
                    return $coreViewPath;
                }
            }
        }
        
        // Try app/Views structure
        $appViewPath = APP_PATH . DS . 'Views' . DS . $viewPath;
        if (file_exists($appViewPath)) {
            return $appViewPath;
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
