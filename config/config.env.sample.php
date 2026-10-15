<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Production Environment Config
//  Rename this file to "config.env.php" on your shared server.
// ═══════════════════════════════════════════════════════════

// Database Credentials (from your hosting cPanel -> MySQL Databases)
define('DB_HOST',     'localhost');
define('DB_USERNAME', 'your_cpanel_db_user');
define('DB_PASSWORD', 'your_cpanel_db_password');
define('DB_NAME',     'your_cpanel_db_name');

// Optional: Force a specific live site URL (with trailing slash)
// Leave commented to let the system auto-detect your domain and SSL automatically:
// define('SITE_URL', 'https://yourdomain.com/');

// Production security: keep errors hidden from visitors
define('DEBUG_MODE', false);
