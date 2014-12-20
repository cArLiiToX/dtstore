-- add table prefix if you have one
DELETE FROM eav_attribute WHERE entity_type_id IN (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'amazonproducts_amazonproducts_amazonproduct');
DELETE FROM eav_entity_type WHERE entity_type_code = 'amazonproducts_amazonproducts_amazonproduct';
DELETE FROM eav_attribute WHERE attribute_code = 'entity_id' AND entity_type_id IN
                    (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'catalog_product');
DROP TABLE IF EXISTS amazonproducts_amazonproducts_amazonproduct_product;
DROP TABLE IF EXISTS amazonproducts_amazonproducts_amazonproduct_int;
DROP TABLE IF EXISTS amazonproducts_amazonproducts_amazonproduct_decimal;
DROP TABLE IF EXISTS amazonproducts_amazonproducts_amazonproduct_datetime;
DROP TABLE IF EXISTS amazonproducts_amazonproducts_amazonproduct_varchar;
DROP TABLE IF EXISTS amazonproducts_amazonproducts_amazonproduct_text;
DROP TABLE IF EXISTS amazonproducts_amazonproducts_amazonproduct;
DROP TABLE IF EXISTS amazonproducts_amazonproducts_eav_attribute;
DELETE FROM core_resource WHERE code = 'amazonproducts_amazonproducts_setup';
DELETE FROM core_config_data WHERE path like 'amazonproducts_amazonproducts/%';