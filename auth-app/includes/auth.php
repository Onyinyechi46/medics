<?php
declare(strict_types=1);

function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) return;

    // Better HTTPS detection (works behind proxies / shared hosting)
    $isHttps =
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (!empty($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443)
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

    // IMPORTANT: If your site is running on http:// (no SSL), secure cookies MUST be false
    // Otherwise browser won't store the session cookie.
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',          // keep empty unless you know you need a specific domain
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',     // was Strict; Lax avoids common login/redirect issues
    ]);

    session_start();
}

function generateCsrfToken(): string
{
    startSecureSession();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken(?string $submittedToken): bool
{
    startSecureSession();

    if (!isset($_SESSION['csrf_token']) || !$submittedToken) return false;
    return hash_equals($_SESSION['csrf_token'], $submittedToken);
}

function isLoggedIn(): bool
{
    startSecureSession();
    return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function loginUser(int $userId, string $email): void
{
    startSecureSession();
    session_regenerate_id(true);

    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $email;
}

function logoutUser(): void
{
    startSecureSession();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        // PHP 7.3+ supports options array; use it when possible
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'] ?? '/',
            'domain' => $params['domain'] ?? '',
            'secure' => (bool)($params['secure'] ?? false),
            'httponly' => (bool)($params['httponly'] ?? true),
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}