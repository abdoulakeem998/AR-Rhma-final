# AR-RAHMA WEBSITE - QUICK START GUIDE

## 🚀 QUICK INSTALLATION (5 Minutes)

### Step 1: Prerequisites
- ✅ XAMPP installed (Apache + MySQL)
- ✅ PHP 7.4 or higher
- ✅ Modern web browser

### Step 2: Setup Files
1. Copy the `ar-rahma-website` folder to your XAMPP `htdocs` directory
   - Windows: `C:\xampp\htdocs\ar-rahma-website\`
   - Mac: `/Applications/XAMPP/htdocs/ar-rahma-website/`
   - Linux: `/opt/lampp/htdocs/ar-rahma-website/`

### Step 3: Start XAMPP Services
1. Open XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**

### Step 4: Import Database
**Method 1: Using phpMyAdmin (Recommended)**
1. Open browser: `http://localhost/phpmyadmin`
2. Click "Import" tab
3. Click "Choose File"
4. Select: `ar-rahma-website/config/database.sql`
5. Click "Go" button
6. Wait for success message ✅

**Method 2: Using Command Line**
```bash
mysql -u root -p < config/database.sql
# Press Enter when asked for password (default is empty)
```

### Step 5: Verify Installation
Open your browser and visit:
- **Main Website:** `http://localhost/ar-rahma-website/`
- **Admin Panel:** `http://localhost/ar-rahma-website/admin/`

### Step 6: Login to Admin Panel
**Default Admin Credentials:**
- **Username:** `admin`
- **Password:** `Admin@123`

⚠️ **IMPORTANT:** Change this password immediately after first login!

---

## 📋 POST-INSTALLATION CHECKLIST

### Immediate Actions:
- [ ] Change admin password
- [ ] Update site settings (Site name, email, phone, address)
- [ ] Add your organization logo
- [ ] Create first activity
- [ ] Add team members
- [ ] Test user registration
- [ ] Test volunteer application flow

### Configuration:
- [ ] Update database credentials if needed (config/database.php)
- [ ] Set file upload permissions (uploads folder)
- [ ] Configure social media links
- [ ] Review and customize FAQ content
- [ ] Test email functionality

---

## 🎯 FIRST STEPS AFTER INSTALLATION

### 1. Admin Panel Setup
```
1. Login to admin panel
2. Go to Settings → Update all information
3. Go to Team → Add your team members
4. Go to Activities → Add your first activity
5. Go to Roles → Create volunteer opportunities
```

### 2. Test User Flow
```
1. Open main website in incognito window
2. Register as a new user
3. Login with your test account
4. Browse volunteer opportunities
5. Submit an application
6. Check application status
```

### 3. Admin Review Flow
```
1. Login to admin panel
2. Go to Applications → Review pending applications
3. Accept or reject test application
4. Verify email notifications (if configured)
```

---

## 🔧 COMMON ISSUES & SOLUTIONS

### Issue 1: "Database connection failed"
**Solution:**
- Make sure MySQL is running in XAMPP
- Check database credentials in `config/database.php`
- Verify database was imported successfully

### Issue 2: "Cannot upload files"
**Solution:**
```bash
# Linux/Mac - Set permissions
chmod -R 755 uploads/

# Windows - Right-click uploads folder
# Properties → Security → Edit → Add write permissions
```

### Issue 3: "Page not found" or 404 errors
**Solution:**
- Verify files are in correct location (htdocs/ar-rahma-website/)
- Check Apache is running
- Clear browser cache
- Verify .htaccess file exists (if using URL rewriting)

### Issue 4: "Session errors"
**Solution:**
- Check PHP session configuration
- Clear browser cookies
- Restart Apache server

### Issue 5: "Admin login not working"
**Solution:**
- Verify database was imported correctly
- Check if admin user exists:
```sql
SELECT * FROM admins WHERE username = 'admin';
```
- Reset password if needed (see README.md)

---

## 📝 QUICK CUSTOMIZATION

### Change Colors:
Edit `css/style.css` - Look for these variables:
```css
:root {
    --primary-color: #2C5F2D;     /* Change this */
    --secondary-color: #D4AF37;   /* Change this */
    --accent-color: #E8491D;      /* Change this */
}
```

### Update Logo:
1. Place your logo: `images/logo.png`
2. Edit `includes/header.php`
3. Replace text logo with image

### Site Information:
Admin Panel → Settings → Update:
- Site Name
- Contact Email
- Phone Number
- Address
- Social Media URLs

---

## 🔐 SECURITY CHECKLIST

Before going live:
- [ ] Change all default passwords
- [ ] Enable HTTPS (SSL Certificate)
- [ ] Update database user (don't use root)
- [ ] Set proper file permissions
- [ ] Enable error logging (disable display_errors)
- [ ] Regular backups configured
- [ ] Update PHP to latest version
- [ ] Remove database.sql from public folder

---

## 📞 NEED HELP?

### Resources:
- **Full Documentation:** See `README.md`
- **Database Schema:** See `config/database.sql`
- **Admin Guide:** See admin panel documentation

### Support:
- **Email:** admin@ar-rahma.org
- **Check:** Database connection in `config/database.php`
- **Verify:** Apache and MySQL are running

---

## ✅ SUCCESS INDICATORS

You've successfully installed AR-Rahma website if:
- ✅ Homepage loads without errors
- ✅ You can login to admin panel
- ✅ You can create an activity in admin panel
- ✅ You can register as a user on main site
- ✅ Navigation works on all pages
- ✅ Images upload successfully

---

## 🎉 YOU'RE ALL SET!

Congratulations! Your AR-Rahma website is now ready to use.

**Next Steps:**
1. Customize content in admin panel
2. Add your activities and team members
3. Test all features thoroughly
4. Launch your website! 🚀

**Remember:**
- Keep regular backups
- Monitor application submissions
- Update content regularly
- Engage with your community

---

**May your humanitarian work bring blessings and positive change! 🤲**

For detailed information, please refer to the complete `README.md` file.
