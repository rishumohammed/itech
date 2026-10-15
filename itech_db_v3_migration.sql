-- ═══════════════════════════════════════════════════════════════
--  I Tech IT Solutions — Schema Migration v3
--  Adds: rich content fields for detail pages + product slugs
--  Run AFTER itech_db_v2_migration.sql
-- ═══════════════════════════════════════════════════════════════

USE `itech_db`;

-- ── solutions: add rich content columns ────────────────────────
ALTER TABLE `solutions`
    ADD COLUMN IF NOT EXISTS `full_description` TEXT         DEFAULT NULL AFTER `description`,
    ADD COLUMN IF NOT EXISTS `challenges`        TEXT         DEFAULT NULL AFTER `full_description`,
    ADD COLUMN IF NOT EXISTS `our_solution`      TEXT         DEFAULT NULL AFTER `challenges`,
    ADD COLUMN IF NOT EXISTS `technologies`      VARCHAR(500) DEFAULT NULL AFTER `our_solution`;

-- ── services: add rich content columns ─────────────────────────
ALTER TABLE `services`
    ADD COLUMN IF NOT EXISTS `full_description` TEXT         DEFAULT NULL AFTER `description`,
    ADD COLUMN IF NOT EXISTS `sub_services`      TEXT         DEFAULT NULL AFTER `full_description`,
    ADD COLUMN IF NOT EXISTS `use_cases`         TEXT         DEFAULT NULL AFTER `sub_services`,
    ADD COLUMN IF NOT EXISTS `image`             VARCHAR(255) DEFAULT NULL AFTER `icon`;

-- ── products: add slug, description, website ───────────────────
ALTER TABLE `products`
    ADD COLUMN IF NOT EXISTS `slug`        VARCHAR(255) DEFAULT NULL AFTER `name`,
    ADD COLUMN IF NOT EXISTS `description` TEXT         DEFAULT NULL AFTER `logo`,
    ADD COLUMN IF NOT EXISTS `website`     VARCHAR(255) DEFAULT NULL AFTER `description`;

-- ── Populate product slugs from name ───────────────────────────
UPDATE `products`
SET `slug` = LOWER(REPLACE(REPLACE(REPLACE(TRIM(`name`), ' & ', '-'), ' ', '-'), '.', ''))
WHERE `slug` IS NULL OR `slug` = '';

-- ── Unique index for product slugs ─────────────────────────────
ALTER TABLE `products` ADD UNIQUE INDEX IF NOT EXISTS `idx_product_slug` (`slug`);
