-- add table prefix if you have one
DELETE FROM eav_attribute WHERE attribute_code = 'entity_id' AND entity_type_id IN
                    (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'catalog_product');
DROP TABLE IF EXISTS amazon_products_amazon_product;
DROP TABLE IF EXISTS amazon_products_amazon;
DELETE FROM core_resource WHERE code = 'amazon_products_setup';
DELETE FROM core_config_data WHERE path like 'amazon_products/%';