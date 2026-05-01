CREATE DATABASE IF NOT EXISTS transport_portal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE transport_portal;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'focal_person', 'accounts', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


CREATE TABLE routes (
    route_id INT AUTO_INCREMENT PRIMARY KEY,
    route_name VARCHAR(100) NOT NULL,
    book_no INT,
    catalog_title VARCHAR(200),
    yearly_fare VARCHAR(50),
    academic_year VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE buses (
    bus_id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    capacity INT NOT NULL,
    route_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (route_id) REFERENCES routes(route_id) ON DELETE SET NULL
);

-- Students table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    student_reg_no VARCHAR(50) UNIQUE,
    route_id INT,
    user_id INT UNIQUE,
    fee_status ENUM('Paid', 'Unpaid', 'Pending') DEFAULT 'Unpaid',
    fee_amount DECIMAL(10, 2) DEFAULT 0,
    last_payment_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (route_id) REFERENCES routes(route_id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Focal persons table
CREATE TABLE focal_persons (
    focal_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    user_id INT UNIQUE,
    route_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (route_id) REFERENCES routes(route_id) ON DELETE SET NULL
);

-- Attendance table
CREATE TABLE attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    focal_id INT,
    date DATE NOT NULL,
    status ENUM('Present', 'Absent') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (focal_id) REFERENCES focal_persons(focal_id) ON DELETE SET NULL
);

-- Payments table
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE,
    due_date DATE,
    installment_number INT DEFAULT 1,
    status ENUM('Paid', 'Unpaid', 'Pending') DEFAULT 'Pending',
    processed_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Route stops table (for storing multiple stops per route)
CREATE TABLE route_stops (
    stop_id INT AUTO_INCREMENT PRIMARY KEY,
    route_id INT NOT NULL,
    stop_name VARCHAR(200) NOT NULL,
    stop_order INT,
    FOREIGN KEY (route_id) REFERENCES routes(route_id) ON DELETE CASCADE
);

-- Notifications table
CREATE TABLE notifications (
    notif_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'delay', 'alert', 'general') DEFAULT 'info',
    route_id INT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (route_id) REFERENCES routes(route_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Complaints table
CREATE TABLE complaints (
    complaint_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('Open', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Transport ratings table
CREATE TABLE transport_ratings (
    rating_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert default accounts user (password: accounts123)
INSERT INTO users (username, password, role) VALUES 
('accounts', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'accounts');

-- Insert sample routes
INSERT INTO routes (route_name, book_no, catalog_title, yearly_fare, academic_year) VALUES
('Samundri Road Four Season', 1, 'BUS ROUTE FROM SAMUNDRI ROAD', '13,750 × 4 = PKR 55,000', '2025–2026'),
('Sargodha Road', 2, 'BUS ROUTE FROM SARGODHA ROAD', '13,750 × 4 = PKR 55,000', '2025–2026'),
('Millat Town', 3, 'BUS ROUTE FROM MILLAT TOWN', '13,750 × 4 = PKR 55,000', '2025–2026'),
('GM Abad', 4, 'BUS ROUTE FROM SADER BAZAR GM ABAD', '13,750 × 4 = PKR 55,000', '2025–2026'),
('Samundri – Tandlianwala', 5, 'BUS ROUTE FROM SAMUNDRI – TANDALAWALA', '17,500 × 4 = PKR 70,000', '2025–2026'),
('Jaranwala', 6, 'BUS ROUTE FROM JARANWALA', '16,250 × 4 = PKR 65,000', '2025–2026'),
('Chiniot', 7, 'BUS ROUTE FROM CHINIOT', '20,000 × 4 = PKR 80,000', '2025–2026'),
('Shahkot', 8, 'BUS ROUTE FROM SHAHKOT', '17,500 × 4 = PKR 70,000', '2025–2026');

-- Insert sample buses
INSERT INTO buses (model, capacity, route_id) VALUES
('Daewoo 2022', 50, 1),
('Hino 2020', 48, 2),
('Yutong 2023', 52, 3),
('Mazda 2019', 45, 4),
('Master 2021', 47, 5),
('Daewoo 2020', 50, 6),
('Hino 2022', 46, 7),
('Yutong 2021', 51, 8);

-- Insert route stops for Route 1
INSERT INTO route_stops (route_id, stop_name, stop_order) VALUES
(1, 'Four Session Society (SAMUNDRI ROAD)', 1),
(1, 'D-type', 2),
(1, 'Novelty Pull / Samundri Road', 3),
(1, 'GTS Square', 4),
(1, 'Jhal Square', 5),
(1, 'Saleemi Square (SATIANA ROAD)', 6),
(1, 'Gate Square (SATIANA ROAD)', 7),
(1, 'Toll tex Square (SATIANA ROAD)', 8),
(1, 'Fish farm (SATIANA ROAD)', 9),
(1, 'SUPERIOR UNIVERSITY', 10);

-- Insert route stops for Route 2
INSERT INTO route_stops (route_id, stop_name, stop_order) VALUES
(2, 'Lassani Puli (SARGODHA ROAD)', 1),
(2, 'Allied Moar (SARGODHA ROAD)', 2),
(2, 'Millit Square (SARGODHA ROAD)', 3),
(2, 'Jamia Chistia Square (SARGODHA ROAD)', 4),
(2, 'General Bus Stand (SARGODHA ROAD)', 5),
(2, 'Chanab Club', 6),
(2, 'Tariqabad Pull', 7),
(2, 'Jarawala Road', 8),
(2, 'Degree College', 9),
(2, 'Jalvi Market (JARANWALA ROAD)', 10),
(2, 'Mukuana Byepass (JARANWALA ROAD)', 11),
(2, 'SUPERIOR UNIVERSITY', 12);

-- Insert route stops for Route 3
INSERT INTO route_stops (route_id, stop_name, stop_order) VALUES
(3, 'Hassan Square (MILLAT ROAD)', 1),
(3, 'Green Town Square (MILLAT ROAD)', 2),
(3, 'Noor pur (SHEIKHUPURA ROAD)', 3),
(3, 'Millat Square', 4),
(3, 'Hajiabad (SHEIKHUPURA ROAD)', 5),
(3, 'Nishtaabad Flyover (SHEIKHUPURA ROAD)', 6),
(3, 'Kashmir Pull (CANAL ROAD)', 7),
(3, 'Degree College (JARANWALA ROAD)', 8),
(3, 'Superior College Jaranwala Road', 9),
(3, 'Jalvi Market (JARANWALA ROAD)', 10),
(3, 'Lower Canal (JARANWALA ROAD / SATIANA ROAD)', 11),
(3, 'Fish Farm (SATIANA ROAD)', 12),
(3, 'SUPERIOR UNIVERSITY', 13);

-- Insert route stops for Route 4
INSERT INTO route_stops (route_id, stop_name, stop_order) VALUES
(4, 'Kabootran Wala Chowk (G.M. ABAD)', 1),
(4, 'Sadar Bazar (Bara Qabristan)', 2),
(4, 'Gulburg (POLICE STATION SQUARE)', 3),
(4, 'Jinnah Colony Gate', 4),
(4, 'Nishat Cinema Square', 5),
(4, 'Independent College', 6),
(4, 'Chanab Square (JHANG ROAD)', 7),
(4, 'Superior College Kotwali Road', 8),
(4, 'Katchary Bazar Square', 9),
(4, 'Gumtai Square', 10),
(4, 'GTS Square', 11),
(4, 'Jhal Square (SATIANA ROAD)', 12),
(4, 'Saleemi Square (SATIANA ROAD)', 13),
(4, 'Gate Square (SATIANA ROAD)', 14),
(4, 'Toll tex Square (SATIANA ROAD)', 15),
(4, 'SUPERIOR UNIVERSITY', 16);

-- Insert sample focal persons
INSERT INTO users (username, password, role) VALUES
('focal1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'focal_person'),
('focal2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'focal_person');

INSERT INTO focal_persons (name, user_id, route_id) VALUES
('Sir Ahmed Bilal', 3, 1),
('Madam Hina Saleem', 4, 2);

-- Insert sample student user (password: student123)
INSERT INTO users (username, password, role) VALUES
('student1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

INSERT INTO students (name, student_reg_no, route_id, user_id, fee_status, fee_amount) VALUES
('Ali Raza', 'ST-001', 1, 5, 'Paid', 55000);

-- Insert sample payments
INSERT INTO payments (student_id, amount, payment_date, due_date, installment_number, status) VALUES
(1, 13750, '2025-09-12', '2025-09-15', 1, 'Paid'),
(1, 13750, '2025-12-10', '2025-12-15', 2, 'Paid'),
(1, 13750, '2026-03-10', '2026-03-15', 3, 'Paid'),
(1, 13750, NULL, '2026-06-15', 4, 'Pending');
