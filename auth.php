<?php
// auth.php
// Authentication helpers.

require_once __DIR__ . '/init.php';

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function current_user(PDO $pdo): ?array
{
    if (!is_logged_in()) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, username, email, created_at FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    return $user ?: null;
}
