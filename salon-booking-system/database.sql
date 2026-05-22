CREATE DATABASE IF NOT EXISTS salondb;
USE salondb;

DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer','staff','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    service_name VARCHAR(100) NOT NULL,
    category VARCHAR(100),
    duration_minutes INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active'
);

CREATE TABLE appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status ENUM('Pending','Confirmed','Completed','Cancelled') DEFAULT 'Pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (service_id) REFERENCES services(service_id)
);

INSERT INTO users (full_name, email, phone, password_hash, role) VALUES
('Salon Administrator', 'admin@salon.local', '0400000000', '$2y$10$foFZct4bgywgW2HXeb6Oguaew0u5V.n6n42DmTVEFUnkr.QWx9SEW', 'admin'),
('Salon Staff', 'staff@salon.local', '0411111111', '$2y$10$khTRcLu/8tJIe8kQYMqc4.c6jEFvDmhR3ONvk6uOAUs8CLv24havK', 'staff');

INSERT INTO services (service_name, category, duration_minutes, price, status) VALUES
('Women Haircut', 'Haircuts & Styling', 45, 55.00, 'active'),
('Men Haircut', 'Haircuts & Styling', 30, 35.00, 'active'),
('Children Haircut', 'Haircuts & Styling', 25, 25.00, 'active'),
('Shampoo and Blow Dry', 'Haircuts & Styling', 35, 40.00, 'active'),
('Special Occasion Updo', 'Haircuts & Styling', 75, 95.00, 'active'),
('Root Touch Up', 'Hair Colour', 75, 70.00, 'active'),
('Full Hair Colour', 'Hair Colour', 100, 110.00, 'active'),
('Partial Highlights', 'Hair Colour', 110, 130.00, 'active'),
('Full Highlights', 'Hair Colour', 150, 180.00, 'active'),
('Balayage Colour', 'Hair Colour', 180, 220.00, 'active'),
('Deep Conditioning Treatment', 'Hair Treatments', 30, 35.00, 'active'),
('Keratin Smoothing Treatment', 'Hair Treatments', 150, 180.00, 'active'),
('Classic Manicure', 'Nails', 40, 45.00, 'active'),
('Gel Manicure', 'Nails', 55, 60.00, 'active'),
('Classic Pedicure', 'Nails', 50, 55.00, 'active'),
('Express Facial', 'Skincare', 35, 55.00, 'active'),
('Signature Facial', 'Skincare', 60, 85.00, 'active'),
('Eyebrow Wax', 'Waxing & Brows', 15, 20.00, 'active'),
('Lip Wax', 'Waxing & Brows', 10, 15.00, 'active'),
('Brow Tint', 'Waxing & Brows', 20, 25.00, 'active'),
('Makeup Application', 'Makeup & Bridal', 60, 75.00, 'active'),
('Bridal Hair Consultation', 'Makeup & Bridal', 45, 50.00, 'active');
