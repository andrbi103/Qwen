<?php
/**
 * Core Module - System Base Module
 * Provides essential routes and controllers for the application
 */

namespace Modules\Core;

class CoreModule
{
    /**
     * Module configuration
     */
    public static function getConfig()
    {
        return [
            'name' => 'Core',
            'version' => '1.0.0',
            'description' => 'System core module providing base functionality',
            'active' => true,
            'priority' => 1 // Load first
        ];
    }
}
