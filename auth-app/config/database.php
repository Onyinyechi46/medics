<?php
declare(strict_types=1);

/**
 * auth-app/config/database.php
 * Update the constants below to match your XAMPP MySQL/phpMyAdmin credentials.
 */

// --- EDIT THESE ---
const DB_HOST = '127.0.0.1';     // or 'localhost'
const DB_NAME = 'auth_app';        // <-- your database name in phpMyAdmin
const DB_USER = 'root';          // <-- usually 'root' on XAMPP
const DB_PASS = '';              // <-- usually '' (empty) on XAMPP unless you set one
// ------------------

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    return $pdo;
}