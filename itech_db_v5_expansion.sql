-- ═══════════════════════════════════════════════════════════
--  I Tech IT Solutions — Rich Content Expansion (v5)
--  Fixes: Solution/Service accuracy and detailed fields
-- ═══════════════════════════════════════════════════════════

USE `itech_db`;

-- ── 1. Correct Solutions Content ───────────────────────────
UPDATE `solutions` SET 
  `description` = 'Advanced WiFi, IPTV, and integrated security for luxury guest experiences.',
  `full_description` = 'In the competitive hospitality sector, digital guest experience is a primary differentiator. I Tech provides high-density WiFi 6 coverage, interactive IPTV systems, and centralized security management designed specifically for the rigorous demands of hotels and resorts.',
  `challenges` = 'Dead zones in guest rooms\nOutdated analog TVs\nLack of centralized surveillance control\nSlow internet during peak occupancy',
  `our_solution` = 'Site-wide WiFi 6 mesh networks with zero handoff\nNext-gen IPTV with interactive guest services portal\nHigh-definition IP surveillance with AI motion tracking\nPMS integrated billing systems',
  `technologies` = 'Cisco, Ruckus, Hikvision, Amino IPTV'
WHERE `slug` = 'hotels-resorts';

UPDATE `solutions` SET 
  `description` = 'Secure patient monitoring, telemedicine networks, and hospital-wide data isolation.',
  `full_description` = 'Healthcare facilities require mission-critical uptime for patient data and telemedicine. Our healthcare infrastructure ensures secure, high-speed data flow between diagnostic equipment, nursing stations, and specialist clinics.',
  `challenges` = 'Medical equipment interfering with WiFi\nPatient data security compliance\nNeed for ultra-low latency in surgery monitoring\nFragmented communication between departments',
  `our_solution` = 'Medical-grade shielded cabling (Cat6a/Cat7)\nSegmented VLANs for guest vs medical data\nReal-time Location Systems (RTLS) for equipment tracking\nUltra-reliable IP telephony for emergency coordination',
  `technologies` = 'Fortinet, HP Aruba, NEC Telephony, Stanley Healthcare'
WHERE `slug` = 'healthcare';

-- (Fleshing out others...)
UPDATE `solutions` SET `full_description`='Smart logistics require constant connectivity for inventory tracking and AGVs.', `challenges`='Blind spots in high-rack aisles\nSignal interference from metal structures', `our_solution`='Ruggedized outdoor/indoor wireless access points\nPoint-to-multipoint microwave links for yard coverage' WHERE `slug`='warehousing-logistics';

-- ── 2. Expand Services Content ──────────────────────────────
UPDATE `services` SET 
  `full_description` = 'Our surveillance experts design high-resolution IP camera networks that provide total site visibility with intelligent AI-driven analytics.',
  `sub_services` = '4K UHD Fixed/PTZ Cameras\nNetwork Video Recorders (NVR)\nVideo Management Software (VMS)\nFacial Recognition & LPR Analytics\nThermal Monitoring Solutions',
  `use_cases` = 'Industrial safety monitoring\nRetail loss prevention\nCity-wide public safety surveillance\nCritical infrastructure protection'
WHERE `slug` = 'ip-surveillance';

UPDATE `services` SET 
  `full_description` = 'Maximize your operational uptime with professional datacenter design, including precision cooling, UPS redundancy, and high-density racking.',
  `sub_services` = 'Precision Cooling (In-row/CRAH)\nUninterruptible Power Supply (UPS)\nSmart PDU & Rack Management\nDCIM Monitoring Software\nRaised Floor & Fire Suppression',
  `use_cases` = 'Enterprise private clouds\nColocation facility upgrades\nEdge computing site deployments'
WHERE `slug` = 'datacenter-infrastructure';

UPDATE `services` SET 
  `full_description` = 'The backbone of any business is its cable plant. We provide certified copper and fiber optic installations that guarantee high-bandwidth data transmission.',
  `sub_services` = 'Cat6/Cat6a Structured Cabling\nSingle-mode & Multi-mode Fiber Optic\nFiber Splicing & OTDR Testing\nOSP (Outside Plant) Trenching & Cabling\nCable Management & Labeling',
  `use_cases` = 'Campus-wide connectivity\nMulti-floor office fit-outs\nIndustrial automation networking'
WHERE `slug` = 'cabling';

-- ── 3. Add Products (Brands) Descriptions ──────────────────
UPDATE `products` SET `description`='Global leader in IP networking, core switching, and enterprise wireless solutions.' WHERE `name`='Cisco';
UPDATE `products` SET `description`='Next-generation firewall (NGFW) and industry-leading SD-WAN security platform.' WHERE `name`='Fortinet';
UPDATE `products` SET `description`='The world’s leading provider of innovative security cameras and surveillance software.' WHERE `name`='Hikvision';
UPDATE `products` SET `description`='Pioneering specialist in network video, providing optimized surveillance and analytics.' WHERE `name`='Axis';
