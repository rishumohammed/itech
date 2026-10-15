<?php
/**
 * I Tech IT Solutions — Production Server Diagnostic Tool
 * File: diagnostics.php
 * 
 * Access via: https://itechit.sa/diagnostics.php
 * Use this to verify hosting environment, MySQL connection, and database tables.
 */

// Enable full error display for this diagnostic script
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load configuration
$configPath = __DIR__ . '/config/config.php';
$envPath    = __DIR__ . '/config/config.env.php';

$envExists = file_exists($envPath);

// Pre-define checks
$checks = [];

// 1. PHP Version
$phpVersion = PHP_VERSION;
$phpOk = version_compare($phpVersion, '7.4.0', '>=');
$checks[] = [
    'title'   => 'PHP Version',
    'status'  => $phpOk ? 'pass' : 'fail',
    'details' => "Running PHP {$phpVersion} " . ($phpOk ? '(Supported)' : '(Requires PHP 7.4 or higher)'),
    'fix'     => 'In your hosting cPanel, go to "Select PHP Version" and choose PHP 8.1 or 8.2.'
];

// 2. MySQLi Extension
$mysqliOk = extension_loaded('mysqli');
$checks[] = [
    'title'   => 'PHP MySQLi Extension',
    'status'  => $mysqliOk ? 'pass' : 'fail',
    'details' => $mysqliOk ? 'mysqli extension is loaded.' : 'mysqli extension is missing.',
    'fix'     => 'In cPanel &rarr; "Select PHP Version" &rarr; Extensions, check the box for "mysqli".'
];

// 3. Config Env File
$checks[] = [
    'title'   => 'config/config.env.php',
    'status'  => $envExists ? 'pass' : 'warning',
    'details' => $envExists 
        ? 'Found config.env.php (Production credentials override active).' 
        : 'config.env.php not found. Falling back to default credentials in config/config.php.',
    'fix'     => 'Create config/config.env.php in File Manager with your cPanel database username, password, and database name.'
];

// 4. Load config file & determine credential source
$dbHost = $dbUser = $dbPass = $dbName = '';
$source = 'config/config.php';

if ($envExists) {
    @include_once $envPath;
    $source = 'config/config.env.php (Overrides config.php)';
}

$dbHost = defined('DB_HOST')     ? DB_HOST     : 'localhost';
$dbUser = defined('DB_USERNAME') ? DB_USERNAME : 'root';
$dbPass = defined('DB_PASSWORD') ? DB_PASSWORD : '';
$dbName = defined('DB_NAME')     ? DB_NAME     : 'itech_db';

$passLength = strlen($dbPass);
$maskedPass = $passLength > 4 
    ? substr($dbPass, 0, 2) . str_repeat('•', max(1, $passLength - 4)) . substr($dbPass, -2)
    : str_repeat('•', $passLength);

$checks[] = [
    'title'   => 'Active Database Credentials in PHP',
    'status'  => 'pass',
    'details' => "Source: [{$source}]\n" .
                 "Host: [{$dbHost}] | User: [{$dbUser}] | DB: [{$dbName}]\n" .
                 "Password in use: [{$maskedPass}] ({$passLength} characters).",
    'fix'     => 'If this is NOT your new password, update it in config/config.env.php (or delete config.env.php to use config.php).'
];

// 5. Test MySQL Server Connection
mysqli_report(MYSQLI_REPORT_OFF);
$serverConn = false;
$serverError = '';

if ($mysqliOk) {
    try {
        $serverConn = @mysqli_connect($dbHost, $dbUser, $dbPass);
        if (!$serverConn) {
            $serverError = mysqli_connect_error();
        }
    } catch (Throwable $e) {
        $serverError = $e->getMessage();
    }
}

$checks[] = [
    'title'   => 'MySQL Server Connection',
    'status'  => $serverConn ? 'pass' : 'fail',
    'details' => $serverConn 
        ? "Connected successfully to MySQL server at [{$dbHost}] as user [{$dbUser}]."
        : "Failed to connect to MySQL server. Error: " . htmlspecialchars($serverError),
    'fix'     => 'Verify your database username and password in cPanel &rarr; MySQL Databases. Note: Shared hosts require prefixes like "cpaneluser_dbuser".'
];

// 6. Test Database Selection
$dbSelected = false;
$dbSelectError = '';
$tablesFound = [];
$requiredTables = ['solutions', 'services', 'products', 'enquiries', 'users', 'settings'];
$missingTables = $requiredTables;

if ($serverConn) {
    if (@mysqli_select_db($serverConn, $dbName)) {
        $dbSelected = true;
        // Check tables
        $tRes = @mysqli_query($serverConn, "SHOW TABLES");
        if ($tRes) {
            while ($row = mysqli_fetch_array($tRes)) {
                $tablesFound[] = $row[0];
            }
        }
        $missingTables = array_diff($requiredTables, $tablesFound);
    } else {
        $dbSelectError = mysqli_error($serverConn);
    }
}

$checks[] = [
    'title'   => "MySQL Database [{$dbName}]",
    'status'  => $dbSelected ? 'pass' : 'fail',
    'details' => $dbSelected
        ? "Database [{$dbName}] exists and is accessible."
        : "Cannot select database [{$dbName}]. Error: " . htmlspecialchars($dbSelectError ?: 'Database not found or user lacks privileges.'),
    'fix'     => "In cPanel &rarr; MySQL Databases, ensure database [{$dbName}] is created and your user is added with 'ALL PRIVILEGES'."
];

// 7. Test Tables & Data
$tablesOk = $dbSelected && empty($missingTables);
$tableCounts = [];
if ($dbSelected) {
    foreach ($requiredTables as $tbl) {
        if (in_array($tbl, $tablesFound, true)) {
            $cRes = @mysqli_query($serverConn, "SELECT COUNT(*) FROM `{$tbl}`");
            $cRow = $cRes ? mysqli_fetch_array($cRes) : [0];
            $tableCounts[$tbl] = (int)$cRow[0];
        }
    }
}

$checks[] = [
    'title'   => 'Database Tables & Sample Data',
    'status'  => $tablesOk ? 'pass' : 'fail',
    'details' => $tablesOk 
        ? "All 6 required tables are present: " . implode(', ', array_map(function($k) use ($tableCounts) {
            return "{$k} ({$tableCounts[$k]} rows)";
        }, array_keys($tableCounts)))
        : ($dbSelected 
            ? "Missing tables: " . implode(', ', $missingTables) . ". (Found: " . (empty($tablesFound) ? 'None' : implode(', ', $tablesFound)) . ")"
            : "Cannot check tables because database is not connected."),
    'fix'     => 'In cPanel &rarr; phpMyAdmin, click your database and click "Import" &rarr; select "itech_production_complete.sql" and click Import.'
];

// 8. Uploads folder writable
$uploadsDir = __DIR__ . '/uploads';
$uploadsWritable = is_dir($uploadsDir) && is_writable($uploadsDir);
$checks[] = [
    'title'   => 'Uploads Directory Permissions',
    'status'  => $uploadsWritable ? 'pass' : 'warning',
    'details' => $uploadsWritable ? 'uploads/ folder is writable (755/775).' : 'uploads/ folder is not writable.',
    'fix'     => 'In cPanel File Manager, right-click uploads/ &rarr; Change Permissions &rarr; set to 755.'
];

if ($serverConn) {
    mysqli_close($serverConn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>I Tech IT Solutions — Hosting Diagnostic Health Check</title>
  <style>
    * { box-sizing: border-box; }
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 40px 20px; line-height: 1.5; }
    .container { max-width: 820px; margin: 0 auto; }
    .card { background: #1e293b; border-radius: 20px; border: 1px solid #334155; padding: 36px; box-shadow: 0 20px 40px -15px rgba(0,0,0,0.5); margin-bottom: 24px; }
    .header { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #334155; padding-bottom: 20px; margin-bottom: 28px; }
    .logo-text { font-size: 22px; font-weight: 900; color: #fff; letter-spacing: -0.5px; }
    .logo-text span { color: #efac3a; }
    .badge { font-size: 11px; font-weight: 800; padding: 5px 12px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 1px; }
    .badge-pass { background: #064e3b; color: #6ee7b7; border: 1px solid #059669; }
    .badge-fail { background: #450a0a; color: #fca5a5; border: 1px solid #dc2626; }
    .badge-warning { background: #713f12; color: #fde047; border: 1px solid #ca8a04; }
    
    .item { background: #0b1120; border: 1px solid #1e293b; border-radius: 14px; padding: 20px; margin-bottom: 16px; }
    .item-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .item-title { font-weight: 700; font-size: 16px; color: #fff; }
    .item-details { font-size: 13px; color: #94a3b8; font-family: monospace; word-break: break-all; }
    .item-fix { margin-top: 10px; padding: 10px 14px; background: #172554; border-radius: 8px; font-size: 12px; color: #93c5fd; border-left: 4px solid #3b82f6; }
    
    .btn { display: inline-block; background: #5d1d1f; color: #fff; text-decoration: none; font-weight: 800; font-size: 13px; padding: 12px 24px; border-radius: 10px; margin-top: 10px; }
    .btn:hover { background: #4a1517; }
  </style>
</head>
<body>
<div class="container">
  <div class="card">
    <div class="header">
      <div class="logo-text">I TECH <span>IT SOLUTIONS</span> — Hosting Diagnostics</div>
      <a href="index.php" class="btn">Go to Homepage &rarr;</a>
    </div>

    <p style="color:#94a3b8; font-size:14px; margin-bottom:24px;">
      This diagnostic report inspects your live server configuration on <strong><?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'itechit.sa') ?></strong> to pinpoint why an HTTP 500 or database error occurred.
    </p>

    <?php foreach ($checks as $c): ?>
      <div class="item">
        <div class="item-header">
          <span class="item-title"><?= htmlspecialchars($c['title']) ?></span>
          <span class="badge badge-<?= $c['status'] ?>"><?= strtoupper($c['status']) ?></span>
        </div>
        <div class="item-details"><?= htmlspecialchars($c['details']) ?></div>
        <?php if ($c['status'] !== 'pass'): ?>
          <div class="item-fix">
            <strong>How to Fix:</strong> <?= $c['fix'] ?>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <div style="margin-top:30px; padding-top:20px; border-top:1px solid #334155; font-size:12px; color:#64748b;">
      Security Note: Once your site is working, you can safely delete this <code>diagnostics.php</code> file.
    </div>
  </div>
</div>
</body>
</html>
