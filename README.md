# AR-RAHMA HUMANITARIAN WEBSITE - COMPLETE PROJECT DOCUMENTATION

## PROJECT OVERVIEW

AR-Rahma is a comprehensive, full-featured website for a non-profit humanitarian association based in Niamey, Niger. The website serves as a central digital hub for the organization, featuring:

- **Public-facing pages** for showcasing activities, team members, and organizational information
- **User registration and authentication** system for volunteer applications
- **Admin panel (CMS)** for complete content management
- **Application tracking system** for monitoring volunteer applications
- **Donation system** integration ready
- **News and gallery** management
- **Contact forms and feedback** system

---

## FOLDER STRUCTURE

```
ar-rahma-website/
│
├── config/
│   ├── database.php          # Database connection configuration
│   └── database.sql           # Complete database schema with tables
│
├── php/
│   ├── functions.php          # Helper functions (authentication, validation, etc.)
│   ├── get_activity.php       # AJAX endpoint for activity details
│   └── [other PHP handlers]
│
├── css/
│   └── style.css              # Main stylesheet with consistent theme
│
├── js/
│   └── main.js                # Main JavaScript file for interactions
│
├── images/
│   └── [image files]
│
├── uploads/
│   ├── activities/
│   ├── members/
│   └── gallery/
│
├── includes/
│   ├── header.php             # Navigation header
│   └── footer.php             # Footer with contact info
│
├── admin/                     # Admin Panel (CMS)
│   ├── index.php              # Admin dashboard
│   ├── login.php              # Admin login
│   ├── activities.php         # Manage activities
│   ├── team.php               # Manage team members
│   ├── roles.php              # Manage open roles
│   ├── applications.php       # Review applications
│   └── [other admin pages]
│
├── index.php                  # Homepage
├── about.php                  # About page
├── activities.php             # Activities listing
├── team.php                   # Team members page
├── volunteer.php              # Volunteer opportunities
├── gallery.php                # Photo gallery
├── contact.php                # Contact form
├── register.php               # User registration
├── login.php                  # User login
├── logout.php                 # Logout handler
├── my_applications.php        # User's application dashboard
└── [other pages]
```

---

## DATABASE SETUP

### Step 1: Install XAMPP
1. Download and install XAMPP
2. Start Apache and MySQL services

### Step 2: Import Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click on "Import" tab
3. Choose file: `config/database.sql`
4. Click "Go" to import

**OR**

Run these commands in MySQL:
```sql
source /path/to/config/database.sql
```

### Step 3: Configure Database Connection
Open `config/database.php` and update if needed:
```php
$server = "localhost";
$user = "root";
$password = "";  // Default XAMPP password is empty
$database = "ar_rahma_db";
```

---

## DATABASE SCHEMA

### Main Tables:

1. **users** - Registered users (volunteers)
2. **admins** - Admin users for CMS
3. **activities** - Organization activities
4. **team_members** - Team/staff members
5. **open_roles** - Volunteer opportunities
6. **applications** - User applications for roles
7. **news** - News and announcements
8. **gallery** - Photo gallery
9. **faqs** - Frequently Asked Questions
10. **donations** - Donation records
11. **contact_messages** - Contact form submissions
12. **feedbacks** - User feedback
13. **site_settings** - Website settings
14. **activity_logs** - Admin action logs
15. **password_resets** - Password reset tokens

---

## KEY FEATURES

### PUBLIC FEATURES:
- ✅ Homepage with hero section and statistics
- ✅ About page with mission and vision
- ✅ Activities listing with filtering
- ✅ Team members showcase
- ✅ Volunteer opportunities listing
- ✅ Photo gallery
- ✅ News and updates
- ✅ Contact form
- ✅ FAQ page
- ✅ User registration and login
- ✅ Application tracking for users

### USER FEATURES (After Login):
- ✅ Apply for volunteer positions
- ✅ Track application status
- ✅ View application history
- ✅ Update profile
- ✅ Submit feedback

### ADMIN FEATURES (CMS):
- ✅ Complete content management
- ✅ Add/edit/delete activities
- ✅ Manage team members
- ✅ Create and manage volunteer roles
- ✅ Review and approve/reject applications
- ✅ Publish news and updates
- ✅ Manage gallery images
- ✅ View and respond to contact messages
- ✅ View user feedback
- ✅ Manage site settings
- ✅ View activity logs
- ✅ Dashboard with statistics

---

## INSTALLATION INSTRUCTIONS

### 1. Setup Files
```bash
# Copy all files to XAMPP htdocs folder
# Example: C:\xampp\htdocs\ar-rahma-website\
```

### 2. Database Setup
```bash
# Import database.sql file via phpMyAdmin
# Or use command line:
mysql -u root -p < config/database.sql
```

### 3. Configure Permissions
```bash
# Make uploads folder writable (Linux/Mac)
chmod -R 755 uploads/

# For Windows, ensure IIS_USERS has write permissions
```

### 4. Update Default Admin Password
The default admin credentials are:
- **Username:** admin
- **Email:** admin@ar-rahma.org
- **Password:** Admin@123

**IMPORTANT:** Change these immediately after first login!

To create new hashed password:
```php
<?php
echo password_hash('YourNewPassword', PASSWORD_DEFAULT);
?>
```

Then update in database:
```sql
UPDATE admins SET password_hash = 'NEW_HASH_HERE' WHERE username = 'admin';
```

### 5. Access the Website
- **Main Website:** http://localhost/ar-rahma-website/
- **Admin Panel:** http://localhost/ar-rahma-website/admin/

---

## USAGE GUIDE

### For Users:
1. Visit the website homepage
2. Register for an account (register.php)
3. Login with your credentials
4. Browse volunteer opportunities
5. Apply for positions
6. Track your applications in "My Applications"

### For Admins:
1. Login to admin panel (admin/login.php)
2. **Dashboard:** View statistics and recent activities
3. **Activities:** Add new activities, edit or delete existing ones
4. **Team Members:** Manage organization team members
5. **Open Roles:** Create volunteer opportunities
6. **Applications:** Review and manage user applications
7. **News:** Publish news and announcements
8. **Gallery:** Upload and organize photos
9. **Messages:** View and respond to contact forms
10. **Settings:** Update site configuration

---

## CUSTOMIZATION

### Colors and Theme
Edit `css/style.css` - look for CSS variables:
```css
:root {
    --primary-color: #2C5F2D;     /* Deep Green */
    --secondary-color: #D4AF37;   /* Gold */
    --accent-color: #E8491D;      /* Orange */
    /* Modify these to change the color scheme */
}
```

### Logo and Branding
- Place logo in `images/logo.png`
- Update navbar brand in `includes/header.php`

### Site Settings
Admin panel → Settings → Update:
- Site name
- Contact information
- Social media links
- Feature toggles

---

## SECURITY CONSIDERATIONS

1. **Change default admin password immediately**
2. **Use HTTPS in production** (SSL certificate)
3. **Update database credentials** for production
4. **Validate and sanitize all inputs** (already implemented)
5. **Regular backups** of database and uploads folder
6. **Keep software updated** (PHP, MySQL, libraries)
7. **Restrict admin panel** access via .htaccess (production)
8. **Use environment variables** for sensitive data (production)

---

## FILE UPLOAD GUIDELINES

### Supported Formats:
- **Images:** JPG, JPEG, PNG, GIF, WEBP
- **Documents:** PDF
- **Maximum size:** 5MB per file

### Upload Locations:
- Activity images: `uploads/activities/`
- Team member photos: `uploads/members/`
- Gallery images: `uploads/gallery/`

---

## TROUBLESHOOTING

### Database Connection Error:
- Check MySQL service is running
- Verify credentials in `config/database.php`
- Ensure database exists (import database.sql)

### File Upload Issues:
- Check folder permissions (755 or 777)
- Verify PHP upload settings in php.ini
- Check max file size limits

### Session Issues:
- Ensure session_start() is called
- Check PHP session configuration
- Clear browser cookies/cache

### Admin Can't Login:
- Verify admin exists in database
- Reset password using SQL command
- Check admin status is 'active'

---

## FUTURE ENHANCEMENTS

### Potential Features to Add:
- Payment gateway integration for donations
- Email notifications for applications
- SMS notifications
- Multi-language support (French, local languages)
- Advanced analytics dashboard
- Blog section
- Event calendar
- Newsletter subscription
- Social media integration
- PDF certificate generation for volunteers
- Mobile app (Progressive Web App)

---

## SUPPORT AND MAINTENANCE

### Regular Maintenance Tasks:
1. **Weekly:** Backup database
2. **Monthly:** Review and archive old data
3. **Quarterly:** Update dependencies
4. **Annually:** Security audit

### Backup Procedure:
```bash
# Database backup
mysqldump -u root -p ar_rahma_db > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf backup_files_$(date +%Y%m%d).tar.gz uploads/
```

---

## CREDITS AND LICENSE

**Developed for:** AR-Rahma Humanitarian Association
**Location:** Niamey, Niger
**Technology Stack:**
- PHP 7.4+
- MySQL 5.7+
- Bootstrap 5.3
- JavaScript (ES6+)
- HTML5, CSS3

**License:** This project is proprietary to AR-Rahma organization.

---

## CONTACT

For technical support or questions about this project:
- **Email:** admin@ar-rahma.org
- **Website:** [Your website URL]

---

## VERSION HISTORY

**Version 1.0.0** (December 2025)
- Initial release
- Complete CMS implementation
- User registration and application system
- Admin dashboard
- Responsive design
- All core features implemented

---

## THANK YOU!

Thank you for using the AR-Rahma website system. This platform is designed to help your humanitarian work reach more people and make a greater impact in the community.

For Allah's sake, may your good deeds be accepted and multiplied. 🤲

---

**IMPORTANT NEXT STEPS AFTER INSTALLATION:**
1. Import database.sql
2. Change default admin password
3. Update site settings in admin panel
4. Add your first activity
5. Add team members
6. Test the complete workflow
7. Launch! 🚀
