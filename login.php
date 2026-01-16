<?php
require_once __DIR__ . '/init.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = null;
$usernameOrEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usernameOrEmail === '' || $password === '') {
        $error = 'Please enter your username/email and password.';
    } else {
        $stmt = $pdo->prepare('SELECT id, username, email, password FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];

            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid login credentials.';
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="grid">
    <div class="col-12 card">
        <h1>Login</h1>
        <p class="small">Login to access your dashboard.</p>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo e($error); ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="form-row">
                <div class="field">
                    <label for="username_or_email">Username or Email</label>
                    <input id="username_or_email" name="username_or_email" type="text" value="<?php echo e($usernameOrEmail); ?>" required>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div class="field-full">
                    <button class="btn btn-primary" type="submit">Login</button>
                    <a class="btn" href="register.php">Create an account</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
require_once __DIR__ . '/footer.php';
