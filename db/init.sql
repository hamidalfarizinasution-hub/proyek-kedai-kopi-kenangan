CREATE DATABASE IF NOT EXISTS kedaikopi;
USE kedaikopi;
CREATE TABLE IF NOT EXISTS menu (
id INT AUTO_INCREMENT PRIMARY KEY,
nama_produk VARCHAR(100) NOT NULL,
KATEGORI VARCHAR(50) NOT NULL,
harga INT NOT NULL,
stok INT NOT NULL
);
INSERT INTO menu (nama_produk, kategori, harga, stok) VALUES
('Es Kopi Susu Gula Aren', 'Coffe', 18000, 50),
('Americano Hot', 'Coffe', 15000, 30),
('Matcha Latte', 'Non-Coffe', 22000, 25),
('Roti Bakar Cokelat', 'Snack', 12000, 40);
