<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    header('Location: application.php');
    exit;
}

header('Location: login.php');
exit;