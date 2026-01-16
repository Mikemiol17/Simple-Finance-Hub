<?php
require_once __DIR__ . '/finance.php';

require_login();

$user = current_user($pdo);
if (!$user) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT type, category, amount, description, date FROM transactions WHERE user_id = ? ORDER BY date DESC, id DESC');
$stmt->execute([(int)$user['id']]);
$rows = $stmt->fetchAll();

$filename = 'simplefinance_transactions_' . date('Y-m-d_H-i-s') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');

// Column headers
fputcsv($output, ['Type', 'Category', 'Amount', 'Description', 'Date']);

foreach ($rows as $r) {
    fputcsv($output, [
        $r['type'],
        $r['category'],
        $r['amount'],
        $r['description'],
        $r['date'],
    ]);
}

fclose($output);
exit;
