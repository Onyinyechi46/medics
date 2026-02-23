<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/auth.php';

requireLogin();

startSecureSession();
$userEmail = $_SESSION['user_email'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #eff6ff, #ecfeff);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1rem;
        }
        .panel {
            width: min(640px, 96vw);
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
        }
        h1 { margin-top: 0; color: #0f172a; }
        p { color: #334155; font-size: 1.05rem; }
        .email {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            display: inline-block;
            padding: 0.45rem 0.75rem;
            margin: 1rem 0;
            font-weight: 600;
            color: #0f172a;
        }
        form { margin-top: 1rem; }
        button {
            background: #dc2626;
            border: none;
            color: #fff;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <section class="panel">
        <h1>Application Page</h1>
        <p>You are logged in as:</p>
        <div class="email"><?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?></div>

        <form action="logout.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">Logout</button>
        </form>
    </section>
</body>
</html>
