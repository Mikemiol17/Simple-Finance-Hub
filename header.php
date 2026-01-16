<?php
require_once __DIR__ . '/init.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SimpleFinance Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="nav">
    <div class="nav-inner">
        <a class="brand" href="<?php echo is_logged_in() ? 'dashboard.php' : 'index.php'; ?>">SimpleFinance Manager</a>
        <nav class="nav-links">
            <?php if (is_logged_in()): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="add_transaction.php">Add Transaction</a>
                <a href="report.php">Report</a>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
