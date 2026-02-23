<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

startSecureSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? null;
    if (!validateCsrfToken($csrfToken)) {
        header('Location: application.php');
        exit;
    }
}

logoutUser();
header('Location: login.php');
exit;
