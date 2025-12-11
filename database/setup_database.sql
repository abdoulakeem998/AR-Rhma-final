-- =====================================================
-- AR-Rahma Website - Complete MySQL Database Setup
-- Database: webtech_2025A_ngoila_karimou
-- =====================================================

-- Step 1: Create Database
DROP DATABASE IF EXISTS webtech_2025A_ngoila_karimou;
CREATE DATABASE webtech_2025A_ngoila_karimou CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE webtech_2025A_ngoila_karimou;

-- =====================================================
-- Table 1: USERS
-- =====================================================
CREATE TABLE USERS (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('admin', 'user', 'volunteer') DEFAULT 'user',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table 2: TEAM_MEMBERS
-- =====================================================
CREATE TABLE TEAM_MEMBERS (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    bio TEXT,
    photo VARCHAR(255),
    email VARCHAR(100),
    phone VARCHAR(20),
    social_linkedin VARCHAR(255),
    social_twitter VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE SET NULL,
    INDEX idx_position (position),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table 3: ACTIVITIES
-- =====================================================
CREATE TABLE ACTIVITIES (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    target_group VARCHAR(100),
    location VARCHAR(255),
    activity_date DATE NOT NULL,
    status ENUM('planned', 'ongoing', 'completed', 'cancelled') DEFAULT 'planned',
    budget DECIMAL(15, 2),
    actual_cost DECIMAL(15, 2),
    participants_count INT DEFAULT 0,
    image VARCHAR(255),
    gallery JSON,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES USERS(user_id) ON DELETE SET NULL,
    INDEX idx_date (activity_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table 4: DONATIONS
-- =====================================================
CREATE TABLE DONATIONS (
    donation_id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT,
    donor_name VARCHAR(100) NOT NULL,
    donor_email VARCHAR(100),
    donor_phone VARCHAR(20),
    amount DECIMAL(15, 2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'XOF',
    donation_type ENUM('one-time', 'monthly', 'yearly') DEFAULT 'one-time',
    payment_method VARCHAR(50),
    transaction_id VARCHAR(100),
    purpose VARCHAR(255),
    message TEXT,
    is_anonymous TINYINT(1) DEFAULT 0,
    status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    receipt_sent TINYINT(1) DEFAULT 0,
    donation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES USERS(user_id) ON DELETE SET NULL,
    INDEX idx_donor (donor_id),
    INDEX idx_status (status),
    INDEX idx_date (donation_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table 5: VOLUNTEERS
-- =====================================================
CREATE TABLE VOLUNTEERS (
    volunteer_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    date_of_birth DATE,
    skills TEXT,
    interests TEXT,
    availability VARCHAR(100),
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    motivation TEXT,
    experience TEXT,
    status ENUM('pending', 'approved', 'active', 'inactive') DEFAULT 'pending',
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_date TIMESTAMP NULL,
    approved_by INT,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES USERS(user_id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Table 6: CONTACT_MESSAGES
-- =====================================================
CREATE TABLE CONTACT_MESSAGES (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_name VARCHAR(100) NOT NULL,
    sender_email VARCHAR(100) NOT NULL,
    sender_phone VARCHAR(20),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    replied_at TIMESTAMP NULL,
    replied_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (replied_by) REFERENCES USERS(user_id) ON DELETE SET NULL,
    INDEX idx_status (status),
    INDEX idx_email (sender_email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Insert Default Admin User
-- Password: Admin@2023
-- =====================================================
INSERT INTO USERS (username, email, password, full_name, role, status) 
VALUES (
    'admin', 
    'admin@arrahma-niger.org', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Administrator',
    'admin',
    'active'
);

-- =====================================================
-- Insert Team Members
-- =====================================================
INSERT INTO TEAM_MEMBERS (full_name, position, department, display_order, is_active) VALUES
('Moustapha Saidou', 'President', 'Leadership', 1, 1),
('Ramatou AMIDOU', 'Vice President', 'Leadership', 2, 1),
('Leila Ousmane', 'Communications Officer', 'Communications', 3, 1),
('N\'goila Karimou Abdoul Akeem', 'Communications & Logistics Officer', 'Communications', 4, 1),
('Ibrahim Usman', 'Communications Officer', 'Communications', 5, 1),
('Abdoul Kader Issaka', 'Communications Officer', 'Communications', 6, 1),
('Ismaël Moussa', 'Secretary', 'Administration', 7, 1),
('Youssouf Abdou', 'Treasurer', 'Finance', 8, 1),
('Abdoul Nasser Djibo', 'Assistant Treasurer', 'Finance', 9, 1),
('Abdoul Moutalabi Illiassou', 'Business Affairs Officer', 'Business Affairs', 10, 1),
('Abdoul Aziz Tsahirou Alio', 'Business Affairs Officer', 'Business Affairs', 11, 1),
('Mariama Ibrahim', 'Business Affairs Officer', 'Business Affairs', 12, 1),
('Faysal Hamani', 'Business Affairs Officer', 'Business Affairs', 13, 1),
('Ramatou', 'Social Affairs Officer', 'Social Affairs', 14, 1),
('Habiboulaye Hamani', 'Logistics Officer', 'Logistics', 15, 1);

-- =====================================================
-- Insert Sample Activities
-- =====================================================
INSERT INTO ACTIVITIES (title, description, target_group, location, activity_date, status, budget, participants_count) VALUES
('Day with Orphans - ASHABAB', 'A full day with 200 orphans to have fun, eat together and bring them joy. Games, Squid Game, Q&A, match with children. Prizes and gifts after each game.', 'Orphans (200)', 'ASHABAB Orphanage, Niamey', '2023-02-18', 'completed', 500000, 200),

('Ramadan Kit Distribution', 'Collection and distribution of kits (dates, sugar, milk, tea) to underprivileged people living in straw houses. Including: 1 bag of sugar, 3 cartons of tea, 2 cartons of milk, 30 kilos of dates.', 'Underprivileged Families', 'Disadvantaged neighborhoods, Niamey', '2023-03-22', 'completed', 300000, 150),

('Collective Iftar - 10th Day of Ramadan', 'First collective breaking of fast with orphans and talibés. Distribution of oranges, watermelons, chicken legs, juices, dates, sugar, milk, and tea.', 'Orphans and Talibés', 'Niamey', '2023-04-06', 'completed', 400000, 180),

('Eid al-Fitr Kits', 'Distribution of chicken cartons (NUSEB) to underprivileged people on the eve of Eid celebration.', 'Families in Need', 'Niamey', '2023-04-21', 'completed', 250000, 100),

('Park Outing with Orphans', 'Recreational outing to restaurant and park with orphans for a day of fun and joy.', 'Orphans', 'Niamey Park', '2023-04-30', 'completed', 350000, 120),

('Food Kit Distribution', 'Purchase and distribution of food kits to the most underprivileged families in the community.', 'Underprivileged Families', 'Niamey', '2023-05-07', 'completed', 400000, 80),

('Monthly Meeting & Planning', 'Team meeting to review past activities and plan upcoming events for the next quarter.', 'Team Members', 'AR-Rahma Office, Niamey', '2023-05-14', 'completed', 50000, 15);

-- =====================================================
-- Insert Sample Donations (for testing)
-- =====================================================
INSERT INTO DONATIONS (donor_id, donor_name, donor_email, amount, donation_type, purpose, status) VALUES
(1, 'Anonymous Donor', 'donor1@example.com', 50000, 'one-time', 'Orphan Support', 'completed'),
(1, 'John Smith', 'john@example.com', 100000, 'monthly', 'General Donation', 'completed'),
(NULL, 'Anonymous', 'anonymous@example.com', 25000, 'one-time', 'Food Distribution', 'completed'),
(1, 'Sarah Johnson', 'sarah@example.com', 75000, 'one-time', 'Ramadan Activities', 'completed'),
(NULL, 'Anonymous Supporter', NULL, 150000, 'one-time', 'Education', 'completed');

-- =====================================================
-- Create Views for Reports
-- =====================================================

-- Donation Statistics View
CREATE VIEW donation_statistics AS
SELECT 
    COUNT(*) as total_donations,
    SUM(amount) as total_amount,
    AVG(amount) as average_donation,
    COUNT(DISTINCT donor_id) as unique_donors,
    YEAR(donation_date) as year,
    MONTH(donation_date) as month
FROM DONATIONS
WHERE status = 'completed'
GROUP BY YEAR(donation_date), MONTH(donation_date);

-- Volunteer Statistics View
CREATE VIEW volunteer_statistics AS
SELECT 
    status,
    COUNT(*) as count,
    YEAR(application_date) as year
FROM VOLUNTEERS
GROUP BY status, YEAR(application_date);

-- Activity Statistics View
CREATE VIEW activity_statistics AS
SELECT 
    status,
    COUNT(*) as count,
    SUM(budget) as total_budget,
    SUM(actual_cost) as total_spent,
    YEAR(activity_date) as year
FROM ACTIVITIES
GROUP BY status, YEAR(activity_date);

-- =====================================================
-- Database Setup Complete!
-- =====================================================

SELECT 'Database setup completed successfully!' as Status;
SELECT 'Default admin credentials:' as Info;
SELECT 'Username: admin' as Username;
SELECT 'Password: Admin@2023' as Password;
SELECT 'IMPORTANT: Change the admin password after first login!' as Warning;



-- Create activity_logs table if it doesn't exist
CREATE TABLE IF NOT EXISTS activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;