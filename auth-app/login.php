<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

startSecureSession();

if (isLoggedIn()) {
    header('Location: application.php');
    exit;
}

$errors = [];
$email = '';
$successMessage = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? null;

    if (!validateCsrfToken($csrfToken)) {
        $errors[] = 'Invalid request token. Please refresh and try again.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Please enter your password.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id, email, password FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            $errors[] = 'Incorrect email or password.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: application.php');
            exit;
        }
    }
}

$csrfToken = generateCsrfToken();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f2f4f7;
            min-height: 100vh;
            display: grid;
            place-items: center;
        }
        .card {
            width: min(420px, 92vw);
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }
        h1 { margin-top: 0; font-size: 1.6rem; color: #1f2937; }
        .muted { color: #6b7280; margin-bottom: 1rem; }
        .field { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.45rem; font-weight: 600; color: #111827; }
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 0.7rem 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            border: none;
            padding: 0.8rem;
            background: #2563eb;
            color: #fff;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .errors {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            border-radius: 8px;
            padding: 0.7rem 0.9rem;
            margin-bottom: 1rem;
        }
        .success {
            background: #dcfce7;
            border: 1px solid #22c55e;
            color: #166534;
            border-radius: 8px;
            padding: 0.7rem 0.9rem;
            margin-bottom: 1rem;
        }
        .links { margin-top: 1rem; text-align: center; }
        a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Welcome back</h1>
        <p class="muted">Sign in to access your application page.</p>

        <?php if ($successMessage): ?>
            <div class="success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="links">
            Need an account? <a href="register.php">Register</a>
        </div>
    </main>
</body>
</html>
