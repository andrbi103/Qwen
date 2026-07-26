<?php
/**
 * CSRF Protection Class
 * محافظت در برابر حملات Cross-Site Request Forgery
 */

namespace OmniCMS\Core\Security;

class Csrf
{
    /**
     * Generate CSRF token
     * 
     * @return string
     */
    public static function generateToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Validate CSRF token
     * 
     * @param string $token
     * @return bool
     */
    public static function validateToken($token)
    {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Get CSRF token input field
     * 
     * @return string
     */
    public static function tokenField()
    {
        $token = self::generateToken();
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Regenerate CSRF token
     * 
     * @return string
     */
    public static function regenerateToken()
    {
        unset($_SESSION['csrf_token']);
        return self::generateToken();
    }
}
