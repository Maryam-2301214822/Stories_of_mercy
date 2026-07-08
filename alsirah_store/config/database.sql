CREATE DATABASE IF NOT EXISTS alsirah_store;
USE alsirah_store;

-- =========================
-- 1. USERS
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =========================
-- 2. CATEGORIES
-- =========================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

-- =========================
-- 3. BOOKS
-- =========================
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100),
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    pdf_file VARCHAR(255) NULL,
    stock INT DEFAULT 0,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- =========================
-- 4. ORDERS
-- =========================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    total_price DECIMAL(10,2),
    payment_method VARCHAR(100),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- 5. ORDER ITEMS
-- =========================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    book_id INT,
    price DECIMAL(10,2),

    FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,

    FOREIGN KEY (book_id) REFERENCES books(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================
-- 6. FAVORITES
-- =========================
CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (book_id) REFERENCES books(id)
        ON DELETE CASCADE
) ENGINE=InnoDB; س

 -- 1. إضافة مستخدم عادي افتراضي (Default User)
INSERT INTO users (name, email, password, role) 
VALUES ('المستخدم الافتراضي', 'user@example.com', '123456', 'user');

-- 2. إضافة مدير للنظام افتراضي (Default Admin)
INSERT INTO users (name, email, password, role) 
VALUES ('مدير النظام', 'admin@example.com', 'admin123', 'admin');