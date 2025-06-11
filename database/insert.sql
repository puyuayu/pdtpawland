-- Insert users (for login system)
INSERT INTO users (username, password, full_name, email, role, phone, address) VALUES
('admin', SHA2('admin123', 256), 'Administrator', 'admin@pawland.com', 'admin', '081234567890', 'Jl. Admin No. 1'),
('staff1', SHA2('staff123', 256), 'John Doe', 'john@pawland.com', 'staff', '081234567891', 'Jl. Staff No. 1'),
('staff2', SHA2('staff123', 256), 'Jane Smith', 'jane@pawland.com', 'staff', '081234567892', 'Jl. Staff No. 2'),
('customer1', SHA2('cust123', 256), 'Alice Johnson', 'alice@email.com', 'customer', '081234567893', 'Jl. Customer No. 1');

-- Insert categories
INSERT INTO categories (category_name, description) VALUES
('Dogs', 'Dog breeds and dog-related products'),
('Cats', 'Cat breeds and cat-related products'),
('Birds', 'Various bird species and bird supplies'),
('Fish', 'Aquarium fish and aquatic supplies'),
('Pet Food', 'Food for various pets'),
('Accessories', 'Pet accessories and toys'),
('Medicine', 'Pet healthcare and medicine');

-- Insert suppliers
INSERT INTO suppliers (supplier_name, contact_person, phone, email, address) VALUES
('Pet Food Indonesia', 'Budi Santoso', '021-12345678', 'budi@petfood.co.id', 'Jakarta'),
('Animal Care Supplies', 'Siti Nurhaliza', '021-87654321', 'siti@animalcare.co.id', 'Bandung'),
('Exotic Pet Breeder', 'Ahmad Rahman', '0274-123456', 'ahmad@exoticpet.co.id', 'Yogyakarta');

-- Insert products
INSERT INTO products (product_name, category_id, supplier_id, price, stock_quantity, description, product_type, species, breed, age_months) VALUES
('Golden Retriever Puppy', 1, 3, 8000000, 3, 'Healthy golden retriever puppy', 'pet', 'Dog', 'Golden Retriever', 3),
('Persian Cat', 2, 3, 5000000, 2, 'Beautiful persian cat', 'pet', 'Cat', 'Persian', 6),
('Royal Canin Dog Food', 5, 1, 250000, 50, 'Premium dog food 15kg', 'food', NULL, NULL, NULL),
('Whiskas Cat Food', 5, 1, 75000, 80, 'Cat food wet 400g', 'food', NULL, NULL, NULL),
('Dog Leash Premium', 6, 2, 150000, 25, 'Strong leather dog leash', 'accessory', NULL, NULL, NULL),
('Cat Litter Box', 6, 2, 300000, 15, 'Self-cleaning cat litter box', 'accessory', NULL, NULL, NULL),
('Pet Multivitamin', 7, 2, 125000, 40, 'Complete multivitamin for pets', 'medicine', NULL, NULL, NULL);

-- Insert customers
INSERT INTO customers (customer_name, phone, email, address, date_of_birth, total_purchases, loyalty_points) VALUES
('Andi Wijaya', '081234567800', 'andi@email.com', 'Jl. Merdeka No. 10', '1985-05-15', 2500000, 250),
('Sari Indah', '081234567801', 'sari@email.com', 'Jl. Sudirman No. 25', '1990-08-20', 1800000, 180),
('Budi Hartono', '081234567802', 'budi@email.com', 'Jl. Thamrin No. 5', '1988-12-10', 3200000, 320);

-- Insert services
INSERT INTO services (service_name, description, base_price, duration_minutes) VALUES
('Pet Grooming Basic', 'Basic washing and grooming', 100000, 60),
('Pet Grooming Premium', 'Complete grooming with nail trim', 200000, 90),
('Pet Boarding (per day)', 'Pet boarding service per day', 150000, 1440),
('Veterinary Checkup', 'Basic health checkup', 300000, 30);

-- Sample sales data
INSERT INTO sales (customer_id, user_id, total_amount, discount_amount, tax_amount, final_amount, payment_method, status) VALUES
(1, 2, 400000, 20000, 38000, 418000, 'cash', 'completed'),
(2, 2, 750000, 0, 75000, 825000, 'card', 'completed');

-- Sample sale items
INSERT INTO sale_items (sale_id, product_id, quantity, unit_price, subtotal) VALUES
(1, 3, 1, 250000, 250000),
(1, 5, 1, 150000, 150000),
(2, 4, 10, 75000, 750000);
