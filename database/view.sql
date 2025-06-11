-- Sales summary view
CREATE VIEW sales_summary AS
SELECT 
    s.sale_id,
    s.sale_date,
    c.customer_name,
    u.full_name as staff_name,
    s.total_amount,
    s.discount_amount,
    s.final_amount,
    s.payment_method,
    s.status
FROM sales s
LEFT JOIN customers c ON s.customer_id = c.customer_id
LEFT JOIN users u ON s.user_id = u.user_id;

-- Product inventory view
CREATE VIEW product_inventory AS
SELECT 
    p.product_id,
    p.product_name,
    c.category_name,
    p.price,
    p.stock_quantity,
    GetStockStatus(p.product_id) as stock_status,
    p.product_type,
    p.species,
    p.breed
FROM products p
LEFT JOIN categories c ON p.category_id = c.category_id;

-- Customer loyalty view
CREATE VIEW customer_loyalty AS
SELECT 
    customer_id,
    customer_name,
    total_purchases,
    loyalty_points,
    GetCustomerLoyaltyTier(customer_id) as loyalty_tier,
    registration_date
FROM customers;
