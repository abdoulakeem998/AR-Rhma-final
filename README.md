AR-RAHMA Humanitarian Association Website
Live Server Link: http://169.239.251.102/~ngoila.karimou/uploads/AR-Rhma-final/

About AR-RAHMA
AR-RAHMA is a humanitarian association based in Niamey, Niger, dedicated to alleviating poverty and bringing joy to orphans, people with disabilities, and low-income families through community support programs grounded in Islamic principles. Founded in December 2022, AR-RAHMA creates a supportive community where compassion meets action and every contribution makes a difference.

Key Features
For All Users
Activities Gallery - Browse 37+ humanitarian programs with photos and details

Team Showcase - Meet our dedicated association members

Photo Gallery - View images from our community events

FAQ Section - Answers to common questions about our work

Contact Form - Easy way to reach our team

Donation Information - Learn how to support our mission

For Registered Volunteers
Volunteer Application System - Apply for specific humanitarian roles

Application Tracking - Real-time status updates

Personal Dashboard - Manage your applications and profile

Profile Management - Update your personal information

Anonymous Feedback Option - Share thoughts privately if preferred

For Association Admins
Complete CMS - Manage all website content through admin panel

Activity Management - Add, edit, and organize humanitarian programs

Team Management - Update member information and photos

Application Review System - Process volunteer applications

Statistics Dashboard - View platform analytics and impact metrics

Content Management - Update site settings, FAQs, and resources

Technologies Used
Backend: PHP 8.3

Database: MySQL 8.0

Frontend: HTML5, CSS3, JavaScript ES6+

AJAX: Real-time interactions without page reload

Frameworks: Bootstrap 5.3 for responsive design

Icons: Font Awesome 6.0

Fonts: Poppins (Headings), Open Sans (Body)

Charts: Chart.js for data visualization

Email: PHPMailer for notifications

Database Schema
Tables
USERS - User accounts with role management (admin, volunteer, donor)

TEAM_MEMBERS - Association leadership and staff information

ACTIVITIES - Humanitarian programs and events

VOLUNTEER_ROLES - Available volunteer positions

APPLICATIONS - Volunteer applications with status tracking

DONATIONS - Contribution records and tracking

CONTACT_MESSAGES - Inquiry and communication management

GALLERY - Photo and media content

SITE_SETTINGS - Website configuration and content

Installation & Setup
Prerequisites
PHP 7.4 or higher

MySQL 5.7 or higher

Apache/Nginx web server

Setup Steps
Upload Project Files

bash
# Copy all files to server
/~ngoila.karimou/uploads/AR-Rhma-final/
Database Configuration

sql
-- Create database
CREATE DATABASE webtech_2025A_ngoila_karimou;

-- Import the schema
USE webtech_2025A_ngoila_karimou;
-- Run all CREATE TABLE statements from config/database.sql
Configure Database Connection
Edit config/database.php:

php
$host = 'localhost';
$dbname = 'webtech_2025A_ngoila_karimou';
$username = 'ngoila.karimou';
$password = 'Joker99@';
Set Upload Directory Permissions

bash
mkdir -p uploads/activities uploads/members uploads/gallery uploads/documents
chmod 755 uploads/
chmod 755 uploads/*/
Access the Application
Use the welcome page at: http://your-server/~ngoila.karimou/uploads/AR-Rhma-final/

User Roles & Access
Regular Visitor
Browse activities and gallery

View team information

Read FAQs and resources

Submit contact inquiries

Access donation information

Registered Volunteer
All visitor features

Apply for volunteer roles

Track application status

Update personal profile

View application history

Association Admin
All user features

Complete content management

Review volunteer applications

Manage team members

Update site settings

View analytics dashboard

Default Credentials
Admin Account:
Email: admin@ar-rahma.org

Password: admin123

Access URL: /admin/login.php

Test Volunteer Account:
Email: test@example.com

Password: password123

Volunteer Access:
Register through signup page

No special codes required

Project Structure
text
AR-Rhma-final/
├── config/
│   └── database.php          # Database configuration
├── includes/
│   ├── header.php            # Site header
│   └── footer.php            # Site footer
├── assets/
│   ├── css/
│   │   └── style.css         # Complete CSS for all pages
│   └── js/
│       └── main.js           # Complete JavaScript functionality
├── uploads/                  # User uploads directory
│   ├── activities/           # Activity photos
│   ├── members/              # Team member photos
│   ├── gallery/              # Gallery images
│   └── documents/            # Application documents
├── admin/                    # ADMIN PANEL (Full CMS)
│   ├── login.php             # Admin login
│   ├── index.php             # Dashboard
│   ├── activities.php        # Manage activities
│   ├── team.php              # Manage team members
│   ├── roles.php             # Manage volunteer roles
│   ├── applications.php      # Review applications
│   ├── messages.php          # View messages
│   ├── gallery.php           # Manage gallery
│   ├── settings.php          # Site settings
│   └── logout.php            # Admin logout
├── index.php                 # Homepage
├── about.php                 # About us
├── activities.php            # Activities listing
├── team.php                  # Team members
├── volunteer.php             # Volunteer opportunities
├── apply.php                 # Apply for roles
├── gallery.php               # Photo gallery
├── contact.php               # Contact form
├── signup.php                # User registration
├── login.php                 # User login
├── profile.php               # User profile
├── my_applications.php       # User applications dashboard
├── donate.php                # Donation information
├── faq.php                   # FAQ page
└── logout.php                # User logout
Design Features
Color Scheme:
Primary: #2C5F2D (Forest Green - growth, harmony, Islam)

Secondary: #D4AF37 (Metallic Gold - excellence, value)

Accent: #E8491D (Orange - compassion, energy)

Light Background: #E8F5E9

Dark Text: #333333

Typography:
Headings: Poppins (Modern, clean)

Body Text: Open Sans (Highly readable)

Arabic Support: Available for Islamic content

Features:
Responsive Design: Mobile-friendly layouts

Smooth Animations: CSS transitions and transforms

Interactive Elements: JavaScript for enhanced UX

Photo Gallery: Lightbox functionality

Statistics Counter: Animated numbers on homepage

FAQ Accordion: Expandable question system

Security Features
Password hashing using PHP's password_hash() with bcrypt

Session-based authentication with regeneration

SQL injection prevention via PDO prepared statements

File upload validation (type, size, MIME)

CSRF token protection for forms

Input sanitization and validation

Role-based access control

Secure file storage in uploads directory

Pages Overview
Page	Description	Access Level
Homepage	Welcome with statistics and featured activities	Public
About	Mission, vision, values, and history	Public
Activities	List of humanitarian programs	Public
Team	Association members and leadership	Public
Volunteer	Available volunteer opportunities	Public
Gallery	Photo gallery of events	Public
Contact	Contact form for inquiries	Public
FAQ	Frequently Asked Questions	Public
Donate	Donation information	Public
Signup	User registration	Public
Login	User authentication	Public
Apply	Volunteer application form	Authenticated
My Applications	User application dashboard	Authenticated
Profile	User settings management	Authenticated
Admin Dashboard	Complete CMS for admins	Admin only
How to Use
For Visitors
Browse Activities - See our humanitarian programs

View Team - Meet our association members

Explore Gallery - View photos from events

Read FAQ - Find answers to common questions

Contact Us - Send inquiries through contact form

Learn About Donations - See how to support us

For Volunteers
Sign Up - Create an account through registration

Login - Access your volunteer account

Browse Opportunities - View available volunteer roles

Apply - Submit application for preferred position

Track Application - Check status in "My Applications"

Update Profile - Keep your information current

For Admins
Login - Use admin credentials at /admin/login.php

Dashboard - View platform statistics

Manage Content - Add/edit activities, team info, gallery

Review Applications - Process volunteer requests

Update Settings - Configure website information

Monitor Activity - Track user engagement and donations

Adding Team Photos
To add photos for team members:

Login to Admin Panel (/admin/login.php)

Navigate to Team Management (/admin/team.php)

Edit any team member

Click "Choose File" under photo field

Select photo from your computer

Click "Save Changes"

Photo Requirements:

Format: JPG, PNG, or WEBP

Size: Maximum 5MB

Dimensions: 500x500 pixels recommended

Storage: uploads/members/ directory

Contributing
To contribute to AR-RAHMA website:

Test all features thoroughly before deployment

Maintain code consistency and documentation

Respect user privacy and data security

Follow Islamic principles in content creation

Document all changes clearly

Test on multiple devices and browsers

Support
For technical support or questions:

Developer: Ngoila Karimou Abdoul Akeem

Course: Web Technologies 2025A

University: University of Niamey, Niger

Check FAQ page within the website first

Contact form for association-related inquiries

License
This project is created for educational purposes as part of the Web Technologies 2025A course at University of Niamey. All rights reserved by the developer and AR-RAHMA Humanitarian Association.

Future Enhancements
Online donation portal with payment gateway

Event registration system

Multi-language support (French, Arabic)

Mobile app development

Email newsletter system

SMS notifications for urgent needs

Volunteer certificate generation

Advanced analytics dashboard

Content filtering and moderation

Integration with social media platforms

Impact reporting system

Calendar of events

Acknowledgments
Built with compassion for community service and humanitarian work. Inspired by Islamic principles of charity (Zakat, Sadaqah) and community support.

Remember: Every small act of kindness creates ripples of positive change in our community.

Homepage
The landing page showcases AR-RAHMA's mission with statistics, featured activities, and clear calls-to-action for volunteering and donations.

Activities Page
Users can browse 37+ humanitarian programs with filtering options, detailed descriptions, and photo galleries for each event.

Team Page
Displays association leadership with photos, positions, and brief bios. Admin can upload and manage team member photos.

Volunteer Page
Lists available volunteer opportunities with requirements, duration, and application process. Users can apply directly through the website.

Gallery Page
Photo gallery showcasing community events, program activities, and impact stories with lightbox viewing functionality.

Admin Dashboard
Complete content management system for association admins to manage all aspects of the website, review applications, and track platform statistics.