<?php
/**
 * Logger - Monolog-style Logging Engine
 * 
 * @package OmniCMS\Core\Log
 */

namespace OmniCMS\Core\Log;

class Logger
{
    /**
     * Log levels
     */
    const EMERGENCY = 'emergency';
    const ALERT = 'alert';
    const CRITICAL = 'critical';
    const ERROR = 'error';
    const WARNING = 'warning';
    const NOTICE = 'notice';
    const INFO = 'info';
    const DEBUG = 'debug';

    /**
     * @var array Log levels with severity
     */
    private static $levels = [
        self::DEBUG => 0,
        self::INFO => 1,
        self::NOTICE => 2,
        self::WARNING => 3,
        self::ERROR => 4,
        self::CRITICAL => 5,
        self::ALERT => 6,
        self::EMERGENCY => 7
    ];

    /**
     * @var string Minimum log level
     */
    private static $minLevel = self::DEBUG;

    /**
     * @var string Log file path
     */
    private static $logFile = null;

    /**
     * @var array Log entries buffer
     */
    private static $buffer = [];

    /**
     * Set minimum log level
     * 
     * @param string $level Log level
     */
    public static function setMinLevel($level)
    {
        self::$minLevel = $level;
    }

    /**
     * Set log file path
     * 
     * @param string $path File path
     */
    public static function setLogFile($path)
    {
        self::$logFile = $path;
    }

    /**
     * Log emergency message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function emergency($message, array $context = [])
    {
        self::log(self::EMERGENCY, $message, $context);
    }

    /**
     * Log alert message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function alert($message, array $context = [])
    {
        self::log(self::ALERT, $message, $context);
    }

    /**
     * Log critical message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function critical($message, array $context = [])
    {
        self::log(self::CRITICAL, $message, $context);
    }

    /**
     * Log error message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function error($message, array $context = [])
    {
        self::log(self::ERROR, $message, $context);
    }

    /**
     * Log warning message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function warning($message, array $context = [])
    {
        self::log(self::WARNING, $message, $context);
    }

    /**
     * Log notice message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function notice($message, array $context = [])
    {
        self::log(self::NOTICE, $message, $context);
    }

    /**
     * Log info message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function info($message, array $context = [])
    {
        self::log(self::INFO, $message, $context);
    }

    /**
     * Log debug message
     * 
     * @param string $message Message
     * @param array $context Context data
     */
    public static function debug($message, array $context = [])
    {
        self::log(self::DEBUG, $message, $context);
    }

    /**
     * Log message with level
     * 
     * @param string $level Log level
     * @param string $message Message
     * @param array $context Context data
     */
    public static function log($level, $message, array $context = [])
    {
        // Check if level should be logged
        if (!self::shouldLog($level)) {
            return;
        }

        // Format message with context
        $formattedMessage = self::formatMessage($message, $context);

        // Create log entry
        $entry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'level' => strtoupper($level),
            'message' => $formattedMessage,
            'context' => $context,
            'file' => self::getCallerInfo()
        ];

        // Add to buffer
        self::$buffer[] = $entry;

        // Write to file (immediate or batch)
        if (config('log.immediate', true)) {
            self::flush();
        }
    }

    /**
     * Check if level should be logged
     * 
     * @param string $level Log level
     * @return bool
     */
    private static function shouldLog($level)
    {
        return self::$levels[$level] >= self::$levels[self::$minLevel];
    }

    /**
     * Format message with context placeholders
     * 
     * @param string $message Message
     * @param array $context Context
     * @return string Formatted message
     */
    private static function formatMessage($message, array $context)
    {
        foreach ($context as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }

        return $message;
    }

    /**
     * Get caller file and line
     * 
     * @return string Caller info
     */
    private static function getCallerInfo()
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        
        if (isset($trace[3]) && isset($trace[3]['file']) && isset($trace[3]['line'])) {
            $file = basename($trace[3]['file']);
            $line = $trace[3]['line'];
            return $file . ':' . $line;
        }

        return 'unknown';
    }

    /**
     * Flush buffer to file
     */
    public static function flush()
    {
        if (empty(self::$buffer)) {
            return;
        }

        $logFile = self::$logFile ?? STORAGE_PATH . DS . 'logs' . DS . 'app.log';
        $logDir = dirname($logFile);

        // Create directory if not exists
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Format entries
        $lines = [];
        foreach (self::$buffer as $entry) {
            $lines[] = self::formatEntry($entry);
        }

        // Write to file
        file_put_contents($logFile, implode(PHP_EOL, $lines) . PHP_EOL, FILE_APPEND | LOCK_EX);

        // Clear buffer
        self::$buffer = [];
    }

    /**
     * Format log entry for file output
     * 
     * @param array $entry Log entry
     * @return string Formatted entry
     */
    private static function formatEntry(array $entry)
    {
        $jsonContext = !empty($entry['context']) ? ' ' . json_encode($entry['context']) : '';
        
        return sprintf(
            "[%s] %s.%s: %s%s",
            $entry['timestamp'],
            $entry['level'],
            $entry['file'],
            $entry['message'],
            $jsonContext
        );
    }

    /**
     * Get recent logs
     * 
     * @param int $limit Number of entries
     * @param string $level Filter by level
     * @return array Log entries
     */
    public static function getRecent($limit = 100, $level = null)
    {
        $logFile = self::$logFile ?? STORAGE_PATH . DS . 'logs' . DS . 'app.log';
        
        if (!file_exists($logFile)) {
            return [];
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_slice($lines, -$limit);

        $entries = [];
        foreach ($lines as $line) {
            $entry = self::parseLine($line);
            
            if ($entry && ($level === null || $entry['level'] === strtoupper($level))) {
                $entries[] = $entry;
            }
        }

        return $entries;
    }

    /**
     * Parse log line
     * 
     * @param string $line Log line
     * @return array|null Parsed entry
     */
    private static function parseLine($line)
    {
        $pattern = '/\[(.*?)\] (\w+)\.(\w+): (.*?)(?:\s+(.*))?$/';
        
        if (preg_match($pattern, $line, $matches)) {
            return [
                'timestamp' => $matches[1],
                'level' => $matches[2],
                'file' => $matches[3],
                'message' => $matches[4],
                'context' => $matches[5] ? json_decode($matches[5], true) : []
            ];
        }

        return null;
    }

    /**
     * Clear log file
     */
    public static function clear()
    {
        $logFile = self::$logFile ?? STORAGE_PATH . DS . 'logs' . DS . 'app.log';
        
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
    }

    /**
     * Rotate log file
     * 
     * @param int $maxFiles Maximum number of old files to keep
     */
    public static function rotate($maxFiles = 5)
    {
        $logFile = self::$logFile ?? STORAGE_PATH . DS . 'logs' . DS . 'app.log';
        $dir = dirname($logFile);
        $baseName = basename($logFile);
        
        // Remove oldest file
        $oldest = $dir . DS . $baseName . '.' . $maxFiles;
        if (file_exists($oldest)) {
            unlink($oldest);
        }
        
        // Rotate existing files
        for ($i = $maxFiles - 1; $i >= 1; $i--) {
            $old = $dir . DS . $baseName . '.' . $i;
            $new = $dir . DS . $baseName . '.' . ($i + 1);
            
            if (file_exists($old)) {
                rename($old, $new);
            }
        }
        
        // Rename current file
        if (file_exists($logFile)) {
            rename($logFile, $dir . DS . $baseName . '.1');
        }
    }
}
