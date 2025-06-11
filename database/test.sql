-- Test the functions
SELECT GetCustomerLoyaltyTier(1) as customer_tier;
SELECT GetStockStatus(3) as stock_status;
SELECT CalculateProductProfit(3, 250000) as profit_margin;

-- Test the views
SELECT * FROM sales_summary LIMIT 5;
SELECT * FROM product_inventory WHERE stock_status = 'Low Stock';
SELECT * FROM customer_loyalty ORDER BY total_purchases DESC;

-- Call stored procedures
CALL GetMonthlySalesReport(2025, 6);
CALL BackupDatabase();

