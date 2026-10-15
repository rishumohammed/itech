<?php
// ── Session authentication guard ─────────────────────────────
// Include at top of every protected admin page (BEFORE any output).

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// ── Convenience: current admin data ──────────────────────────
$adminUser = $_SESSION['admin_user'] ?? 'Admin';
$adminRole = $_SESSION['admin_role'] ?? 'subadmin';
$adminFullName = $_SESSION['admin_full_name'] ?? 'Staff';
