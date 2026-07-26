<?php
/**
 * HTTP Request Class
 * 
 * @package OmniCMS\Core\Http
 */

namespace OmniCMS\Core\Http;

class Request
{
    /**
     * @var string HTTP method
     */
    private $method;

    /**
     * @var string Request URI
     */
    private $uri;

    /**
     * @var array GET parameters
     */
    private $query = [];

    /**
     * @var array POST parameters
     */
    private $body = [];

    /**
     * @var array Cookies
     */
    private $cookies = [];

    /**
     * @var array Headers
     */
    private $headers = [];

    /**
     * @var array Files
     */
    private $files = [];

    /**
     * @var string Base path
     */
    private $basePath = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '/';
        $this->query = $_GET ?? [];
        $this->body = $_POST ?? [];
        $this->cookies = $_COOKIE ?? [];
        $this->files = $_FILES ?? [];
        $this->headers = $this->parseHeaders();
        
        // Parse JSON body if present
        if ($this->isJson()) {
            $this->body = json_decode(file_get_contents('php://input'), true) ?: [];
        }
    }

    /**
     * Create request from globals
     * 
     * @return Request
     */
    public static function createFromGlobals()
    {
        return new self();
    }

    /**
     * Parse headers from $_SERVER
     * 
     * @return array
     */
    private function parseHeaders()
    {
        $headers = [];
        
        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $header = str_replace('_', '-', substr($key, 5));
                $headers[$header] = $value;
            } elseif ($key === 'CONTENT_TYPE') {
                $headers['Content-Type'] = $value;
            } elseif ($key === 'CONTENT_LENGTH') {
                $headers['Content-Length'] = $value;
            }
        }
        
        return $headers;
    }

    /**
     * Get HTTP method
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get request URI
     * 
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get path without query string
     * 
     * @return string
     */
    public function getPath()
    {
        $path = parse_url($this->uri, PHP_URL_PATH);
        return $path ?: '/';
    }

    /**
     * Check if request matches method
     * 
     * @param string $method HTTP method
     * @return bool
     */
    public function isMethod($method)
    {
        return strtoupper($this->method) === strtoupper($method);
    }

    /**
     * Check if request is AJAX
     * 
     * @return bool
     */
    public function isAjax()
    {
        return isset($this->headers['X-Requested-With']) && 
               $this->headers['X-Requested-With'] === 'XMLHttpRequest';
    }

    /**
     * Check if request expects JSON
     * 
     * @return bool
     */
    public function wantsJson()
    {
        $accept = $this->getHeader('Accept');
        return strpos($accept, 'application/json') !== false;
    }

    /**
     * Check if request is JSON
     * 
     * @return bool
     */
    public function isJson()
    {
        $contentType = $this->getHeader('Content-Type');
        return strpos($contentType, 'application/json') !== false;
    }

    /**
     * Get query parameter
     * 
     * @param string $key Parameter name
     * @param mixed $default Default value
     * @return mixed
     */
    public function query($key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        
        return $this->query[$key] ?? $default;
    }

    /**
     * Get POST/body parameter
     * 
     * @param string $key Parameter name
     * @param mixed $default Default value
     * @return mixed
     */
    public function input($key = null, $default = null)
    {
        if ($key === null) {
            return $this->body;
        }
        
        return $this->body[$key] ?? $default;
    }

    /**
     * Get all input data
     * 
     * @return array
     */
    public function all()
    {
        return array_merge($this->query, $this->body);
    }

    /**
     * Check if input has key
     * 
     * @param string $key Key to check
     * @return bool
     */
    public function has($key)
    {
        return isset($this->body[$key]) || isset($this->query[$key]);
    }

    /**
     * Get cookie value
     * 
     * @param string $key Cookie name
     * @param mixed $default Default value
     * @return mixed
     */
    public function cookie($key = null, $default = null)
    {
        if ($key === null) {
            return $this->cookies;
        }
        
        return $this->cookies[$key] ?? $default;
    }

    /**
     * Get header value
     * 
     * @param string $key Header name
     * @param mixed $default Default value
     * @return mixed
     */
    public function getHeader($key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get all headers
     * 
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get uploaded file
     * 
     * @param string $key File input name
     * @return array|null
     */
    public function file($key)
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Get all files
     * 
     * @return array
     */
    public function files()
    {
        return $this->files;
    }

    /**
     * Check if file was uploaded
     * 
     * @param string $key File input name
     * @return bool
     */
    public function hasFile($key)
    {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Get client IP address
     * 
     * @return string
     */
    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
    }

    /**
     * Get user agent
     * 
     * @return string
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Get referrer URL
     * 
     * @return string|null
     */
    public function referrer()
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

    /**
     * Set base path
     * 
     * @param string $path Base path
     */
    public function setBasePath($path)
    {
        $this->basePath = rtrim($path, '/');
    }

    /**
     * Get base path
     * 
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * Check if request is secure (HTTPS)
     * 
     * @return bool
     */
    public function isSecure()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ||
               (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    }

    /**
     * Get request URL with query string
     * 
     * @return string
     */
    public function url()
    {
        $scheme = $this->isSecure() ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . $this->uri;
    }
}
