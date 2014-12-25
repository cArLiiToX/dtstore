-- add table prefix if you have one
DELETE FROM eav_attribute WHERE attribute_code = 'pregunta' AND entity_type_id IN
                    (SELECT entity_type_id FROM eav_entity_type WHERE entity_type_code = 'catalog_product');
DROP TABLE IF EXISTS preguntas_products_pregunta_product;
DROP TABLE IF EXISTS preguntas_products_pregunta;
DELETE FROM core_resource WHERE code = 'preguntas_products_setup';
DELETE FROM core_config_data WHERE path like 'preguntas_products/%';