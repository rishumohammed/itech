-- ═══════════════════════════════════════════════════════════════
--  I Tech IT Solutions — Complete Production Database Dump
--  Compatible with Shared Hosting (cPanel / phpMyAdmin / DirectAdmin)
--  Note: Select your created database in phpMyAdmin, then click Import.
-- ═══════════════════════════════════════════════════════════════

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ──────────────────────────────────────────────────────────────
--  TABLE: solutions
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `solutions` (
  `id`               INT NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255) NOT NULL,
  `slug`             VARCHAR(255) DEFAULT NULL,
  `description`      TEXT NOT NULL,
  `meta_description` VARCHAR(320) DEFAULT NULL,
  `full_description` TEXT DEFAULT NULL,
  `challenges`       TEXT DEFAULT NULL,
  `our_solution`     TEXT DEFAULT NULL,
  `technologies`     VARCHAR(500) DEFAULT NULL,
  `image`            VARCHAR(512) DEFAULT NULL,
  `status`           TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_solution_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `solutions` (`id`, `title`, `slug`, `description`, `meta_description`, `full_description`, `challenges`, `our_solution`, `technologies`, `image`, `status`) VALUES
(1, 'Hotels & Resorts', 'hotels-resorts', 'Advanced WiFi, IPTV, and integrated security for luxury guest experiences.', 'Enterprise IT infrastructure, guest Wi-Fi and IPTV systems for luxury hotels & resorts in Saudi Arabia.', 'In the competitive hospitality sector, digital guest experience is a primary differentiator. I Tech provides high-density WiFi 6 coverage, interactive IPTV systems, and centralized security management designed specifically for the rigorous demands of hotels and resorts.', 'Dead zones in guest rooms\nOutdated analog TVs\nLack of centralized surveillance control\nSlow internet during peak occupancy', 'Site-wide WiFi 6 mesh networks with zero handoff\nNext-gen IPTV with interactive guest services portal\nHigh-definition IP surveillance with AI motion tracking\nPMS integrated billing systems', 'Cisco, Ruckus, Hikvision, Amino IPTV', 'sol_hospitality.png', 1),
(2, 'Healthcare', 'healthcare', 'Secure patient monitoring, telemedicine networks, and hospital-wide data isolation.', 'Mission-critical healthcare networks, HIS/EMR connectivity, and HIPAA/MOH compliant infrastructure in KSA.', 'Healthcare facilities require mission-critical uptime for patient data and telemedicine. Our healthcare infrastructure ensures secure, high-speed data flow between diagnostic equipment, nursing stations, and specialist clinics.', 'Medical equipment interfering with WiFi\nPatient data security compliance\nNeed for ultra-low latency in surgery monitoring\nFragmented communication between departments', 'Medical-grade shielded cabling (Cat6a/Cat7)\nSegmented VLANs for guest vs medical data\nReal-time Location Systems (RTLS) for equipment tracking\nUltra-reliable IP telephony for emergency coordination', 'Fortinet, HP Aruba, NEC Telephony, Stanley Healthcare', 'sol_healthcare.png', 1),
(3, 'Retail Chains', 'retail-chains', 'Centralized networking, POS connectivity, and multi-branch retail infrastructure.', 'Secure SD-WAN networks and CCTV systems for retail chains and shopping centers across Saudi Arabia.', 'Seamless connectivity across multiple retail branches is vital for inventory synchronization and POS reliability. We build unified network backbones connecting HQ, distribution centers, and stores.', 'POS downtime leading to revenue loss\nUnmonitored inventory shrinkage\nInconsistent customer Wi-Fi experience\nComplex multi-branch network management', 'Dual-WAN failover with automated 4G/5G backup\nAI retail heat-mapping and loss prevention cameras\nCentrally managed cloud firewall across all branches\nUnified customer engagement Wi-Fi portal', 'Fortinet, Cisco Meraki, Hikvision, Zebra', 'sol_retail.png', 1),
(4, 'Manufacturing', 'manufacturing', 'Industrial IoT, OT/IT convergence & rugged factory-floor network infrastructure.', 'High-durability network cabling and industrial switches for factories and manufacturing plants in Saudi Arabia.', 'Modern manufacturing plants rely on connected automation and real-time sensor feedback. We deploy ruggedized network switches and fiber backbones resilient to heat, dust, and electrical interference.', 'Dust, vibration and extreme heat damaging IT equipment\nLatency spikes interrupting automation lines\nCyber threats jumping from corporate IT to factory OT', 'IP67 rated industrial switches and fiber enclosures\nStrict air-gapped network segmentation between OT and IT\nRedundant ring topologies with sub-millisecond failover\nPredictive environmental monitoring sensors', 'Siemens, Cisco Industrial, Moxa, Panduit', 'sol_manufacturing.png', 1),
(5, 'Warehousing & Logistics', 'warehousing-logistics', 'High-speed wireless, barcode tracking & RFID network infrastructure for distribution hubs.', 'Ruggedized wireless networks and warehouse surveillance for logistics hubs and fulfillment centers.', 'Smart logistics require constant connectivity for inventory tracking and AGVs. We design high-bay wireless networks engineered specifically to overcome metal rack signal attenuation.', 'Blind spots in high-rack aisles\nSignal interference from metal structures and moving machinery\nLong roaming handover delays on barcode scanners', 'Ruggedized outdoor/indoor wireless access points\nPoint-to-multipoint microwave links for yard coverage\nNarrow-beam directional antennas along high aisles\n24/7 central video surveillance with license plate recognition', 'Cisco Meraki, Zebra Technologies, Honeywell, Axis', 'sol_warehouse.png', 1),
(6, 'Banking & Finance', 'banking-finance', 'Highly secure VPNs, zero-trust firewalls, and compliance-grade banking IT.', 'SAMA-compliant enterprise cybersecurity and datacenter infrastructure for banking and financial institutions.', 'Financial institutions require zero tolerance for downtime and strict regulatory compliance. We implement multi-layered perimeter defense and high-availability redundant architectures.', 'Strict SAMA cybersecurity compliance mandates\nThreat of advanced persistent attacks (APTs)\nHigh bandwidth requirements for financial transactions', 'Zero-trust architecture with micro-segmentation\nDual-active datacenters with automated disaster recovery\nEncrypted point-to-point leased lines and MPLS\nContinuous security operations monitoring', 'Palo Alto Networks, Fortinet, Cisco, F5', 'hero_enterprise.png', 1),
(7, 'Parking & Vehicle Management', 'parking-vehicle-management', 'Smart parking systems, automated barrier controls & ANPR surveillance.', 'Automated barrier gate, ANPR camera, and smart ticketing IT systems for commercial facilities in Jeddah.', 'Eliminate congestion and automate revenue collection with IoT-enabled smart parking guidance and automated number plate recognition (ANPR).', 'Traffic bottlenecks during peak hours\nManual ticketing fraud and lost revenue\nLack of space visibility for visitors', 'High-speed ANPR cameras with 99%+ accuracy\nAutomated boom barriers with RFID and barcode integration\nDigital LED space availability display boards\nCloud dashboard for parking analytics and revenue reports', 'Hikvision ANPR, Dahua, Nedap, Came', 'serv_surveillance.png', 1),
(8, 'Construction & Camps', 'construction-camps', 'Rapid deployment mobile networks, wireless links, and remote site security.', 'Quick-deployment temporary IT infrastructure, satellite/microwave internet, and CCTV for mega project sites.', 'Remote construction sites need instant, dependable connectivity for engineers, site offices, and worker accommodations. We deliver self-contained plug-and-play IT and security containers.', 'No existing wired telecommunication infrastructure\nHarsh outdoor desert conditions and dust\nNeed for rapid redeployment as projects advance', 'Microwave radio backhauls and 5G cellular gateways\nSolar-powered mobile CCTV surveillance towers\nPrefabricated containerized server rooms\nRugged mesh Wi-Fi covering worker camps', 'Ubiquiti, Cambium Networks, Teltonika, Hikvision', 'serv_cabling.png', 1),
(9, 'Oil & Gas', 'oil-gas', 'Ruggedized communications, explosion-proof CCTV & offshore-grade network engineering.', 'ATEX/IECEx certified networking and communication equipment for upstream and downstream oil & gas facilities.', 'Oil & gas environments require intrinsically safe, explosion-proof hardware certified for hazardous industrial zones. Our solutions maintain connectivity across remote drilling sites and refineries.', 'Hazardous volatile environments requiring ATEX certification\nMassive geographic spread between wellheads and control rooms\nStrict Aramco cybersecurity compliance (CCC/CBA)', 'IECEx/ATEX certified explosion-proof cameras and enclosures\nHardened fiber optic lines with redundant rings\nIndustrial SCADA communication gateways\nSecure satellite communication fallbacks', 'Bartec, Pelco, Cisco Industrial, Eaton MTL', 'noc_center.png', 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `description`=VALUES(`description`), `full_description`=VALUES(`full_description`);

-- ──────────────────────────────────────────────────────────────
--  TABLE: services
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `services` (
  `id`               INT NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255) NOT NULL,
  `slug`             VARCHAR(255) DEFAULT NULL,
  `description`      TEXT NOT NULL,
  `meta_description` VARCHAR(320) DEFAULT NULL,
  `full_description` TEXT DEFAULT NULL,
  `sub_services`     TEXT DEFAULT NULL,
  `use_cases`        TEXT DEFAULT NULL,
  `icon`             VARCHAR(255) DEFAULT NULL,
  `image`            VARCHAR(255) DEFAULT NULL,
  `status`           TINYINT(1) NOT NULL DEFAULT 1,
  `created_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_service_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` (`id`, `title`, `slug`, `description`, `meta_description`, `full_description`, `sub_services`, `use_cases`, `icon`, `image`, `status`) VALUES
(1, 'Datacenter Infrastructure', 'datacenter-infrastructure', 'Design and supply of server racks, precision cooling, UPS redundancy, and high-density racking.', 'Tier-rated datacenter design, server room construction, UPS power, and precision cooling in Jeddah, KSA.', 'Maximize your operational uptime with professional datacenter design, including precision cooling, UPS redundancy, and high-density racking.', 'Precision Cooling (In-row/CRAH)\nUninterruptible Power Supply (UPS)\nSmart PDU & Rack Management\nDCIM Monitoring Software\nRaised Floor & Fire Suppression', 'Enterprise private clouds\nColocation facility upgrades\nEdge computing site deployments', 'icon-datacenter', 'serv_datacenter.png', 1),
(2, 'Cabling (Copper & Fiber)', 'cabling', 'Structured Cat6A and OM4/OS2 fiber cabling certified for enterprise data transmission.', 'Certified Cat6A and fiber optic structured cabling services for buildings and campuses in Saudi Arabia.', 'The backbone of any business is its cable plant. We provide certified copper and fiber optic installations that guarantee high-bandwidth data transmission.', 'Cat6/Cat6a Structured Cabling\nSingle-mode & Multi-mode Fiber Optic\nFiber Splicing & OTDR Testing\nOSP (Outside Plant) Trenching & Cabling\nCable Management & Labeling', 'Campus-wide connectivity\nMulti-floor office fit-outs\nIndustrial automation networking', 'icon-cabling', 'serv_cabling.png', 1),
(3, 'Wireless & Microwave', 'wireless-microwave', 'Point-to-point microwave backhauls and campus-wide high-density wireless links.', 'Carrier-grade microwave point-to-point links and long-range outdoor wireless systems.', 'Extend your network reach without trenching costs. We deploy licensed and unlicensed microwave radios delivering multi-gigabit throughput across campuses.', 'Point-to-Point (PtP) High Capacity Links\nPoint-to-Multipoint (PtMP) Distribution\nRadio Frequency (RF) Site Surveys\nTower Rigging and Antenna Alignment', 'Connecting remote branches\nTemporary disaster recovery links\nCCTV backhauls across open terrain', 'icon-wireless', 'serv_wireless.png', 1),
(4, 'Switching & Routing', 'switching-routing', 'Enterprise-grade core, distribution, and edge switching engineered for 99.999% uptime.', 'Core switching, routing, and SD-WAN architecture design and deployment in Saudi Arabia.', 'Deploy robust, low-latency network backbones with Cisco, Huawei, and Aruba switches configured for high availability, QoS, and segmentation.', 'Core & Distribution Layer Switching\nBGP, OSPF & Multi-homed Routing\nSD-WAN Orchestration\nNetwork Access Control (802.1X)', 'Corporate headquarters\nMulti-tenant office towers\nHigh-frequency trading environments', 'icon-networking', 'serv_networking.png', 1),
(5, 'Firewall / UTM', 'firewall-utm', 'Next-Generation Firewalls, intrusion prevention (IPS), and zero-trust security architecture.', 'Enterprise Fortinet, Cisco, and Palo Alto firewall implementation and managed perimeter security.', 'Protect your enterprise from ransomware, DDoS attacks, and unauthorized intrusion with next-generation firewall and UTM appliances.', 'Next-Gen Firewall (NGFW) Deployment\nIntrusion Detection & Prevention (IDS/IPS)\nSSL/TLS Deep Packet Inspection\nWeb Filtering & Anti-Spam Gateways', 'Protecting customer records\nComplying with NCA ECC regulations\nSecuring remote hybrid workforces', 'icon-security', 'serv_security.png', 1),
(6, 'VPN Solutions', 'vpn-solutions', 'Secure remote access and encrypted site-to-site connectivity for distributed operations.', 'IPsec and SSL VPN enterprise solutions for secure inter-branch and remote staff communication.', 'Provide remote workers and satellite offices with seamless, encrypted access to corporate applications with multi-factor authentication (MFA).', 'Site-to-Site IPsec VPN Tunnels\nClient-to-Site SSL VPN with MFA\nZero-Trust Network Access (ZTNA)\nHigh-Throughput Hardware VPN Concentrators', 'Connecting branch offices to HQ\nEnabling work-from-home staff\nSecuring third-party vendor access', 'icon-vpn', 'serv_vpn.png', 1),
(7, 'IP Surveillance', 'ip-surveillance', 'High-definition 4K surveillance networks with AI analytics and centralized VMS platforms.', '4K IP CCTV, central monitoring stations, and AI facial recognition surveillance systems in Jeddah.', 'Our surveillance experts design high-resolution IP camera networks that provide total site visibility with intelligent AI-driven analytics.', '4K UHD Fixed/PTZ Cameras\nNetwork Video Recorders (NVR)\nVideo Management Software (VMS)\nFacial Recognition & LPR Analytics\nThermal Monitoring Solutions', 'Industrial safety monitoring\nRetail loss prevention\nCity-wide public safety surveillance\nCritical infrastructure protection', 'icon-camera', 'serv_surveillance.png', 1),
(8, 'IPTV', 'iptv', 'Smart hotel entertainment, digital signage delivery, and high-definition video distribution.', 'Enterprise IPTV headend design and digital signage systems for hotels, hospitals, and corporate boards.', 'Deliver crystal-clear digital TV, on-demand video, and branded digital signage over standard IP networks without separate coaxial cabling.', 'Hospitality Interactive IPTV Portals\nCentralized Digital Signage Networks\nSatellite Headend Ingestion Systems\nVideo-on-Demand (VOD) Servers', 'Luxury hotel guest rooms\nHospital patient bedside screens\nCorporate cafeteria information boards', 'icon-tv', 'serv_iptv.png', 1),
(9, 'IP Telephony', 'ip-telephony', 'Scalable VoIP systems, IP-PBX appliances, and enterprise unified communications.', 'Enterprise IP telephony, VoIP phone systems, and Microsoft Teams UC integration in KSA.', 'Modernize your business communications with unified voice, video, and messaging platforms that integrate seamlessly with your CRM and mobile devices.', 'IP-PBX Server Installation & Configuration\nVoIP Handsets, Conference Phones & Headsets\nSIP Trunk Integration with STC/Mobily/Zain\nContact Center & IVR Automated Attendants', 'Customer support call centers\nCorporate multi-extension offices\nRemote branch inter-calling at zero cost', 'icon-phone', 'serv_telephony.png', 1),
(10, 'WiFi Networks', 'wifi-networks', 'Commercial-grade high-density Wi-Fi 6/6E deployments with captive portals.', 'Enterprise Wi-Fi 6 wireless surveys, access point installation, and guest portal management.', 'Deliver fast, interference-free Wi-Fi coverage across offices, warehouses, and auditoriums with predictive site surveys and cloud controllers.', 'Predictive & Active RF Heat-Mapping\nWi-Fi 6 / 6E Access Point Deployment\nCustom Branded Captive Guest Portals\nBYOD Policies & IoT Network Segregation', 'High-density auditoriums & exhibitions\nLuxury hotels & shopping malls\nOpen-plan collaborative workspaces', 'icon-wifi', 'serv_wifi.png', 1),
(11, 'Access Control', 'access-control', 'Biometric, facial recognition, and RFID-based physical access security systems.', 'Biometric time attendance and door access control systems for commercial properties in Saudi Arabia.', 'Control and log who enters your facilities with biometric turnstiles, electromagnetic door locks, and centralized attendance software.', 'Facial Recognition & Fingerprint Terminals\nRFID Smart Card & Mobile NFC Access\nAutomated Pedestrian Turnstiles\nIntegration with Payroll & HR Software', 'Restricted server room doors\nCorporate office reception entry\nFactory workforce shift clocking', 'icon-lock', 'serv_access.png', 1),
(12, 'Internet Services', 'internet-services', 'High-speed dedicated leased line and redundant enterprise broadband solutions.', 'Dedicated corporate internet leased lines (DIA) with SLA and multi-carrier failover.', 'Ensure guaranteed symmetric bandwidth and low latency for mission-critical cloud applications through partnerships with top Saudi telecommunications carriers.', 'Dedicated Internet Access (DIA) Leased Lines\nMulti-Carrier BGP Redundancy (STC, Mobily, Zain)\nBandwidth Management & Traffic Shaping\nStatic Public IP Allocation & Routing', 'Primary corporate internet link\nCloud backup and video streaming\nE-commerce hosting connectivity', 'icon-web', 'serv_internet.png', 1),
(13, 'Cloud Services', 'cloud-services', 'Reliable cloud storage, hybrid cloud migration, and automated off-site backup solutions.', 'AWS, Microsoft Azure, and local Saudi cloud migration and managed infrastructure services.', 'Accelerate your digital transformation by migrating workloads to Microsoft Azure, AWS, or in-Kingdom cloud zones with automated disaster recovery.', 'Cloud Infrastructure Migration (IaaS/PaaS)\nMicrosoft 365 & Google Workspace Deployment\nAutomated Off-site Immutable Cloud Backups\nDisaster Recovery as a Service (DRaaS)', 'Replacing aging on-premise hardware\nOff-site ransomware-proof backup\nHigh-availability web applications', 'icon-cloud', 'serv_cloud.png', 1)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `description`=VALUES(`description`), `full_description`=VALUES(`full_description`);

-- ──────────────────────────────────────────────────────────────
--  TABLE: products
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `products` (
  `id`          INT NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(255) NOT NULL,
  `slug`        VARCHAR(255) DEFAULT NULL,
  `logo`        VARCHAR(512) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `website`     VARCHAR(255) DEFAULT NULL,
  `created_at`  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_product_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `name`, `slug`, `logo`, `description`, `website`) VALUES
(1, 'Cisco', 'cisco', 'assets/images/brands/cisco.png', 'Global leader in enterprise networking, switching, routing, and cybersecurity solutions.', 'https://www.cisco.com'),
(2, 'Huawei', 'huawei', 'assets/images/brands/huawei.png', 'Enterprise datacom, optical storage, intelligent campus networks, and cloud solutions.', 'https://e.huawei.com'),
(3, 'Fortinet', 'fortinet', 'assets/images/brands/fortinet.png', 'Next-generation firewalls (FortiGate), secure SD-WAN, and zero-trust cybersecurity platform.', 'https://www.fortinet.com'),
(4, 'Hikvision', 'hikvision', 'assets/images/brands/hikvision.png', 'The world’s leading provider of innovative security cameras, ANPR, and surveillance software.', 'https://www.hikvision.com'),
(5, 'Dahua', 'dahua', 'assets/images/brands/dahua.png', 'Advanced video surveillance, AI video analytics, and smart IoT security solutions.', 'https://www.dahuasecurity.com'),
(6, 'Axis', 'axis', 'assets/images/brands/axis.png', 'Pioneering specialist in network video, providing optimized surveillance, thermal, and analytics cameras.', 'https://www.axis.com'),
(7, 'Bosch', 'bosch', 'assets/images/brands/bosch.png', 'High-end security, public address, video monitoring, and integrated building automation systems.', 'https://www.boschsecurity.com'),
(8, 'Honeywell', 'honeywell', 'assets/images/brands/honeywell.png', 'Industrial grade sensors, building management systems, fire alarms, and security controls.', 'https://www.honeywell.com'),
(9, 'Mitel', 'mitel', 'assets/images/brands/mitel.png', 'Global market leader in business communications, IP-PBX, and hospitality telephony systems.', 'https://www.mitel.com'),
(10, 'Yeastar', 'yeastar', 'assets/images/brands/yeastar.png', 'Easy-to-use, versatile IP-PBX appliances and cloud VoIP unified communications for modern businesses.', 'https://www.yeastar.com')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `description`=VALUES(`description`);

-- ──────────────────────────────────────────────────────────────
--  TABLE: enquiries
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id`         INT NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255) NOT NULL,
  `email`      VARCHAR(255) NOT NULL,
  `phone`      VARCHAR(50) DEFAULT NULL,
  `company`    VARCHAR(255) DEFAULT NULL,
  `message`    TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ──────────────────────────────────────────────────────────────
--  TABLE: users
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`         INT NOT NULL AUTO_INCREMENT,
  `username`   VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `full_name`  VARCHAR(255) NOT NULL,
  `role`       ENUM('admin', 'subadmin') NOT NULL DEFAULT 'subadmin',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default login accounts (Self-healing on first login with password_hash):
-- admin / admin123
-- subadmin / subadmin123
INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `role`) VALUES
(1, 'admin',    '$2y$10$wE1Lp0fI5o5Wv8s8k1u1e.v7G9Y7E8z1h8P6/E1o8i5r4v3w2y1q0', 'Main Admin', 'admin'),
(2, 'subadmin', '$2y$10$wE1Lp0fI5o5Wv8s8k1u1e.v7G9Y7E8z1h8P6/E1o8i5r4v3w2y1q0', 'Support Staff', 'subadmin')
ON DUPLICATE KEY UPDATE `username`=VALUES(`username`);

-- ──────────────────────────────────────────────────────────────
--  TABLE: settings
-- ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
  `setting_key`   VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `category`      VARCHAR(50) DEFAULT 'general',
  `description`   VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` (`setting_key`, `setting_value`, `category`, `description`) VALUES
('site_name',        'I Tech IT Solutions', 'general', 'Visible name of the website'),
('site_tagline',     'Enterprise IT Infrastructure & Security Solutions', 'general', 'Hero/Footer tagline'),
('company_email',    'sales@itech-sa.com', 'contact', 'Primary contact email'),
('company_phone',    '+966 50 553 2108', 'contact', 'Primary contact phone'),
('company_whatsapp', '+966505532108', 'contact', 'WhatsApp number (no symbols)'),
('company_address',  'Khalid Bin Waleed Street, Jeddah, Kingdom of Saudi Arabia', 'contact', 'Full office address'),
('social_linkedin',  'https://www.linkedin.com/company/itechitsolutions', 'social', 'LinkedIn profile URL'),
('social_twitter',   'https://x.com/itechit', 'social', 'Twitter/X profile URL'),
('social_facebook',  'https://www.facebook.com/itechitsolutions', 'social', 'Facebook profile URL'),
('site_logo',        'assets/images/logo.png', 'appearance', 'Path to company logo'),
('hero_video',       'uploads/hero-video.mp4', 'appearance', 'Path to hero background video'),
('hero_poster',      'uploads/hero_enterprise.png', 'appearance', 'Fallback image for hero video'),
('brochure_url',     'assets/brochure.pdf', 'general', 'Direct link to company brochure pdf')
ON DUPLICATE KEY UPDATE `setting_key`=VALUES(`setting_key`);

SET FOREIGN_KEY_CHECKS=1;
