<?php

namespace App\Core\Logging;

class Logger
{
    private static $logFile;
    private static $instance = null;

    private function __construct()
    {
        $logDir = __DIR__ . '/../../Logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        self::$logFile = $logDir . '/' . date('Y-m-d') . '.log';
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function info($message, $context = [])
    {
        self::log('INFO', $message, $context);
    }

    public static function warning($message, $context = [])
    {
        self::log('WARNING', $message, $context);
    }

    public static function error($message, $context = [])
    {
        self::log('ERROR', $message, $context);
    }

    public static function debug($message, $context = [])
    {
        self::log('DEBUG', $message, $context);
    }

    private static function log($level, $message, $context = [])
    {
        $logger = self::getInstance();
        $timestamp = date('Y-m-d H:i:s');
        $messageStr = is_string($message) ? $message : json_encode($message);
        
        if (!empty($context)) {
            $messageStr .= ' ' . json_encode($context);
        }

        $logEntry = "[$timestamp] [$level] $messageStr" . PHP_EOL;
        
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }
}
