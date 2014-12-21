-- add table prefix if you have one
DROP TABLE IF EXISTS banners_nikolai_bannerdt;
DELETE FROM core_resource WHERE code = 'banners_nikolai_setup';
DELETE FROM core_config_data WHERE path like 'banners_nikolai/%';