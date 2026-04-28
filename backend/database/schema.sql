CREATE DATABASE IF NOT EXISTS nexes_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE nexes_store;
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(100), name VARCHAR(255) NOT NULL, category VARCHAR(100), price DECIMAL(10,2) NOT NULL DEFAULT 0,
  stock INT NOT NULL DEFAULT 0, image VARCHAR(255), badge VARCHAR(50), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(150), email VARCHAR(180) UNIQUE, password_hash VARCHAR(255), is_b2b TINYINT DEFAULT 0, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY, customer_name VARCHAR(150), customer_email VARCHAR(180), total DECIMAL(10,2), status VARCHAR(50) DEFAULT 'pending', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY, order_id INT, product_id INT, product_name VARCHAR(255), price DECIMAL(10,2), qty INT DEFAULT 1,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
INSERT INTO products (brand,name,category,price,stock,image,badge) VALUES
('Apple','iPhone 15 Pro Max 256GB Natural Titanium','Smartphones',1249.99,12,'1899caec4e4b3775c0f136aaaa058063.jpg','NEW'),
('Samsung','Samsung Galaxy S24 Ultra 512GB Titanium Black','Smartphones',1199.99,8,'2a3380dfdecbb63c9c68b93a6ab692b1.jpg','HOT'),
('Apple','iPhone 14 Screen LCD + Touch Assembly','Spare Parts',89.99,50,'57b7102007238224259d6a1bed79f501.jpg','HOT'),
('Anker','Anker 65W GaN USB-C Charger','Accessories',39.99,40,'07c8e23881123433586a1bec7cf44a62.jpg','HOT'),
('iFixit','Professional Phone Repair Toolkit 38pcs','Tools',34.99,30,'9b31ed92a354b15421662a9acf014c10.jpg','PRO');
