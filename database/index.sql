CREATE INDEX idx_sales_date ON sales(sale_date);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_name ON products(product_name);
CREATE INDEX idx_customers_name ON customers(customer_name);
CREATE INDEX idx_inventory_logs_product ON inventory_logs(products)