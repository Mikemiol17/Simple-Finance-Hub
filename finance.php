<?php
// finance.php
// Simple finance-related helper functions (totals, filters).

require_once __DIR__ . '/auth.php';

function money(float $value): string
{
    return number_format($value, 2);
}

function get_totals(PDO $pdo, int $userId, ?string $fromDate = null, ?string $toDate = null): array
{
    $sql = "
        SELECT
            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS total_income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS total_expense
        FROM transactions
        WHERE user_id = :user_id
    ";

    $params = [':user_id' => $userId];

    if ($fromDate !== null) {
        $sql .= " AND date >= :from_date";
        $params[':from_date'] = $fromDate;
    }

    if ($toDate !== null) {
        $sql .= " AND date <= :to_date";
        $params[':to_date'] = $toDate;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $row = $stmt->fetch();

    $income = (float)($row['total_income'] ?? 0);
    $expense = (float)($row['total_expense'] ?? 0);

    return [
        'income' => $income,
        'expense' => $expense,
        'net' => $income - $expense,
    ];
}

function get_period_range(string $period): array
{
    // Returns [fromDate, toDate] as YYYY-MM-DD strings.
    $today = new DateTime('today');

    if ($period === 'daily') {
        $from = clone $today;
        $to = clone $today;
        return [$from->format('Y-m-d'), $to->format('Y-m-d')];
    }

    if ($period === 'monthly') {
        $from = new DateTime('first day of this month');
        $to = new DateTime('last day of this month');
        return [$from->format('Y-m-d'), $to->format('Y-m-d')];
    }

    if ($period === 'yearly') {
        $from = new DateTime('first day of January ' . date('Y'));
        $to = new DateTime('last day of December ' . date('Y'));
        return [$from->format('Y-m-d'), $to->format('Y-m-d')];
    }

    // Default: all-time
    return [null, null];
}

function get_transactions(PDO $pdo, int $userId, int $limit = 100): array
{
    $stmt = $pdo->prepare('SELECT id, type, category, amount, description, date FROM transactions WHERE user_id = ? ORDER BY date DESC, id DESC LIMIT ' . (int)$limit);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}
