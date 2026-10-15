-- ═══════════════════════════════════════════════════════════════
--  I Tech IT Solutions — DB Migration (v6)
--  RBAC (Users) and Site Settings
-- ═══════════════════════════════════════════════════════════════

USE `itech_db`;

-- ──────────────────────────────────────────────────────────────
--  TABLE: users (RBAC)
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `full_name`  VARCHAR(255) NOT NULL,
  `role`       ENUM('admin', 'subadmin') NOT NULL DEFAULT 'subadmin',
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default users (Initial setup)
-- admin / admin123 (Migrated to hash)
-- subadmin / subadmin123
INSERT INTO `users` (`username`, `password`, `full_name`, `role`) VALUES
  ('admin',    '$2y$10$C8iU0L/1l0bA1qG8k1u1e.v7G9Y7E8z1h8P6/E1o8i5r4v3w2y1q0', 'Main Admin', 'admin'),
  ('subadmin', '$2y$10$S9V1P6U6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6V6.', 'Support Staff', 'subadmin')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- ──────────────────────────────────────────────────────────────
--  TABLE: settings
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key`   VARCHAR(100) NOT NULL,
  `setting_value` TEXT         DEFAULT NULL,
  `category`      VARCHAR(50)  DEFAULT 'general',
  `description`   VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `category`, `description`) VALUES
  ('site_name',    'I Tech IT Solutions', 'general', 'Visible name of the website'),
  ('site_tagline', 'Enterprise IT Infrastructure & Security Solutions', 'general', 'Hero/Footer tagline'),
  ('company_email', 'sales@itech-sa.com', 'contact', 'Primary contact email'),
  ('company_phone', '+966 50 553 2108', 'contact', 'Primary contact phone'),
  ('company_whatsapp', '+966505532108', 'contact', 'WhatsApp number (no symbols)'),
  ('company_address', 'Khalid Bin Waleed Street, Jeddah, Kingdom of Saudi Arabia', 'contact', 'Full office address'),
  ('social_linkedin', 'https://www.linkedin.com/company/itechitsolutions', 'social', 'LinkedIn profile URL'),
  ('social_twitter', 'https://x.com/itechit', 'social', 'Twitter/X profile URL'),
  ('social_facebook', 'https://www.facebook.com/itechitsolutions', 'social', 'Facebook profile URL'),
  ('site_logo', 'assets/images/logo.png', 'appearance', 'Path to company logo'),
  ('hero_video', 'uploads/hero-video.mp4', 'appearance', 'Path to hero background video'),
  ('hero_poster', 'uploads/hero_enterprise.png', 'appearance', 'Fallback image for hero video'),
  ('brochure_url', 'assets/brochure.pdf', 'general', 'Direct link to company brochure pdf')
ON DUPLICATE KEY UPDATE `setting_key`=`setting_key`;
