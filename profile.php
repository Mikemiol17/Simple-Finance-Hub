<?php
require_once __DIR__ . '/auth.php';

require_login();

$user = current_user($pdo);
if (!$user) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/header.php';
?>

<div class="grid">
    <div class="col-12 card">
        <h1>Profile</h1>
        <p class="small">Your account details.</p>

        <table class="table">
            <tbody>
                <tr>
                    <th style="width:180px;">Username</th>
                    <td><?php echo e($user['username']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo e($user['email']); ?></td>
                </tr>
                <tr>
                    <th>Joined</th>
                    <td><?php echo e($user['created_at']); ?></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top:12px;">
            <a class="btn" href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
