<?php
require_once __DIR__ . '/init.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$errors = [];
$success = null;

$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '' || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '' || strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$username, $email, $hash]);

            $success = 'Registration successful. You can now login.';
            $username = '';
            $email = '';
        } catch (PDOException $e) {
            // Common beginner-friendly handling for duplicate username/email.
            if ((int)($e->errorInfo[1] ?? 0) === 1062) {
                $errors[] = 'Username or email already exists. Please try another.';
            } else {
                $errors[] = 'Something went wrong. Please try again.';
            }
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="grid">
    <div class="col-12 card">
        <h1>Create Account</h1>
        <p class="small">Register to start tracking your income and expenses.</p>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo e($success); ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <strong>Please fix the following:</strong>
                <div>
                    <?php foreach ($errors as $err): ?>
                        <div><?php echo e($err); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="post" action="register.php" class="form">
            <div class="form-row">
                <div class="field">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="<?php echo e($username); ?>" required>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="<?php echo e($email); ?>" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div class="field">
                    <label for="confirm_password">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required>
                </div>
                <div class="field-full">
                    <button class="btn btn-primary" type="submit">Register</button>
                    <a class="btn" href="login.php">Already have an account?</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
