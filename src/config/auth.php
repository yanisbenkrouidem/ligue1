<?php
/**
 * Cookie-based Authentication Helper
 * 
 * PHP sessions don't persist on Vercel serverless functions because each
 * request runs in an isolated, ephemeral container. This helper uses
 * HMAC-signed cookies to maintain authentication state across requests.
 */

define('AUTH_COOKIE_NAME', 'ligue1_auth');
define('AUTH_SECRET_KEY', 'L1gue1-Pr0d-S3cr3t-K3y-2024-BKD');

/**
 * Sign a pseudo with HMAC to prevent forgery.
 */
function auth_sign($pseudo) {
    return hash_hmac('sha256', $pseudo, AUTH_SECRET_KEY);
}

/**
 * Log a user in by setting a signed authentication cookie.
 * Call this instead of $_SESSION['user'] = $pseudo.
 */
function auth_login($pseudo) {
    $signature = auth_sign($pseudo);
    $cookieValue = base64_encode($pseudo . '|' . $signature);
    setcookie(AUTH_COOKIE_NAME, $cookieValue, [
        'expires' => time() + 86400 * 30, // 30 days
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    // Also set in session for the current request (before redirect)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user'] = $pseudo;
}

/**
 * Check if the user is authenticated.
 * Returns the pseudo if authenticated, null otherwise.
 * Also populates $_SESSION['user'] for backward compatibility.
 */
function auth_check() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // First check session (works for current request after auth_login)
    if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    // Then check cookie (works across Vercel serverless requests)
    if (isset($_COOKIE[AUTH_COOKIE_NAME])) {
        $decoded = base64_decode($_COOKIE[AUTH_COOKIE_NAME]);
        if ($decoded === false) return null;
        
        $parts = explode('|', $decoded, 2);
        if (count($parts) === 2) {
            $pseudo = $parts[0];
            $signature = $parts[1];
            if (hash_equals(auth_sign($pseudo), $signature)) {
                $_SESSION['user'] = $pseudo;
                return $pseudo;
            }
        }
    }

    return null;
}

/**
 * Log the user out by destroying session and clearing the auth cookie.
 */
function auth_logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['user']);
    session_destroy();
    setcookie(AUTH_COOKIE_NAME, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}
?>
