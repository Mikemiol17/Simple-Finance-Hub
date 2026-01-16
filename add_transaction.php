<?php
require_once __DIR__ . '/finance.php';

require_login();

$user = current_user($pdo);
if (!$user) {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = null;

$type = 'expense';
$category = '';
$amount = '';
$description = '';
$date = date('Y-m-d');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? 'expense';
    $category = trim($_POST['category'] ?? '');
    $amount = trim($_POST['amount'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $date = $_POST['date'] ?? date('Y-m-d');

    if ($type !== 'income' && $type !== 'expense') {
        $errors[] = 'Invalid transaction type.';
    }

    if ($category === '') {
        $errors[] = 'Category is required.';
    }

    if ($amount === '' || !is_numeric($amount) || (float)$amount <= 0) {
        $errors[] = 'Amount must be a number greater than 0.';
    }

    $d = DateTime::createFromFormat('Y-m-d', $date);
    if (!$d || $d->format('Y-m-d') !== $date) {
        $errors[] = 'Please enter a valid date.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO transactions (user_id, type, category, amount, description, date) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $user['id'],
            $type,
            $category,
            (float)$amount,
            $description !== '' ? $description : null,
            $date,
        ]);

        $success = 'Transaction added successfully.';

        // Reset form for convenience.
        $type = 'expense';
        $category = '';
        $amount = '';
        $description = '';
        $date = date('Y-m-d');
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="grid">
    <div class="col-12 card">
        <h1>Add Transaction</h1>
        <p class="small">Add income or expense. Amount should be a positive number.</p>

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

        <form method="post" action="add_transaction.php">
            <div class="form-row">
                <div class="field">
                    <label for="type">Type</label>
                    <select id="type" name="type" required>
                        <option value="income" <?php echo $type === 'income' ? 'selected' : ''; ?>>Income</option>
                        <option value="expense" <?php echo $type === 'expense' ? 'selected' : ''; ?>>Expense</option>
                    </select>
                </div>

                <div class="field">
                    <label for="amount">Amount</label>
                    <input id="amount" name="amount" type="number" step="0.01" min="0.01" value="<?php echo e($amount); ?>" required>
                </div>

                <div class="field">
                    <label for="category">Category</label>
                    <input id="category" name="category" type="text" value="<?php echo e($category); ?>" placeholder="e.g. Salary, Food, Rent" required>
                </div>

                <div class="field">
                    <label for="date">Date</label>
                    <input id="date" name="date" type="date" value="<?php echo e($date); ?>" required>
                </div>

                <div class="field-full">
                    <label for="description">Description (optional)</label>
                    <textarea id="description" name="description" placeholder="Small note..." ><?php echo e($description); ?></textarea>
                </div>

                <div class="field-full">
                    <button class="btn btn-primary" type="submit">Save Transaction</button>
                    <a class="btn" href="dashboard.php">Back to Dashboard</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
