-- Trigger to automatically update customer loyalty points after purchase
DELIMITER //
CREATE TRIGGER update_loyalty_points_after_sale
    AFTER UPDATE ON sales
    FOR EACH ROW
BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        UPDATE customers 
        SET loyalty_points = loyalty_points + FLOOR(NEW.final_amount / 10000)
        WHERE customer_id = NEW.customer_id;
    END IF;
END //

-- Trigger to log product stock changes
CREATE TRIGGER log_product_stock_changes
    AFTER UPDATE ON products
    FOR EACH ROW
BEGIN
    IF NEW.stock_quantity != OLD.stock_quantity THEN
        INSERT INTO inventory_logs (product_id, action_type, quantity_change, previous_stock, new_stock, notes, created_at)
        VALUES (NEW.product_id, 'adjust', NEW.stock_quantity - OLD.stock_quantity, OLD.stock_quantity, NEW.stock_quantity, 'Stock adjustment', NOW());
    END IF;
END //

-- Trigger to prevent negative stock
CREATE TRIGGER prevent_negative_stock
    BEFORE UPDATE ON products
    FOR EACH ROW
BEGIN
    IF NEW.stock_quantity < 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock cannot be negative';
    END IF;
END //

-- Trigger to update product updated_at timestamp
CREATE TRIGGER update_product_timestamp
    BEFORE UPDATE ON products
    FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END //

-- Trigger to validate service booking dates
CREATE TRIGGER validate_booking_date
    BEFORE INSERT ON service_bookings
    FOR EACH ROW
BEGIN
    IF NEW.scheduled_date <= NOW() THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Booking date must be in the future';
    END IF;
END //

DELIMITER ;
