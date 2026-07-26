<?php
/**
 * CSRF Protection Middleware
 * 
 * @package OmniCMS\Http\Middleware
 */

namespace OmniCMS\Http\Middleware;

use OmniCMS\Core\Http\Request;
use OmniCMS\Core\Http\Response;

class CsrfMiddleware
{
    /**
     * Exempt methods from CSRF check
     * 
     * @var array
     */
    private $exemptMethods = ['GET', 'HEAD', 'OPTIONS'];

    /**
     * Handle the request
     * 
     * @param Request $request Request object
     * @return Response|null Response or null to continue
     */
    public function handle(Request $request)
    {
        // Skip CSRF check for safe methods
        if (in_array($request->getMethod(), $this->exemptMethods)) {
            return null;
        }

        // Get CSRF token from request
        $token = $request->input('csrf_token') ?? $request->getHeader('X-CSRF-Token');

        // Verify token
        if (!$token || !verify_csrf($token)) {
            // Check if it's an API request
            if ($request->wantsJson() || strpos($request->getPath(), '/api/') !== false) {
                return json_response([
                    'success' => false,
                    'message' => 'CSRF token mismatch. Please refresh the page.'
                ], 419);
            }

            // Return error page
            $response = new Response($this->errorPage(), 403);
            $response->withHeader('Content-Type', 'text/html; charset=UTF-8');
            return $response;
        }

        return null; // Continue to next middleware/handler
    }

    /**
     * Generate CSRF error page
     * 
     * @return string HTML
     */
    private function errorPage()
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Error</title>
    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
        }
        h1 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        p {
            color: #666;
            line-height: 1.6;
        }
        button {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>⚠️ خطای امنیتی</h1>
        <p>توکن امنیتی نامعتبر است. لطفاً صفحه را مجدداً بارگذاری کرده و عملیات خود را تکرار کنید.</p>
        <p style="font-size: 12px; color: #999;">Security Token Mismatch</p>
        <button onclick="location.reload()">بارگذاری مجدد</button>
    </div>
</body>
</html>
HTML;
    }
}
