-- Function to calculate customer loyalty tier
DELIMITER //
CREATE FUNCTION GetCustomerLoyaltyTier(p_customer_id INT) 
RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_total_purchases DECIMAL(12,2);
    DECLARE v_tier VARCHAR(20);
    
    SELECT total_purchases INTO v_total_purchases 
    FROM customers 
    WHERE customer_id = p_customer_id;
    
    IF v_total_purchases >= 10000000 THEN
        SET v_tier = 'Diamond';
    ELSEIF v_total_purchases >= 5000000 THEN
        SET v_tier = 'Gold';
    ELSEIF v_total_purchases >= 2000000 THEN
        SET v_tier = 'Silver';
    ELSE
        SET v_tier = 'Bronze';
    END IF;
    
    RETURN v_tier;
END //

-- Function to calculate product profit margin
CREATE FUNCTION CalculateProductProfit(p_product_id INT, p_selling_price DECIMAL(10,2))
RETURNS DECIMAL(5,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_avg_cost DECIMAL(10,2);
    DECLARE v_profit_margin DECIMAL(5,2);
    
    SELECT AVG(unit_cost) INTO v_avg_cost
    FROM purchase_order_items poi
    JOIN purchase_orders po ON poi.order_id = po.order_id
    WHERE poi.product_id = p_product_id AND po.status = 'received';
    
    IF v_avg_cost IS NULL OR v_avg_cost = 0 THEN
        RETURN 0;
    END IF;
    
    SET v_profit_margin = ((p_selling_price - v_avg_cost) / v_avg_cost) * 100;
    
    RETURN v_profit_margin;
END //

-- Function to get stock status
CREATE FUNCTION GetStockStatus(p_product_id INT)
RETURNS VARCHAR(20)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_stock INT;
    DECLARE v_status VARCHAR(20);
    
    SELECT stock_quantity INTO v_stock FROM products WHERE product_id = p_product_id;
    
    IF v_stock = 0 THEN
        SET v_status = 'Out of Stock';
    ELSEIF v_stock <= 5 THEN
        SET v_status = 'Low Stock';
    ELSEIF v_stock <= 20 THEN
        SET v_status = 'Normal';
    ELSE
        SET v_status = 'Well Stocked';
    END IF;
    
    RETURN v_status;
END //

DELIMITER ;
