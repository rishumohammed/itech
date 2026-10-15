<?php
// ═══════════════════════════════════════════════════════════
//  I Tech IT Solutions — Database Configuration (v2)
//  File: config/config.php
// ═══════════════════════════════════════════════════════════

// ── Block direct HTTP access to this file ───────────────────
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    http_response_code(403);
    die('403 Forbidden');
}

// ── Optional environment override file ──────────────────────
// NOTE: If config/config.env.php exists, it OVERRIDES the settings below.
// If you prefer editing this file directly, simply delete config/config.env.php.
if (file_exists(__DIR__ . '/config.env.php')) {
    require_once __DIR__ . '/config.env.php';
}

// ── Database Credentials ────────────────────────────────────
// Update these directly (or in config/config.env.php):
if (!defined('DB_HOST'))
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
if (!defined('DB_USERNAME'))
    define('DB_USERNAME', getenv('DB_USERNAME') ?: 'root');
if (!defined('DB_PASSWORD'))
    define('DB_PASSWORD', getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : '');
if (!defined('DB_NAME'))
    define('DB_NAME', getenv('DB_NAME') ?: 'itech_db');

// ── Site Metadata & Dynamic URL Detection ───────────────────
if (!defined('SITE_NAME'))
    define('SITE_NAME', 'I Tech IT Solutions');

if (!defined('SITE_URL')) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? 80) == 443)
        || ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'));
    $protocol = $isHttps ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    $scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $pos = strpos($scriptPath, '/public/');
    if ($pos === false) {
        $pos = strpos($scriptPath, '/admin/');
    }
    if ($pos !== false) {
        $basePath = substr($scriptPath, 0, $pos + 1);
    } else {
        $basePath = rtrim(dirname($scriptPath), '/') . '/';
    }
    if ($basePath === '//') {
        $basePath = '/';
    }
    define('SITE_URL', $protocol . $host . $basePath);
}

if (!defined('ADMIN_EMAIL'))
    define('ADMIN_EMAIL', 'sales@itech-sa.com');
if (!defined('BROCHURE_URL'))
    define('BROCHURE_URL', SITE_URL . 'assets/brochure.pdf');

// ── Social Links ─────────────────────────────────────────────
if (!defined('SOCIAL_LINKEDIN'))
    define('SOCIAL_LINKEDIN', 'https://www.linkedin.com/company/itechitsolutions');
if (!defined('SOCIAL_TWITTER'))
    define('SOCIAL_TWITTER', 'https://x.com/itechit');
if (!defined('SOCIAL_FACEBOOK'))
    define('SOCIAL_FACEBOOK', 'https://www.facebook.com/itechitsolutions');

// ── Site-wide constants ──────────────────────────────────────
if (!defined('SITE_TAGLINE'))
    define('SITE_TAGLINE', 'Enterprise IT Infrastructure & Security Solutions');

// ── Default SEO meta ─────────────────────────────────────────
if (!defined('DEFAULT_META_TITLE'))
    define('DEFAULT_META_TITLE', SITE_NAME . ' | Enterprise IT Infrastructure & Security');
if (!defined('DEFAULT_META_DESCRIPTION'))
    define('DEFAULT_META_DESCRIPTION', 'Reliable, scalable, and cost-effective IT infrastructure and security solutions for hotels, healthcare, retail, oil & gas, and more.');
if (!defined('DEFAULT_META_KEYWORDS'))
    define('DEFAULT_META_KEYWORDS', 'IT infrastructure, datacenter Jeddah, networking company Saudi Arabia, cybersecurity IT, IP surveillance, telephony, cloud services, enterprise IT Jeddah');

// ── Environment: automatically false on shared host, true on localhost ──
// Tip: You can append ?debug=1 to any URL to temporarily inspect errors if needed
$debugParam = isset($_GET['debug']) && in_array($_GET['debug'], ['1', 'true', 'itech', 'on'], true);
if (!defined('DEBUG_MODE')) {
    $isLocal = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']) || strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false;
    define('DEBUG_MODE', $isLocal || $debugParam);
}

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ── HTML output minification buffer ──────────────────────────
function minify_html_output(string $html): string
{
    // Remove HTML comments (preserve IE conditionals)
    $html = preg_replace('/<!--(?!\[if).*?-->/s', '', $html);
    // Collapse whitespace between tags
    $html = preg_replace('/>\s{2,}</s', '> <', $html);
    // Remove whitespace around block elements
    $html = preg_replace('/\s{2,}/s', ' ', $html);
    return trim($html);
}

if (!DEBUG_MODE) {
    ob_start('minify_html_output');
}

// ── Database connection with safe error handling ──────────────
// In PHP 8.1+, mysqli defaults to throwing exceptions which causes HTTP 500
// if connection fails. We disable automatic exception throwing here:
mysqli_report(MYSQLI_REPORT_OFF);

$conn = false;
$dbError = '';

try {
    $conn = @mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if (!$conn) {
        $dbError = mysqli_connect_error() ?: 'Unknown database connection error';
    } else {
        mysqli_set_charset($conn, 'utf8mb4');
    }
} catch (Throwable $e) {
    $conn = false;
    $dbError = $e->getMessage();
}

// If DB connection fails, show clear diagnostic guidance instead of a blank HTTP 500
if (!$conn) {
    http_response_code(503);
    $showDetails = DEBUG_MODE || $debugParam;
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Setup Required — <?= htmlspecialchars(SITE_NAME) ?></title>
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background: #0f172a;
                color: #f8fafc;
                margin: 0;
                padding: 40px 20px;
                line-height: 1.6;
            }

            .box {
                max-width: 680px;
                margin: 40px auto;
                background: #1e293b;
                border-radius: 16px;
                padding: 36px;
                border: 1px solid #334155;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            }

            h1 {
                color: #fca5a5;
                font-size: 24px;
                margin-top: 0;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .badge {
                background: #5d1d1f;
                color: #fff;
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 12px;
                font-weight: bold;
            }

            .code-box {
                background: #090d16;
                border: 1px solid #334155;
                border-radius: 8px;
                padding: 14px 18px;
                font-family: monospace;
                font-size: 13px;
                color: #efac3a;
                overflow-x: auto;
                margin: 15px 0;
            }

            .steps {
                padding-left: 20px;
                color: #cbd5e1;
            }

            .steps li {
                margin-bottom: 12px;
            }

            .help-link {
                color: #efac3a;
                text-decoration: none;
                font-weight: bold;
            }

            .help-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>
        <div class="box">
            <h1><span>&#9888;</span> Database Setup Required <span class="badge">HTTP 503</span></h1>
            <p>The application could not connect to the MySQL database on this server.</p>

            <?php if ($showDetails): ?>
                <div
                    style="background:#450a0a; border:1px solid #7f1d1d; border-radius:8px; padding:12px 16px; margin:16px 0; color:#fecaca; font-size:13px;">
                    <strong>MySQL Error:</strong> <?= htmlspecialchars($dbError) ?><br />
                    <strong>Host:</strong> <?= htmlspecialchars(DB_HOST) ?> |
                    <strong>User:</strong> <?= htmlspecialchars(DB_USERNAME) ?> |
                    <strong>Database:</strong> <?= htmlspecialchars(DB_NAME) ?>
                </div>
            <?php endif; ?>

            <h3>Quick Setup in cPanel:</h3>
            <ol class="steps">
                <li><strong>Create MySQL Database & User:</strong> In cPanel &rarr; <em>MySQL Databases</em>, create a
                    database (e.g. <code>itech_db</code>), a user, and grant <strong>ALL PRIVILEGES</strong>.</li>
                <li><strong>Import SQL Tables:</strong> In cPanel &rarr; <em>phpMyAdmin</em>, select your database and
                    import <code>itech_production_complete.sql</code>.</li>
                <li><strong>Configure Credentials:</strong> In cPanel File Manager, create/edit
                    <code>config/config.env.php</code>:
                    <div class="code-box">
                        &lt;?php<br />
                        define('DB_HOST', 'localhost');<br />
                        define('DB_USERNAME', 'your_cpanel_db_user');<br />
                        define('DB_PASSWORD', 'your_db_password');<br />
                        define('DB_NAME', 'your_cpanel_db_name');
                    </div>
                </li>
            </ol>
            <p style="font-size:12px;color:#94a3b8;margin-top:24px;">Tip: Add <code>?debug=1</code> to your URL for
                technical details.</p>
        </div>
    </body>

    </html>
    <?php
    exit;
}

// ── Load Site Settings safely into globals ───────────────────
$settings = [];
if ($conn) {
    try {
        $setRes = @mysqli_query($conn, "SELECT setting_key, setting_value FROM `settings`");
        if ($setRes) {
            while ($row = mysqli_fetch_assoc($setRes)) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
        }
    } catch (Throwable $e) {
        // settings table does not exist yet (itech_production_complete.sql not imported)
    }
}

/**
 * get_setting() - Safely retrieve a site setting with robust fallbacks
 */
function get_setting(string $key, $default = ''): string
{
    global $settings;
    if (isset($settings[$key]) && $settings[$key] !== '') {
        return $settings[$key];
    }
    // Fallback defaults if database tables are empty
    $defaults = [
        'site_name' => 'I Tech IT Solutions',
        'site_logo' => 'assets/images/logo.png',
        'company_phone' => '+966 12 000 0000',
        'company_email' => 'sales@itech-sa.com',
        'company_address' => 'Jeddah, Kingdom of Saudi Arabia',
        'hero_poster' => 'assets/images/hero_datacenter.png',
        'hero_video' => 'assets/videos/hero_datacenter.mp4',
        'brochure_url' => 'assets/brochure.pdf',
        'social_linkedin' => 'https://www.linkedin.com/company/itechitsolutions',
        'social_twitter' => 'https://x.com/itechit',
    ];
    return $defaults[$key] ?? $default;
}

// ── db_escape: sanitize user input ───────────────────────────
function db_escape(string $value, $conn): string
{
    return mysqli_real_escape_string($conn, trim($value));
}

// ── h(): safe HTML output shorthand ─────────────────────────
function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// ── Auth Helpers ─────────────────────────────────────────────
function is_admin(): bool
{
    return ($_SESSION['admin_role'] ?? '') === 'admin';
}

function check_admin_only(): void
{
    if (!is_admin()) {
        redirect('index.php', 'Access denied. Administrator privileges required.', 'danger');
    }
}

// ── slug_to_title(): convert slug to readable title ─────────
function slug_to_title(string $slug): string
{
    return ucwords(str_replace('-', ' ', $slug));
}

// ── title_to_slug(): convert title to URL slug ──────────────
function title_to_slug(string $title): string
{
    $title = strtolower(trim($title));
    $title = preg_replace('/[^a-z0-9\s-]/', '', $title);
    $title = preg_replace('/[\s-]+/', '-', $title);
    return trim($title, '-');
}

// ── redirect with flash ──────────────────────────────────────
function redirect(string $url, string $msg = '', string $type = 'success'): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if ($msg) {
        $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
    }
    header('Location: ' . $url);
    exit;
}

// ── Flash helper: consume and return flash data ──────────────
function get_flash(): array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flash;
}
