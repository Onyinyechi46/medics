<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

startSecureSession();

$errors = [];
$email = '';

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

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if (empty($errors)) {
        $checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $checkStmt->execute(['email' => $email]);

        if ($checkStmt->fetch()) {
            $errors[] = 'This email is already registered. Please log in.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
            $insertStmt->execute([
                'email' => $email,
                'password' => $passwordHash,
            ]);

            $_SESSION['flash_success'] = 'Registration successful. Please log in.';
            header('Location: login.php');
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
    <title>Register</title>
    <style>
        :root { color-scheme: light dark; }
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
        .links { margin-top: 1rem; text-align: center; }
        a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Create account</h1>
        <p class="muted">Register with your email and password.</p>

        <?php if ($errors): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="register.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" minlength="6" required>
            </div>

            <button type="submit">Register</button>
        </form>

        <div class="links">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </main>
</body>
</html>
