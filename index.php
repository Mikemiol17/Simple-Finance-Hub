<?php
require_once __DIR__ . '/init.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

header('Location: login.php');
exit;
