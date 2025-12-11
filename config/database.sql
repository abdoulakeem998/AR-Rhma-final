-- ============================================
-- AR-RAHMA DATABASE - COMPLETE SCHEMA
-- ============================================

DROP DATABASE IF EXISTS ar_rahma_db;
CREATE DATABASE ar_rahma_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ar_rahma_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Admins table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'admin',
    status ENUM('active', 'inactive') DEFAULT 'active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB;

-- Insert default admin (password: admin123)
INSERT INTO admins (username, email, password_hash, full_name, role, status) 
VALUES ('admin', 'admin@ar-rahma.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin', 'active');

-- Activities table
CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    activity_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    beneficiaries INT DEFAULT 0,
    image_url VARCHAR(500),
    category ENUM('orphan_support', 'disability_care', 'poverty_relief', 'education', 'healthcare', 'emergency_relief', 'other') DEFAULT 'other',
    status ENUM('active', 'archived', 'draft') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_date (activity_date),
    INDEX idx_status (status),
    INDEX idx_featured (featured)
) ENGINE=InnoDB;

-- Team members table
CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    position VARCHAR(255) NOT NULL,
    bio TEXT,
    email VARCHAR(255),
    phone VARCHAR(20),
    photo_url VARCHAR(500),
    linkedin_url VARCHAR(500),
    twitter_url VARCHAR(500),
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Open roles table
CREATE TABLE open_roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT,
    responsibilities TEXT,
    type ENUM('volunteer', 'internship', 'full_time', 'part_time', 'contract') DEFAULT 'volunteer',
    location VARCHAR(255),
    duration VARCHAR(100),
    slots_available INT DEFAULT 1,
    slots_filled INT DEFAULT 0,
    deadline DATE,
    status ENUM('open', 'closed', 'draft') DEFAULT 'open',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Applications table
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    role_id INT NOT NULL,
    cover_letter TEXT,
    resume_url VARCHAR(500),
    availability TEXT,
    status ENUM('pending', 'reviewed', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending',
    admin_notes TEXT,
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES open_roles(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_role_id (role_id),
    INDEX idx_status (status),
    UNIQUE KEY unique_application (user_id, role_id)
) ENGINE=InnoDB;

-- News table
CREATE TABLE news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt VARCHAR(500),
    image_url VARCHAR(500),
    category ENUM('announcement', 'event', 'success_story', 'press_release', 'general') DEFAULT 'general',
    status ENUM('published', 'draft', 'archived') DEFAULT 'draft',
    featured BOOLEAN DEFAULT FALSE,
    published_date DATE,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_published_date (published_date),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Gallery table
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(500) NOT NULL,
    activity_id INT,
    category ENUM('activities', 'events', 'team', 'facilities', 'beneficiaries', 'other') DEFAULT 'other',
    display_order INT DEFAULT 0,
    status ENUM('active', 'archived') DEFAULT 'active',
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE SET NULL,
    FOREIGN KEY (uploaded_by) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- FAQs table
CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category ENUM('general', 'donations', 'volunteering', 'activities', 'contact') DEFAULT 'general',
    display_order INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Contact messages table
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'responded', 'archived') DEFAULT 'new',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- Feedback table
CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    rating INT CHECK (rating BETWEEN 1 AND 5),
    feedback_type ENUM('general', 'website', 'service', 'suggestion', 'complaint') DEFAULT 'general',
    message TEXT NOT NULL,
    status ENUM('new', 'reviewed', 'resolved') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Site settings table
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    description VARCHAR(500),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert default settings
INSERT INTO site_settings (setting_key, setting_value, description) VALUES
('site_name', 'AR-Rahma', 'Website name'),
('site_description', 'Non-profit humanitarian association based in Niamey, Niger', 'Site description'),
('contact_email', 'info@ar-rahma.org', 'Main contact email'),
('contact_phone', '+227 XX XX XX XX', 'Main contact phone'),
('contact_address', 'Niamey, Niger', 'Physical address'),
('facebook_url', '', 'Facebook page URL'),
('twitter_url', '', 'Twitter profile URL'),
('instagram_url', '', 'Instagram profile URL'),
('linkedin_url', '', 'LinkedIn profile URL');

-- Activity logs table
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(100),
    entity_id INT,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX idx_admin_id (admin_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- Insert sample data for testing
INSERT INTO activities (title, description, activity_date, location, beneficiaries, category, status, featured) VALUES
('Food Distribution to Orphans', 'Monthly food distribution program providing essential supplies to orphaned children in the community.', '2024-11-15', 'Niamey Central Orphanage', 50, 'orphan_support', 'active', TRUE),
('Medical Camp for Disabled', 'Free medical checkup and treatment camp for people with disabilities.', '2024-11-20', 'Community Health Center', 75, 'disability_care', 'active', TRUE),
('Education Scholarship Program', 'Providing school fees and supplies for children from low-income families.', '2024-12-01', 'Various Schools in Niamey', 100, 'education', 'active', FALSE);

INSERT INTO team_members (full_name, position, bio, email, display_order, status) VALUES
('Dr. Ibrahim Mohammed', 'Executive Director', 'Leading AR-Rahma since 2020 with over 15 years of humanitarian work experience.', 'ibrahim@ar-rahma.org', 1, 'active'),
('Fatima Al-Hassan', 'Program Coordinator', 'Manages all community outreach and volunteer programs.', 'fatima@ar-rahma.org', 2, 'active'),
('Ahmed Diallo', 'Finance Manager', 'Ensures transparent financial management and accountability.', 'ahmed@ar-rahma.org', 3, 'active');

INSERT INTO open_roles (title, description, requirements, responsibilities, type, location, status) VALUES
('Community Volunteer', 'Help us distribute food and supplies to families in need.', 'Compassionate, reliable, able to work weekends', 'Assist in food distribution, interact with beneficiaries, maintain records', 'volunteer', 'Niamey', 'open'),
('Social Media Manager', 'Manage our social media presence and engage with our community online.', 'Experience with social media platforms, good communication skills', 'Create content, respond to messages, report on metrics', 'volunteer', 'Remote', 'open');

INSERT INTO faqs (question, answer, category, display_order, status) VALUES
('How can I donate to AR-Rahma?', 'You can donate through our website donation page, bank transfer, or visit our office in person.', 'donations', 1, 'active'),
('Can I volunteer if I am a student?', 'Yes! We welcome students and provide flexible volunteering schedules.', 'volunteering', 2, 'active'),
('What areas do you serve?', 'We primarily serve communities in and around Niamey, Niger.', 'general', 3, 'active');
