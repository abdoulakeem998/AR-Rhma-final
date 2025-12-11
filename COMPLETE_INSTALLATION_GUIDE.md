# AR-RAHMA WEBSITE - COMPLETE INSTALLATION GUIDE

## 📦 WHAT YOU'RE GETTING

A COMPLETE, professional humanitarian organization website with:

### ✅ USER FEATURES:
- Homepage with statistics
- About Us page
- Activities listing
- Team members page
- Volunteer opportunities
- Gallery
- Contact form
- FAQ page
- Donation information
- User signup/login system
- Application system for volunteer roles
- User profile management
- Application tracking

### ✅ ADMIN PANEL (CMS):
- Secure admin login
- Dashboard with statistics
- Manage Activities (Create, Edit, Delete)
- Manage Team Members
- Manage Volunteer Roles
- Review Applications
- View Contact Messages
- Manage Gallery
- Update Site Settings
- Activity Logs

### ✅ DATABASE:
- 15 interconnected tables
- Sample data included
- Proper relationships and indexes

---

## 🚀 INSTALLATION STEPS

### STEP 1: Install XAMPP
1. Download XAMPP from https://www.apachefriends.org
2. Install XAMPP
3. Start Apache and MySQL services

### STEP 2: Copy Files
1. Copy the `ar-rahma-website` folder to:
   - Windows: `C:\xampp\htdocs\`
   - Mac: `/Applications/XAMPP/htdocs/`
   - Linux: `/opt/lampp/htdocs/`

### STEP 3: Import Database
1. Open browser: `http://localhost/phpmyadmin`
2. Click "Import" tab
3. Click "Choose File"
4. Select: `ar-rahma-website/config/database.sql`
5. Click "Go"
6. Wait for success message ✅

### STEP 4: Access the Website
**Main Website:** `http://localhost/ar-rahma-website/`
**Admin Panel:** `http://localhost/ar-rahma-website/admin/login.php`

**Default Admin Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **CHANGE THIS PASSWORD IMMEDIATELY!**

---

## 📁 COMPLETE FILE STRUCTURE

```
ar-rahma-website/
├── config/
│   ├── database.php              [Database connection]
│   └── database.sql              [Complete database schema]
│
├── includes/
│   ├── functions.php             [All helper functions]
│   ├── header.php                [Navigation header]
│   ├── footer.php                [Footer]
│   └── get_activity.php          [AJAX endpoint]
│
├── assets/
│   ├── css/
│   │   └── style.css             [Complete stylesheet]
│   ├── js/
│   │   └── main.js               [Interactive JavaScript]
│   └── images/                   [Image files]
│
├── uploads/
│   ├── activities/               [Activity images]
│   ├── members/                  [Team photos]
│   ├── gallery/                  [Gallery images]
│   └── documents/                [User documents]
│
├── admin/
│   ├── login.php                 [Admin login]
│   ├── index.php                 [Dashboard]
│   ├── activities.php            [Manage activities]
│   ├── team.php                  [Manage team]
│   ├── roles.php                 [Manage roles]
│   ├── applications.php          [Review applications]
│   ├── messages.php              [Contact messages]
│   ├── gallery.php               [Manage gallery]
│   ├── settings.php              [Site settings]
│   └── logout.php                [Admin logout]
│
├── index.php                     [Homepage]
├── about.php                     [About page]
├── activities.php                [Activities listing]
├── team.php                      [Team members]
├── volunteer.php                 [Volunteer opportunities]
├── gallery.php                   [Photo gallery]
├── contact.php                   [Contact form]
├── faq.php                       [FAQs]
├── donate.php                    [Donation info]
├── signup.php                    [User registration - COMPLETE]
├── login.php                     [User login]
├── logout.php                    [User logout]
├── apply.php                     [Apply for roles]
├── my_applications.php           [User dashboard]
└── profile.php                   [User profile]
```

---

## 🔐 DEFAULT CREDENTIALS

### Admin Account:
- **Username:** admin
- **Password:** admin123
- **Email:** admin@ar-rahma.org

### Test User Account (Create your own):
- Use the signup page to create test accounts

---

## 📝 DATABASE TABLES

1. **users** - Website users (volunteers)
2. **admins** - Admin users for CMS
3. **activities** - Organization activities
4. **team_members** - Team/staff members
5. **open_roles** - Volunteer opportunities
6. **applications** - User applications
7. **news** - News and announcements  
8. **gallery** - Photo gallery
9. **faqs** - Frequently Asked Questions
10. **contact_messages** - Contact form submissions
11. **feedbacks** - User feedback
12. **site_settings** - Website configuration
13. **activity_logs** - Admin action logs
14. **password_resets** - Password reset tokens

---

## 🎯 FIRST STEPS AFTER INSTALLATION

### 1. Login to Admin Panel
```
URL: http://localhost/ar-rahma-website/admin/login.php
Username: admin
Password: admin123
```

### 2. Change Admin Password
- Go to Settings
- Update admin credentials
- Use a strong password!

### 3. Update Site Settings
- Site name
- Contact email
- Phone number
- Address
- Social media links

### 4. Add Content
- Create activities
- Add team members
- Create volunteer roles
- Upload gallery images

### 5. Test User Flow
- Open website in incognito window
- Sign up as a new user
- Browse volunteer opportunities
- Submit an application
- Check admin panel for the application

---

## 🛠️ ADMIN PANEL GUIDE

### Dashboard (index.php)
- View statistics (users, activities, applications)
- Recent activities
- Pending applications

### Manage Activities (activities.php)
- **Create:** Add new activities with photos
- **Edit:** Update activity details
- **Delete:** Remove activities
- **Feature:** Mark activities as featured (shows on homepage)

### Manage Team (team.php)
- Add team members with photos
- Edit member information
- Remove team members
- Set display order

### Manage Roles (roles.php)
- Create volunteer opportunities
- Edit role details
- Close/open positions
- View applicants

### Review Applications (applications.php)
- View all applications
- Accept/reject applications
- Add notes
- Contact applicants

### Contact Messages (messages.php)
- View messages from contact form
- Mark as read/responded
- Add notes

### Gallery (gallery.php)
- Upload images
- Organize photos
- Delete images

### Settings (settings.php)
- Site name and description
- Contact information
- Social media links
- Enable/disable features

---

## 🔧 CUSTOMIZATION GUIDE

### Change Colors
Edit `assets/css/style.css`:
```css
:root {
    --primary-color: #2C5F2D;     /* Change this */
    --secondary-color: #D4AF37;   /* Change this */
    --accent-color: #E8491D;      /* Change this */
}
```

### Add Your Logo
1. Place logo: `assets/images/logo.png`
2. Edit `includes/header.php`
3. Replace text with image:
```php
<img src="assets/images/logo.png" alt="AR-Rahma" height="40">
```

### Update Contact Info
- Login to admin panel
- Go to Settings
- Update all contact information

---

## 🐛 TROUBLESHOOTING

### Issue: Database connection failed
**Solution:**
- Check MySQL is running in XAMPP
- Verify credentials in `config/database.php`
- Ensure database was imported

### Issue: Can't upload files
**Solution:**
```
# Windows: Right-click uploads folder
# Properties → Security → Add write permissions

# Linux/Mac:
chmod -R 755 uploads/
```

### Issue: Admin can't login
**Solution:**
```sql
-- Run in phpMyAdmin:
UPDATE admins SET password_hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE username = 'admin';
-- This resets password to: admin123
```

### Issue: Pages show "404 Not Found"
**Solution:**
- Check files are in correct location
- Verify Apache is running
- Clear browser cache

---

## ✅ TESTING CHECKLIST

- [ ] Homepage loads
- [ ] Can signup as new user
- [ ] Can login
- [ ] Can view activities
- [ ] Can view team members
- [ ] Can view volunteer opportunities
- [ ] Can apply for a role (when logged in)
- [ ] Can view own applications
- [ ] Can update profile
- [ ] Can submit contact form
- [ ] Admin can login
- [ ] Admin can create activity
- [ ] Admin can add team member
- [ ] Admin can create role
- [ ] Admin can review applications
- [ ] Admin can view contact messages

---

## 🚀 GOING LIVE (Production)

Before deploying to production:

1. **Change all passwords**
2. **Enable HTTPS** (SSL certificate)
3. **Update database credentials**
4. **Set proper file permissions**
5. **Remove database.sql** from public access
6. **Enable error logging** (disable display_errors)
7. **Set up backups**
8. **Test everything thoroughly**

---

## 📞 SUPPORT

### Resources:
- Check `README.md` for detailed documentation
- Review `database.sql` for database structure
- Examine code comments for explanations

### Common Questions:
**Q: How do I add more admins?**
A: Insert directly in database or create admin management page

**Q: How do I customize emails?**
A: Edit the `sendEmail()` function in `includes/functions.php`

**Q: Can I add more fields to forms?**
A: Yes, update database table, then update corresponding PHP files

---

## 🎉 YOU'RE ALL SET!

Your AR-Rahma website is ready to use!

**Remember:**
- Keep regular backups
- Monitor applications
- Update content regularly
- Respond to contact messages
- Engage with volunteers

**May your humanitarian work bring blessings! 🤲**

For detailed code documentation, see individual files - all code is well-commented.
