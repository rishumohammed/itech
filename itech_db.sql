-- ═══════════════════════════════════════════════════════════════
--  I Tech IT Solutions — MySQL Database Schema
--  Import via phpMyAdmin or: mysql -u root -p < itech_db.sql
-- ═══════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS `itech_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `itech_db`;

-- ──────────────────────────────────────────────────────────────
--  TABLE: solutions
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `solutions` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) NOT NULL,
  `description` TEXT         NOT NULL,
  `image`       VARCHAR(512) DEFAULT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `solutions` (`title`, `description`, `image`) VALUES
  ('Hotels & Resorts',            'Smart hospitality networks, PMS integration & guest Wi-Fi.',          'assets/images/solutions/hotels.jpg'),
  ('Healthcare',                  'Secure clinical networks, HIS/EMR connectivity & compliance.',        'assets/images/solutions/healthcare.jpg'),
  ('Retail Chains',               'POS networking, inventory systems & multi-branch connectivity.',      'assets/images/solutions/retail.jpg'),
  ('Manufacturing',               'Industrial IoT, OT/IT convergence & factory-floor networks.',         'assets/images/solutions/manufacturing.jpg'),
  ('Warehousing & Logistics',     'WMS integration, barcode & RFID networks for distribution centres.',  'assets/images/solutions/logistics.jpg'),
  ('Oil & Gas',                   'Rugged network infrastructure for upstream & downstream operations.', 'assets/images/solutions/oilgas.jpg'),
  ('Parking & Vehicle Mgmt',      'ANPR, barrier control & IoT-enabled smart parking platforms.',       'assets/images/solutions/parking.jpg'),
  ('Construction & Real Estate',  'Temporary & permanent IT infrastructure for project sites.',          'assets/images/solutions/construction.jpg');

-- ──────────────────────────────────────────────────────────────
--  TABLE: services
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `services` (
  `id`          INT          NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255) NOT NULL,
  `description` TEXT         NOT NULL,
  `icon`        VARCHAR(255) DEFAULT NULL,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `services` (`title`, `description`, `icon`) VALUES
  ('Datacenter Infrastructure', 'Design & build high-availability server rooms and DCs.',                        'icon-datacenter'),
  ('Cabling (Copper & Fiber)',   'Structured Cat6A/OM4 fiber cabling with full certification.',                  'icon-cabling'),
  ('Networking (Switching)',     'Enterprise LAN/WAN, SD-WAN and core routing deployments.',                     'icon-networking'),
  ('Security (Firewall / UTM)',  'Firewall, UTM, IDS/IPS and zero-trust security architecture.',                 'icon-security'),
  ('IP Surveillance',           'IP CCTV, NVR/VMS platforms and analytics-based camera systems.',               'icon-camera'),
  ('IP Telephony',              'VoIP, IP-PBX and unified communications for enterprises.',                     'icon-phone'),
  ('Wireless Networks (WiFi)',   'Wi-Fi 6/6E site surveys, mesh deployments and guest portals.',                 'icon-wifi'),
  ('Cloud & Internet Services',  'AWS/Azure/GCP migrations and ISP/SD-WAN connectivity.',                       'icon-cloud');

-- ──────────────────────────────────────────────────────────────
--  TABLE: products
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `products` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255) NOT NULL,
  `logo`       VARCHAR(512) DEFAULT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`name`, `logo`) VALUES
  ('Cisco',      'assets/images/brands/cisco.png'),
  ('Huawei',     'assets/images/brands/huawei.png'),
  ('Fortinet',   'assets/images/brands/fortinet.png'),
  ('Hikvision',  'assets/images/brands/hikvision.png'),
  ('Dahua',      'assets/images/brands/dahua.png'),
  ('Axis',       'assets/images/brands/axis.png'),
  ('Bosch',      'assets/images/brands/bosch.png'),
  ('Honeywell',  'assets/images/brands/honeywell.png'),
  ('Mitel',      'assets/images/brands/mitel.png'),
  ('Yeastar',    'assets/images/brands/yeastar.png');

-- ──────────────────────────────────────────────────────────────
--  TABLE: enquiries
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id`         INT          NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255) NOT NULL,
  `email`      VARCHAR(255) NOT NULL,
  `phone`      VARCHAR(50)  DEFAULT NULL,
  `company`    VARCHAR(255) DEFAULT NULL,
  `message`    TEXT         NOT NULL,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
