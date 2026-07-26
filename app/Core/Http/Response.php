<?php
/**
 * HTTP Response Class
 * 
 * @package OmniCMS\Core\Http
 */

namespace OmniCMS\Core\Http;

class Response
{
    /**
     * @var string Response content
     */
    private $content = '';

    /**
     * @var int HTTP status code
     */
    private $statusCode = 200;

    /**
     * @var array HTTP headers
     */
    private $headers = [];

    /**
     * @var array Cookies to set
     */
    private $cookies = [];

    /**
     * Constructor
     * 
     * @param string $content Response content
     * @param int $statusCode HTTP status code
     * @param array $headers HTTP headers
     */
    public function __construct($content = '', $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        
        // Set default Content-Type
        if (!isset($this->headers['Content-Type'])) {
            $this->headers['Content-Type'] = 'text/html; charset=UTF-8';
        }
    }

    /**
     * Send response to client
     */
    public function send()
    {
        // Send status code
        http_response_code($this->statusCode);
        
        // Send headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        
        // Send cookies
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie['name'],
                $cookie['value'],
                $cookie['expire'],
                $cookie['path'],
                $cookie['domain'],
                $cookie['secure'],
                $cookie['httpOnly']
            );
        }
        
        // Send content
        echo $this->content;
    }

    /**
     * Get response content
     * 
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set response content
     * 
     * @param string $content Content
     * @return Response
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get status code
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set status code
     * 
     * @param int $statusCode Status code
     * @return Response
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
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
     * Get header value
     * 
     * @param string $name Header name
     * @param mixed $default Default value
     * @return mixed
     */
    public function getHeader($name, $default = null)
    {
        return $this->headers[$name] ?? $default;
    }

    /**
     * Set header
     * 
     * @param string $name Header name
     * @param string $value Header value
     * @return Response
     */
    public function withHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Set multiple headers
     * 
     * @param array $headers Headers array
     * @return Response
     */
    public function withHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    /**
     * Remove header
     * 
     * @param string $name Header name
     * @return Response
     */
    public function withoutHeader($name)
    {
        unset($this->headers[$name]);
        return $this;
    }

    /**
     * Set cookie
     * 
     * @param string $name Cookie name
     * @param string $value Cookie value
     * @param int $expire Expiration time (timestamp)
     * @param string $path Cookie path
     * @param string $domain Cookie domain
     * @param bool $secure Secure flag
     * @param bool $httpOnly HTTPOnly flag
     * @return Response
     */
    public function withCookie(
        $name,
        $value,
        $expire = 0,
        $path = '/',
        $domain = '',
        $secure = false,
        $httpOnly = true
    ) {
        $this->cookies[] = [
            'name' => $name,
            'value' => $value,
            'expire' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httpOnly' => $httpOnly
        ];
        return $this;
    }

    /**
     * Set JSON content
     * 
     * @param mixed $data Data to encode
     * @param int $options JSON options
     * @return Response
     */
    public function json($data, $options = 0)
    {
        $this->content = json_encode($data, $options);
        $this->headers['Content-Type'] = 'application/json; charset=UTF-8';
        return $this;
    }

    /**
     * Redirect to URL
     * 
     * @param string $url URL to redirect to
     * @param int $statusCode Redirect status code
     * @return Response
     */
    public function redirect($url, $statusCode = 302)
    {
        $this->statusCode = $statusCode;
        $this->headers['Location'] = $url;
        return $this;
    }

    /**
     * Download file
     * 
     * @param string $filename Filename
     * @param string $content File content
     * @return Response
     */
    public function download($filename, $content)
    {
        $this->content = $content;
        $this->headers['Content-Type'] = 'application/octet-stream';
        $this->headers['Content-Disposition'] = 'attachment; filename="' . basename($filename) . '"';
        $this->headers['Content-Length'] = strlen($content);
        return $this;
    }

    /**
     * Set cache headers
     * 
     * @param int $maxAge Max age in seconds
     * @return Response
     */
    public function cache($maxAge = 3600)
    {
        $this->headers['Cache-Control'] = 'public, max-age=' . $maxAge;
        $this->headers['Expires'] = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';
        return $this;
    }

    /**
     * Prevent caching
     * 
     * @return Response
     */
    public function noCache()
    {
        $this->headers['Cache-Control'] = 'no-cache, no-store, must-revalidate';
        $this->headers['Pragma'] = 'no-cache';
        $this->headers['Expires'] = '0';
        return $this;
    }

    /**
     * Enable CORS
     * 
     * @param string $origin Allowed origin
     * @return Response
     */
    public function cors($origin = '*')
    {
        $this->headers['Access-Control-Allow-Origin'] = $origin;
        $this->headers['Access-Control-Allow-Methods'] = 'GET, POST, PUT, DELETE, OPTIONS';
        $this->headers['Access-Control-Allow-Headers'] = 'Content-Type, Authorization, X-Requested-With';
        return $this;
    }
}
