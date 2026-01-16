<?php
require_once __DIR__ . '/finance.php';

require_login();

$user = current_user($pdo);
if (!$user) {
    header('Location: login.php');
    exit;
}

$totalsAll = get_totals($pdo, (int)$user['id']);
$transactions = get_transactions($pdo, (int)$user['id'], 500);

$netClass = $totalsAll['net'] >= 0 ? 'good' : 'bad';

require_once __DIR__ . '/header.php';
?>

<div class="grid">
    <div class="col-12 card">
        <div class="grid">
            <div class="col-6">
                <h1 style="margin:0;">Printable Report</h1>
                <div class="small">Use your browser print feature to save as PDF.</div>
            </div>
            <div class="col-6 no-print" style="text-align:right;">
                <button class="btn btn-primary no-print" type="button" id="printBtn">Print</button>
                <a class="btn no-print" href="dashboard.php">Back</a>
            </div>
        </div>

        <div style="margin-top:14px;" class="grid">
            <div class="col-4 card">
                <div class="stat-label">Total Income</div>
                <div class="stat-value good">$<?php echo e(money($totalsAll['income'])); ?></div>
            </div>
            <div class="col-4 card">
                <div class="stat-label">Total Expenses</div>
                <div class="stat-value bad">$<?php echo e(money($totalsAll['expense'])); ?></div>
            </div>
            <div class="col-4 card">
                <div class="stat-label">Net Profit / Loss</div>
                <div class="stat-value <?php echo e($netClass); ?>">$<?php echo e(money($totalsAll['net'])); ?></div>
            </div>
        </div>

        <div style="margin-top:14px;">
            <h1 style="margin:0 0 10px; font-size:18px;">Transactions</h1>

            <?php if (!$transactions): ?>
                <div class="small">No transactions found.</div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td><?php echo e($t['date']); ?></td>
                                <td><?php echo e($t['type']); ?></td>
                                <td><?php echo e($t['category']); ?></td>
                                <td>$<?php echo e(money((float)$t['amount'])); ?></td>
                                <td><?php echo e((string)($t['description'] ?? '')); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.getElementById('printBtn')?.addEventListener('click', function () {
    window.print();
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
