<?php
require_once __DIR__ . '/finance.php';

require_login();

$user = current_user($pdo);
if (!$user) {
    header('Location: login.php');
    exit;
}

$period = $_GET['period'] ?? 'all';
if (!in_array($period, ['all', 'daily', 'monthly', 'yearly'], true)) {
    $period = 'all';
}

[$fromDate, $toDate] = get_period_range($period);

$totalsAll = get_totals($pdo, (int)$user['id']);
$totalsPeriod = get_totals($pdo, (int)$user['id'], $fromDate, $toDate);

$transactions = get_transactions($pdo, (int)$user['id'], 200);

function net_class(float $net): string
{
    return $net >= 0 ? 'good' : 'bad';
}

require_once __DIR__ . '/header.php';
?>

<div class="grid">
    <div class="col-12">
        <h1>Dashboard</h1>
        <div class="small">Welcome, <?php echo e($user['username']); ?>. Track your finances and review summaries.</div>
    </div>

    <div class="col-4 card">
        <div class="stat-label">Total Income (All Time)</div>
        <div class="stat-value good">$<?php echo e(money($totalsAll['income'])); ?></div>
    </div>

    <div class="col-4 card">
        <div class="stat-label">Total Expenses (All Time)</div>
        <div class="stat-value bad">$<?php echo e(money($totalsAll['expense'])); ?></div>
    </div>

    <div class="col-4 card">
        <div class="stat-label">Net Profit / Loss (All Time)</div>
        <div class="stat-value <?php echo e(net_class($totalsAll['net'])); ?>">
            $<?php echo e(money($totalsAll['net'])); ?>
        </div>
    </div>

    <div class="col-12 card">
        <div class="grid">
            <div class="col-6">
                <h1 style="margin:0; font-size:18px;">Reports & Summaries</h1>
                <div class="small">Choose a time filter to see totals for that period.</div>
            </div>
            <div class="col-6" style="text-align:right;">
                <a class="btn btn-primary" href="add_transaction.php">+ Add Transaction</a>
                <a class="btn" href="export_csv.php">Export CSV</a>
                <a class="btn" href="report.php">Printable Report</a>
            </div>
        </div>

        <div class="filters" style="margin-top:12px;">
            <a class="<?php echo $period === 'all' ? 'active' : ''; ?>" href="dashboard.php?period=all">All</a>
            <a class="<?php echo $period === 'daily' ? 'active' : ''; ?>" href="dashboard.php?period=daily">Daily</a>
            <a class="<?php echo $period === 'monthly' ? 'active' : ''; ?>" href="dashboard.php?period=monthly">Monthly</a>
            <a class="<?php echo $period === 'yearly' ? 'active' : ''; ?>" href="dashboard.php?period=yearly">Yearly</a>
        </div>

        <div class="grid" style="margin-top:14px;">
            <div class="col-4 card">
                <div class="stat-label">Income (<?php echo e(ucfirst($period)); ?>)</div>
                <div class="stat-value good">$<?php echo e(money($totalsPeriod['income'])); ?></div>
            </div>
            <div class="col-4 card">
                <div class="stat-label">Expenses (<?php echo e(ucfirst($period)); ?>)</div>
                <div class="stat-value bad">$<?php echo e(money($totalsPeriod['expense'])); ?></div>
            </div>
            <div class="col-4 card">
                <div class="stat-label">Net (<?php echo e(ucfirst($period)); ?>)</div>
                <div class="stat-value <?php echo e(net_class($totalsPeriod['net'])); ?>">
                    $<?php echo e(money($totalsPeriod['net'])); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 card">
        <h1 style="margin:0 0 10px; font-size:18px;">Transactions (Newest First)</h1>

        <?php if (!$transactions): ?>
            <div class="small">No transactions yet. Click “Add Transaction” to get started.</div>
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
                            <td>
                                <?php if ($t['type'] === 'income'): ?>
                                    <span class="badge badge-income">Income</span>
                                <?php else: ?>
                                    <span class="badge badge-expense">Expense</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($t['category']); ?></td>
                            <td>
                                <?php if ($t['type'] === 'income'): ?>
                                    <span class="good">+$<?php echo e(money((float)$t['amount'])); ?></span>
                                <?php else: ?>
                                    <span class="bad">-$<?php echo e(money((float)$t['amount'])); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e((string)($t['description'] ?? '')); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
