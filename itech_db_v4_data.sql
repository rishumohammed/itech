-- ═══════════════════════════════════════════════════════════
--  I Tech IT Solutions — Database Expansion (v4)
--  Adds: Expanded Solutions and Services for Mega Menu
-- ═══════════════════════════════════════════════════════════

USE `itech_db`;

-- ── Expand Solutions ───────────────────────────────────────
-- Clean up existing (only 2 basic ones currently)
-- Or just insert missing ones
INSERT INTO `solutions` (`title`, `slug`, `description`) VALUES
('Hotels & Resorts', 'hotels-resorts', 'Comprehensive guest WiFi, IPTV, and security solutions for hospitality.'),
('Healthcare', 'healthcare', 'Reliable bedside entertainment, telemedicine networks, and hospital security.'),
('Retail Chains', 'retail-chains', 'Centralized networking and surveillance for multi-location retail stores.'),
('Manufacturing', 'manufacturing', 'Industrial networking and IoT solutions for modern factory floors.'),
('Warehousing & Logistics', 'warehousing-logistics', 'High-speed wireless and tracking infrastructure for large-scale warehouses.'),
('Banking & Finance', 'banking-finance', 'Highly secure VPNs and firewall solutions for financial institutions.'),
('Parking & Vehicle Management', 'parking-vehicle-management', 'Smart parking systems and automated vehicle access control.'),
('Construction & Camps', 'construction-camps', 'Rapid deployment mobile networks and remote site security.'),
('Oil & Gas', 'oil-gas', 'Mission-critical communication and monitoring for energy sites.')
ON DUPLICATE KEY UPDATE title=VALUES(title);

-- ── Expand Services ────────────────────────────────────────
INSERT INTO `services` (`title`, `slug`, `description`, `icon`) VALUES
('Datacenter Infrastructure', 'datacenter-infrastructure', 'Design and supply of server racks, UPS, and cooling systems.', 'icon-datacenter'),
('Cabling (Copper & Fiber)', 'cabling', 'Structured cabling solutions for multi-floor and campus-wide high-speed data.', 'icon-cabling'),
('Wireless & Microwave', 'wireless-microwave', 'Point-to-point microwave and campus-wide high-density WiFi.', 'icon-wireless'),
('Switching & Routing', 'switching-routing', 'Enterprise-grade core and edge switching for maximum uptime.', 'icon-network'),
('Firewall / UTM', 'firewall-utm', 'Unified Threat Management and perimeter security solutions.', 'icon-security'),
('VPN Solutions', 'vpn-solutions', 'Secure remote access and encrypted site-to-site connectivity.', 'icon-vpn'),
('IP Surveillance', 'ip-surveillance', 'High-definition 4K surveillance networks with centralized monitoring.', 'icon-cctv'),
('IPTV', 'iptv', 'Smart hotel entertainment and digital signage delivery.', 'icon-tv'),
('IP Telephony', 'ip-telephony', 'Scalable VoIP systems and enterprise unified communications.', 'icon-phone'),
('WiFi Networks', 'wifi-networks', 'Commercial-grade high-density wireless LAN deployments.', 'icon-wifi'),
('Access Control', 'access-control', 'Biometric and RFID-based building access systems.', 'icon-lock'),
('Internet Services', 'internet-services', 'High-speed leased line and broadband solutions for corporate.', 'icon-web'),
('Cloud Services', 'cloud-services', 'Reliable cloud storage, hosting, and backup solutions.', 'icon-cloud')
ON DUPLICATE KEY UPDATE title=VALUES(title);

-- ── Expand Products (Brands) ───────────────────────────────
INSERT INTO `products` (`name`, `slug`) VALUES
('Huawei', 'huawei'),
('Fortinet', 'fortinet'),
('Hikvision', 'hikvision'),
('Dahua', 'dahua'),
('Axis', 'axis'),
('Bosch', 'bosch'),
('Honeywell', 'honeywell'),
('Mitel', 'mitel'),
('Yeastar', 'yeastar')
ON DUPLICATE KEY UPDATE name=VALUES(name);
