-- ═══════════════════════════════════════════════════════════════
--  I Tech IT Solutions — Schema Migration v2
--  Adds: slug column to solutions/services, status columns
--  Run this if you already have the v1 schema imported.
-- ═══════════════════════════════════════════════════════════════

USE `itech_db`;

-- Add slug column to solutions (ignore if already exists)
ALTER TABLE `solutions`
    ADD COLUMN IF NOT EXISTS `slug`   VARCHAR(255) DEFAULT NULL AFTER `title`,
    ADD COLUMN IF NOT EXISTS `status` TINYINT(1)   NOT NULL DEFAULT 1 AFTER `image`;

-- Add slug column to services
ALTER TABLE `services`
    ADD COLUMN IF NOT EXISTS `slug`   VARCHAR(255) DEFAULT NULL AFTER `title`,
    ADD COLUMN IF NOT EXISTS `status` TINYINT(1)   NOT NULL DEFAULT 1 AFTER `icon`;

-- Populate slugs from existing titles
UPDATE `solutions` SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    TRIM(`title`),
    ' & ', '-'), ' ', '-'), '/', '-'), '(', ''), ')', ''), '--', '-'), '&', ''))
WHERE `slug` IS NULL OR `slug` = '';

UPDATE `services` SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    TRIM(`title`),
    ' & ', '-'), ' ', '-'), '/', '-'), '(', ''), ')', ''), '--', '-'), '&', ''))
WHERE `slug` IS NULL OR `slug` = '';

-- Unique indexes for slug lookups
ALTER TABLE `solutions` ADD UNIQUE INDEX IF NOT EXISTS `idx_solution_slug` (`slug`);
ALTER TABLE `services`  ADD UNIQUE INDEX IF NOT EXISTS `idx_service_slug`  (`slug`);

-- Add meta_description for rich SEO per solution/service
ALTER TABLE `solutions`
    ADD COLUMN IF NOT EXISTS `meta_description` VARCHAR(320) DEFAULT NULL AFTER `description`;
ALTER TABLE `services`
    ADD COLUMN IF NOT EXISTS `meta_description` VARCHAR(320) DEFAULT NULL AFTER `description`;
