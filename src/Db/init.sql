CREATE DATABASE IF NOT EXISTS api;

USE api;

CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(20),
  pass TEXT,
  email VARCHAR(60)
);

CREATE TABLE IF NOT EXISTS categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category_name VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  product_name VARCHAR(64),
  category_id INT,
  SKU TEXT,
  price FLOAT,
  quantity INT,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO categories(category_name)  VALUES ('Games'),('Computers'),('TVs and Accessories');
INSERT INTO products(product_name, category_Id, SKU, price, quantity)  VALUES 
('Pong', (SELECT id FROM categories WHERE category_name LIKE 'Games'),"A0001",69.99,20),
('GameStation 5', (SELECT id FROM categories WHERE category_name LIKE 'Games'),"A0002",269.99,15),
('AP Oman PC - Aluminum', (SELECT id FROM categories WHERE category_name LIKE 'Computers'),"A0003",1399.99,10),
('Fony UHD HDR 55\" 4k TV', (SELECT id FROM categories WHERE category_name LIKE 'TVs and Accessories'),"A0004",1399.99,5);
