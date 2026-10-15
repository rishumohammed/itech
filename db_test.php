<?php
/**
 * Simple 1-Second Database Connection Checker
 * File: db_test.php
 * URL: https://itechit.sa/db_test.php
 */

require_once __DIR__ . '/config/config.php';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Database Connection Status</title>
  <style>
    body { font-family: sans-serif; background: #0f172a; color: #fff; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
    .card { background: #1e293b; padding: 40px; border-radius: 16px; border: 1px solid #334155; text-align: center; max-width: 500px; }
    .success { color: #4ade80; font-size: 24px; font-weight: bold; }
    .fail { color: #f87171; font-size: 24px; font-weight: bold; }
    .info { color: #94a3b8; font-family: monospace; margin-top: 15px; font-size: 14px; }
  </style>
</head>
<body>
  <div class="card">
    <?php if ($conn): ?>
      <div class="success">&#10004; Database is CONNECTED!</div>
      <p class="info">Connected to: <strong><?= htmlspecialchars(DB_NAME) ?></strong> as <strong><?= htmlspecialchars(DB_USERNAME) ?></strong></p>
      <?php
        $q = @mysqli_query($conn, "SELECT COUNT(*) FROM `services`");
        $servicesCount = $q ? mysqli_fetch_array($q)[0] : 0;
      ?>
      <p style="color:#cbd5e1; font-size:14px;">Found <strong><?= (int)$servicesCount ?></strong> services in database.</p>
    <?php else: ?>
      <div class="fail">&#10008; Database is NOT Connected</div>
      <p class="info">Host: <?= htmlspecialchars(DB_HOST) ?> | User: <?= htmlspecialchars(DB_USERNAME) ?></p>
      <p style="color:#cbd5e1; font-size:13px;">Please check password in config/config.env.php</p>
    <?php endif; ?>
  </div>
</body>
</html>
