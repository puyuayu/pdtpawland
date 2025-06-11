-- PROCEDURE to process a sale transaction
DELIMITER //
CREATE PROCEDURE ProcessSale(
    IN p_customer_id INT,
    IN p_user_id INT,
    IN p_payment_method VARCHAR(20),
    IN p_discount_amount DECIMAL(10,2),
    IN p_product_ids TEXT, -- comma separated
    IN p_quantities TEXT,  -- comma separated
    IN p_unit_prices TEXT  -- comma separated
)
BEGIN
    DECLARE v_sale_id INT;
    DECLARE v_total_amount DECIMAL(12,2) DEFAULT 0;
    DECLARE v_tax_amount DECIMAL(10,2) DEFAULT 0;
    DECLARE v_final_amount DECIMAL(12,2) DEFAULT 0;
    DECLARE v_product_id INT;
    DECLARE v_quantity INT;
    DECLARE v_unit_price DECIMAL(10,2);
    DECLARE v_subtotal DECIMAL(12,2);
    DECLARE v_current_stock INT;
    DECLARE v_counter INT DEFAULT 1;
    DECLARE v_total_items INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
 -- Calculate total items
    SET v_total_items = (LENGTH(p_product_ids) - LENGTH(REPLACE(p_product_ids, ',', '')) + 1);
    
    -- Create sale record
    INSERT INTO sales (customer_id, user_id, total_amount, discount_amount, tax_amount, final_amount, payment_method)
    VALUES (p_customer_id, p_user_id, 0, p_discount_amount, 0, 0, p_payment_method);
    
    SET v_sale_id = LAST_INSERT_ID();
    
    -- Process each item
    WHILE v_counter <= v_total_items DO
        SET v_product_id = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p_product_ids, ',', v_counter), ',', -1) AS UNSIGNED);
        SET v_quantity = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p_quantities, ',', v_counter), ',', -1) AS UNSIGNED);
        SET v_unit_price = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p_unit_prices, ',', v_counter), ',', -1) AS DECIMAL(10,2));
        SET v_subtotal = v_quantity * v_unit_price;
        
        -- Check stock availability
        SELECT stock_quantity INTO v_current_stock FROM products WHERE product_id = v_product_id;
        IF v_current_stock < v_quantity THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient stock for product';
        END IF;
-- Insert sale item
        INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, subtotal)
        VALUES (v_sale_id, v_product_id, v_quantity, v_unit_price, v_subtotal);
        
        -- Update product stock
        UPDATE products SET stock_quantity = stock_quantity - v_quantity WHERE product_id = v_product_id;
        
        -- Log inventory change
        INSERT INTO inventory_logs (product_id, user_id, action_type, quantity_change, previous_stock, new_stock, reference_id, reference_type)
        VALUES (v_product_id, p_user_id, 'sale', -v_quantity, v_current_stock, v_current_stock - v_quantity, v_sale_id, 'sale');
        
        SET v_total_amount = v_total_amount + v_subtotal;
        SET v_counter = v_counter + 1;
    END WHILE;
    
    -- Calculate tax (10% of total)
    SET v_tax_amount = (v_total_amount - p_discount_amount) * 0.10;
    SET v_final_amount = v_total_amount - p_discount_amount + v_tax_amount;
    
    -- Update sale totals
    UPDATE sales 
    SET total_amount = v_total_amount, tax_amount = v_tax_amount, final_amount = v_final_amount, status = 'completed'
    WHERE sale_id = v_sale_id;
     -- Update customer total purchases
    UPDATE customers 
    SET total_purchases = total_purchases + v_final_amount,
        loyalty_points = loyalty_points + FLOOR(v_final_amount / 10000)
    WHERE customer_id = p_customer_id;
    
    COMMIT;
    SELECT v_sale_id AS sale_id, v_final_amount AS final_amount;
END //


-- PROCEDURE to receive purchase order
CREATE PROCEDURE ReceivePurchaseOrder(
    IN p_order_id INT,
    IN p_user_id INT,
    IN p_product_ids TEXT,
    IN p_quantities_received TEXT
)
BEGIN
    DECLARE v_product_id INT;
    DECLARE v_quantity_received INT;
    DECLARE v_current_stock INT;
    DECLARE v_counter INT DEFAULT 1;
    DECLARE v_total_items INT;
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    SET v_total_items = (LENGTH(p_product_ids) - LENGTH(REPLACE(p_product_ids, ',', '')) + 1);
  WHILE v_counter <= v_total_items DO
        SET v_product_id = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p_product_ids, ',', v_counter), ',', -1) AS UNSIGNED);
        SET v_quantity_received = CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(p_quantities_received, ',', v_counter), ',', -1) AS UNSIGNED);
        
        -- Update purchase order item
        UPDATE purchase_order_items 
        SET quantity_received = v_quantity_received 
        WHERE order_id = p_order_id AND product_id = v_product_id;
        
        -- Get current stock
        SELECT stock_quantity INTO v_current_stock FROM products WHERE product_id = v_product_id;
        
        -- Update product stock
        UPDATE products 
        SET stock_quantity = stock_quantity + v_quantity_received 
        WHERE product_id = v_product_id;
        
        -- Log inventory change
        INSERT INTO inventory_logs (product_id, user_id, action_type, quantity_change, previous_stock, new_stock, reference_id, reference_type)
        VALUES (v_product_id, p_user_id, 'purchase', v_quantity_received, v_current_stock, v_current_stock + v_quantity_received, p_order_id, 'purchase');
SET v_counter = v_counter + 1;
    END WHILE;
    
    -- Update purchase order status
    UPDATE purchase_orders SET status = 'received' WHERE order_id = p_order_id;
    
    COMMIT;
END //


-- PROCEDURE to get monthly sales report
CREATE PROCEDURE GetMonthlySalesReport(
    IN p_year INT,
    IN p_month INT
)
BEGIN
    SELECT 
        DATE(s.sale_date) as sale_date,
        COUNT(s.sale_id) as total_transactions,
        SUM(s.final_amount) as daily_revenue,
        AVG(s.final_amount) as avg_transaction_value
    FROM sales s
    WHERE YEAR(s.sale_date) = p_year 
        AND MONTH(s.sale_date) = p_month
        AND s.status = 'completed'
    GROUP BY DATE(s.sale_date)
    ORDER BY sale_date;
    
    -- Summary
    SELECT 
        COUNT(*) as total_monthly_transactions,
        SUM(final_amount) as total_monthly_revenue,
        AVG(final_amount) as avg_monthly_transaction
    FROM sales 
    WHERE YEAR(sale_date) = p_year 
        AND MONTH(sale_date) = p_month
        AND status = 'completed';
END //

DELIMITER ;
