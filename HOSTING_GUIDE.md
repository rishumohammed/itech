# Shared Server Hosting Guide — I Tech IT Solutions

This guide provides step-by-step instructions to deploy the **I Tech IT Solutions** application to any standard Linux shared hosting server (cPanel, DirectAdmin, Plesk, Hostinger, Namecheap, GoDaddy, Bluehost, etc.).

---

## 1. Pre-Deployment Summary

| Component | Shared Hosting Requirement |
|---|---|
| **Web Server** | Apache with `mod_rewrite`, `mod_headers`, and `mod_deflate` |
| **PHP Version** | PHP 7.4, 8.0, 8.1, 8.2, or 8.3 |
| **PHP Extensions**| `mysqli`, `session`, `json`, `mbstring` (standard on all hosts) |
| **Database** | MySQL 5.7+ or MariaDB 10.3+ |
| **Document Root**| `public_html/` (for primary domain) or subfolder (for addon/subdomain) |

---

## 2. Step 1: Package Your Project Files

Create a `.zip` archive of your project directory to upload via cPanel File Manager.

### Files & Folders to INCLUDE:
* `admin/`
* `assets/`
* `config/`
* `includes/`
* `public/`
* `uploads/`
* `index.php`
* `submit.php`
* `.htaccess`
* `itech_production_complete.sql`

### Folders to EXCLUDE (Do NOT upload):
* `node_modules/` *(Unnecessary devDependencies; saves ~30MB and thousands of inodes)*
* `.git/` / `.agents/`

---

## 3. Step 2: Create MySQL Database & User in cPanel

1. Log into your hosting **cPanel**.
2. Navigate to **Databases** → **MySQL® Databases** (or **MySQL Database Wizard**).
3. **Create New Database**:
   * Example: `cpaneluser_itechdb`
4. **Create New User**:
   * Example: `cpaneluser_dbadmin`
   * Generate a strong password and save it securely.
5. **Add User to Database**:
   * Select the user and database you just created.
   * Click **Add**.
   * Check **ALL PRIVILEGES** and click **Make Changes**.

---

## 4. Step 3: Import the Database

1. In cPanel, navigate to **Databases** → **phpMyAdmin**.
2. In the left sidebar, click on your newly created database (e.g., `cpaneluser_itechdb`).
3. Click the **Import** tab at the top.
4. Under **File to import**, click **Choose File** and select `itech_production_complete.sql`.
5. Click **Import** (or **Go**) at the bottom.
6. You will see a green success message confirming all 6 tables were created:
   * `solutions` (9 industry solutions)
   * `services` (13 core IT services)
   * `products` (10 technology brands)
   * `enquiries` (Customer inquiry inbox)
   * `users` (Admin staff accounts)
   * `settings` (Site-wide contact information & appearance)

---

## 5. Step 4: Upload and Extract Files

1. In cPanel, go to **Files** → **File Manager**.
2. Navigate to your target directory:
   * **Primary Domain**: `/public_html/`
   * **Subdomain or Addon Domain**: `/public_html/your-folder/`
3. Click **Upload** and upload your `.zip` archive.
4. Once uploaded, right-click the `.zip` file in File Manager and select **Extract**.
5. Ensure hidden files (`.htaccess`) are visible by clicking **Settings** (top right) → check **Show Hidden Files (dotfiles)** → **Save**.

---

## 6. Step 5: Configure Production Credentials

You can configure your database credentials in one of two easy ways:

### Option A (Recommended): Create `config/config.env.php`
In cPanel File Manager, look inside `config/`. Rename `config.env.sample.php` to `config.env.php` (or create a new file `config/config.env.php`) and add:

```php
<?php
define('DB_HOST',     'localhost');
define('DB_USERNAME', 'cpaneluser_dbadmin');
define('DB_PASSWORD', 'YourStrongPasswordHere');
define('DB_NAME',     'cpaneluser_itechdb');

// The application will automatically detect your domain and HTTPS!
// If you want to force an exact URL, uncomment below:
// define('SITE_URL', 'https://yourdomain.com/');

define('DEBUG_MODE', false);
```

### Option B: Edit `config/config.php` directly
Update lines 18–21 in `config/config.php` with your database credentials.

---

## 7. Step 6: Verify Permissions & RewriteBase

1. **Uploads Folder Permissions**:
   * In File Manager, right-click `uploads/` → **Change Permissions**.
   * Ensure it is set to `755` (or `775` depending on host).
2. **RewriteBase in `.htaccess`**:
   * If your site is hosted in the **root** of your domain (`public_html/`), the default `RewriteBase /` is already configured.
   * If your site is installed in a **subfolder** (e.g. `https://example.com/demo/`), open `.htaccess` and change line 14 to:
     ```apache
     RewriteBase /demo/
     ```

---

## 8. Step 7: Verify Everything Works

1. **Visit Homepage**:
   * Navigate to `https://yourdomain.com/`.
   * Confirm the hero video/poster, dynamic mega-menus, and stats load properly.
2. **Test Clean URLs**:
   * Visit `https://yourdomain.com/solutions/hotels-resorts`
   * Visit `https://yourdomain.com/services/datacenter-infrastructure`
   * Visit `https://yourdomain.com/about`
3. **Test Contact Form**:
   * Submit an inquiry via the footer contact section.
   * Verify the success flash message appears.
4. **Log Into Admin Panel**:
   * Navigate to `https://yourdomain.com/admin/login.php`
   * **Default Username**: `admin`
   * **Default Password**: `admin123`
   * *(The system automatically encrypts and upgrades the password to a secure bcrypt hash upon first login)*.
   * Navigate to **Admin Users** and change the default password to your own secure password.

---

## 9. Troubleshooting: Fixing "HTTP ERROR 500"

If you see **HTTP ERROR 500** right after uploading your files to your domain (`itechit.sa`), follow these quick steps:

### 1. Run the Diagnostic Tool
Visit in your browser:
```
https://yourdomain.com/diagnostics.php
```
This tool checks PHP, MySQL connection, and database tables, and tells you the exact problem and fix.

### 2. Configure Database Credentials (Most Common Cause)
On shared hosting, the database credentials are not `root` with a blank password. You must configure your cPanel database:
1. In cPanel &rarr; **File Manager**, open `config/`.
2. Create or edit `config/config.env.php`:
   ```php
   <?php
   define('DB_HOST',     'localhost');
   define('DB_USERNAME', 'cpaneluser_dbuser');
   define('DB_PASSWORD', 'YourDatabasePassword');
   define('DB_NAME',     'cpaneluser_itechdb');
   ```
   *(Replace with the actual database name, username, and password you created in cPanel &rarr; MySQL Databases)*.

### 3. Import SQL Database in phpMyAdmin
1. In cPanel &rarr; **phpMyAdmin**, click your database in the left sidebar.
2. Click **Import** tab &rarr; choose `itech_production_complete.sql` &rarr; click **Go / Import**.

### 4. Enable Temporary Debug Mode
If you ever want to see the exact PHP error on any page, append `?debug=1` to the URL:
```
https://yourdomain.com/?debug=1
```

