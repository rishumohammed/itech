<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Contact Form Handler (v2)
//  File: submit.php  — Enhanced validation + spam protection
// ═══════════════════════════════════════════════════════════

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/config/config.php';

// ── Only accept POST ─────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: public/index.php');
    exit;
}

// ── Honeypot spam trap (hidden field "website" must be empty)
if (!empty($_POST['website'])) {
    // Silently discard spam
    header('Location: public/index.php#contact-section');
    exit;
}

// ── Collect & trim raw values ────────────────────────────────
$raw = [
    'name'    => trim($_POST['full_name'] ?? ''),
    'email'   => trim($_POST['email']     ?? ''),
    'phone'   => trim($_POST['phone']     ?? ''),
    'company' => trim($_POST['company']   ?? ''),
    'message' => trim($_POST['message']   ?? ''),
];

// ── Server-side validation ───────────────────────────────────
$errors = [];

if (empty($raw['name'])) {
    $errors[] = 'Full name is required.';
} elseif (strlen($raw['name']) < 2) {
    $errors[] = 'Full name must be at least 2 characters.';
} elseif (strlen($raw['name']) > 100) {
    $errors[] = 'Full name must not exceed 100 characters.';
}

if (empty($raw['email'])) {
    $errors[] = 'Email address is required.';
} elseif (!filter_var($raw['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
} elseif (strlen($raw['email']) > 255) {
    $errors[] = 'Email address is too long.';
}

if (!empty($raw['phone'])) {
    // Allow +, spaces, digits, (), -
    if (!preg_match('/^[\+\d\s\(\)\-]{6,20}$/', $raw['phone'])) {
        $errors[] = 'Phone number format is invalid.';
    }
}

if (empty($raw['message'])) {
    $errors[] = 'Message is required.';
} elseif (strlen($raw['message']) < 10) {
    $errors[] = 'Please write a longer message (at least 10 characters).';
} elseif (strlen($raw['message']) > 2000) {
    $errors[] = 'Message must not exceed 2000 characters.';
}

// ── Return errors to form ────────────────────────────────────
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'msg'  => implode(' &bull; ', $errors),
        'type' => 'error',
    ];
    // Preserve filled values so user doesn't re-type
    $_SESSION['form_data'] = $raw;
    header('Location: public/index.php#contact-section');
    exit;
}

// ── Sanitise for DB insertion ────────────────────────────────
$name    = db_escape($raw['name'],    $conn);
$email   = db_escape($raw['email'],   $conn);
$phone   = db_escape($raw['phone'],   $conn);
$company = db_escape($raw['company'], $conn);
$message = db_escape($raw['message'], $conn);

// ── Insert into enquiries table ──────────────────────────────
$sql = "INSERT INTO `enquiries`
            (`name`, `email`, `phone`, `company`, `message`)
        VALUES
            ('$name', '$email', '$phone', '$company', '$message')";

if (mysqli_query($conn, $sql)) {
    // Clear any old form data
    unset($_SESSION['form_data']);
    $_SESSION['flash'] = [
        'msg'  => 'Thank you, ' . htmlspecialchars($raw['name'], ENT_QUOTES) . '! Your enquiry has been submitted. Our team will respond within 24 hours.',
        'type' => 'success',
    ];
} else {
    $_SESSION['flash'] = [
        'msg'  => 'Sorry, something went wrong on our end. Please try again or email us directly at ' . ADMIN_EMAIL,
        'type' => 'error',
    ];
}

mysqli_close($conn);
header('Location: public/index.php#contact-section');
exit;
