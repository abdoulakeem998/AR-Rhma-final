# AR-RAHMA Humanitarian Association Website

**Live Demo:** [http://169.239.251.102:341/~ngoila.karimou/uploads/AR-Rhma-final/index.php](http://169.239.251.102:341/~ngoila.karimou/uploads/AR-Rhma-final/index.php)

---

##  About This Project

I developed this website for AR-RAHMA, a humanitarian association based in Niamey, Niger. The organization is dedicated to alleviating poverty and bringing joy to orphans, people with disabilities, and low-income families through community support programs grounded in Islamic principles.

Founded in December 2022, AR-RAHMA creates a supportive community where compassion meets action, and every contribution makes a difference. This web platform serves as their digital presence and volunteer management system.

---

##  Key Features I Built

### For All Users
- **Activities Gallery** - Browse 37+ humanitarian programs with photos and details
- **Team Showcase** - Meet the dedicated association members
- **Photo Gallery** - View images from community events
- **FAQ Section** - Get answers to common questions about the work
- **Contact Form** - Easy way to reach the team
- **Donation Information** - Learn how to support the mission

### For Registered Volunteers
- **Volunteer Application System** - Apply for specific humanitarian roles
- **Application Tracking** - Real-time status updates
- **Personal Dashboard** - Manage applications and profile
- **Profile Management** - Update personal information
- **Anonymous Feedback Option** - Share thoughts privately if preferred

### For Association Admins
- **Complete CMS** - Manage all website content through admin panel
- **Activity Management** - Add, edit, and organize humanitarian programs
- **Team Management** - Update member information and photos
- **Application Review System** - Process volunteer applications
- **Statistics Dashboard** - View platform analytics and impact metrics
- **Content Management** - Update site settings, FAQs, and resources

---

##  Technologies I Used

- **Backend:** PHP 8.3
- **Database:** MySQL 8.0
- **Frontend:** HTML5, CSS3, JavaScript ES6+
- **AJAX:** For real-time interactions without page reload
- **Framework:** Bootstrap 5.3 for responsive design
- **Icons:** Bootstrap Icons 1.10
- **Fonts:** Segoe UI (Primary), Georgia (Secondary)
- **Charts:** Chart.js for data visualization
- **Email:** PHPMailer for notifications

---

##  Database Schema

I designed the following tables to support the platform:

- **USERS** - User accounts with role management (admin, volunteer, donor)
- **TEAM_MEMBERS** - Association leadership and staff information
- **ACTIVITIES** - Humanitarian programs and events
- **VOLUNTEER_ROLES** - Available volunteer positions
- **APPLICATIONS** - Volunteer applications with status tracking
- **DONATIONS** - Contribution records and tracking
- **CONTACT_MESSAGES** - Inquiry and communication management
- **GALLERY** - Photo and media content
- **SITE_SETTINGS** - Website configuration and content

---

##  Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server

### Setup Steps

1. **Upload Project Files**
   ```bash
   # Copy all files to your server directory
   /~ngoila.karimou/uploads/AR-Rhma-final/
   ```

2. **Create Database**
   ```sql
   CREATE DATABASE webtech_2025A_ngoila_karimou;
   USE webtech_2025A_ngoila_karimou;
   -- Import the schema from config/database.sql
   ```

3. **Configure Database Connection**
   
   Edit `config/database.php`:
   ```php
   $host = 'localhost';
   $dbname = 'webtech_2025A_ngoila_karimou';
   $username = 'ngoila.karimou';
   $password = 'Joker99@';
   ```

4. **Set Upload Directory Permissions**
   ```bash
   mkdir -p uploads/activities uploads/members uploads/gallery uploads/documents
   chmod 755 uploads/
   chmod 755 uploads/*/
   ```

5. **Access the Application**
   
   Navigate to: `http://169.239.251.102:341/~ngoila.karimou/uploads/AR-Rhma-final/index.php`

---

## User Roles & Access

### Regular Visitor
- Browse activities and gallery
- View team information
- Read FAQs and resources
- Submit contact inquiries
- Access donation information

### Registered Volunteer
- All visitor features
- Apply for volunteer roles
- Track application status
- Update personal profile
- View application history

### Association Admin
- All user features
- Complete content management
- Review volunteer applications
- Manage team members
- Update site settings
- View analytics dashboard

---

## Default Credentials

**Admin Account:**
- UserName: `admin`
- Password: `password`
- Access: `http://169.239.251.102:341/~ngoila.karimou/uploads/AR-Rhma-final/admin/login.php`



**New Volunteers:**
- Register through the signup page
- No special codes required

---

## 📁 Project Structure

```
AR-Rhma-final/
├── config/
│   └── database.php              # Database configuration
├── includes/
│   ├── header.php                # Site header
│   ├── footer.php                # Site footer
│   └── functions.php             # Helper functions
├── assets/
│   ├── css/
│   │   └── style.css             # Main stylesheet
│   ├── js/
│   │   └── main.js               # JavaScript functionality
│   └── images/                   # Static images
├── uploads/                      # User uploads directory
│   ├── activities/               # Activity photos
│   ├── members/                  # Team member photos
│   ├── gallery/                  # Gallery images
│   └── documents/                # Application documents
├── admin/                        # Admin Panel (CMS)
│   ├── login.php                 # Admin login
│   ├── index.php                 # Dashboard
│   ├── activities.php            # Manage activities
│   ├── team.php                  # Manage team members
│   ├── roles.php                 # Manage volunteer roles
│   ├── applications.php          # Review applications
│   ├── messages.php              # View contact messages
│   ├── gallery.php               # Manage gallery
│   ├── settings.php              # Site settings
│   └── logout.php                # Admin logout
├── index.php                     # Homepage
├── about.php                     # About us page
├── activities.php                # Activities listing
├── team.php                      # Team members page
├── volunteer.php                 # Volunteer opportunities
├── apply.php                     # Application form
├── gallery.php                   # Photo gallery
├── contact.php                   # Contact form
├── signup.php                    # User registration
├── login.php                     # User login
├── profile.php                   # User profile
├── my_applications.php           # Applications dashboard
├── donate.php                    # Donation information
├── faq.php                       # FAQ page
└── logout.php                    # User logout
```

---

##  Design System

### Color Scheme
I chose these colors to reflect the organization's values:

- **Primary:** `#2C5F2D` (Forest Green - growth, harmony, Islam)
- **Secondary:** `#D4AF37` (Metallic Gold - excellence, value)
- **Accent:** `#E8491D` (Orange - compassion, energy)
- **Light Background:** `#F8F9FA`
- **Dark Text:** `#1A1A1A`

### Typography
- **Headings:** Segoe UI (Modern, clean)
- **Body Text:** Segoe UI (Highly readable)
- **Arabic Support:** Available for Islamic content

### Features I Implemented
- ✅ Responsive design for all devices
- ✅ Smooth CSS animations and transitions
- ✅ Interactive JavaScript elements
- ✅ Photo gallery with lightbox functionality
- ✅ Animated statistics counter on homepage
- ✅ FAQ accordion system
- ✅ Form validation

---

## 🔒 Security Features I Implemented

- Password hashing using PHP's `password_hash()` with bcrypt
- Session-based authentication with regeneration
- SQL injection prevention via PDO prepared statements
- File upload validation (type, size, MIME)
- CSRF token protection for forms
- Input sanitization and validation
- Role-based access control
- Secure file storage in uploads directory

---

## 📄 Pages Overview

| Page | Description | Access Level |
|------|-------------|--------------|
| Homepage | Welcome with statistics and featured activities | Public |
| About | Mission, vision, values, and history | Public |
| Activities | List of humanitarian programs | Public |
| Team | Association members and leadership | Public |
| Volunteer | Available volunteer opportunities | Public |
| Gallery | Photo gallery of events | Public |
| Contact | Contact form for inquiries | Public |
| FAQ | Frequently Asked Questions | Public |
| Donate | Donation information | Public |
| Signup | User registration | Public |
| Login | User authentication | Public |
| Apply | Volunteer application form | Authenticated |
| My Applications | Application dashboard | Authenticated |
| Profile | User settings management | Authenticated |
| Admin Dashboard | Complete CMS | Admin only |

---

## How to Use

### For Visitors
1. Browse the **Activities** page to see humanitarian programs
2. Visit the **Team** page to meet association members
3. Explore the **Gallery** to view photos from events
4. Read the **FAQ** for common questions
5. Use the **Contact** form to reach out
6. Learn about **Donations** and how to support

### For Volunteers
1. **Sign Up** - Create an account through registration
2. **Login** - Access your volunteer account
3. **Browse Opportunities** - View available volunteer roles
4. **Apply** - Submit application for your preferred position
5. **Track Application** - Check status in "My Applications"
6. **Update Profile** - Keep your information current

### For Admins
1. **Login** - Use admin credentials at `http://169.239.251.102:341/~ngoila.karimou/uploads/AR-Rhma-final/admin/login.php`
2. **Dashboard** - View platform statistics
3. **Manage Content** - Add/edit activities, team info, gallery
4. **Review Applications** - Process volunteer requests
5. **Update Settings** - Configure website information
6. **Monitor Activity** - Track user engagement

---

## 📸 Managing Team Photos

To add or update team member photos:

1. Login to Admin Panel (`http://169.239.251.102:341/~ngoila.karimou/uploads/AR-Rhma-final/admin/login.php`)
2. Navigate to Team Management (`http://169.239.251.102:341/~ngoila.karimou/uploads/AR-Rhma-final/admin/team.php`)
3. Edit any team member
4. Click "Choose File" under photo field
5. Select photo from your computer
6. Click "Save Changes"

**Photo Requirements:**
- Format: JPG, PNG, or WEBP
- Size: Maximum 5MB
- Dimensions: 500x500 pixels recommended
- Storage: `uploads/members/` directory

---

## 🌟 Future Enhancements I'm Planning

- [ ] Online donation portal with payment gateway
- [ ] Event registration system
- [ ] Multi-language support (French, Arabic)
- [ ] Mobile app development
- [ ] Email newsletter system
- [ ] SMS notifications for urgent needs
- [ ] Volunteer certificate generation
- [ ] Advanced analytics dashboard
- [ ] Content moderation tools
- [ ] Social media integration
- [ ] Impact reporting system
- [ ] Events calendar

---

## 🤝 Contributing

If you'd like to contribute to this project:

1. Test all features thoroughly before deployment
2. Maintain code consistency and documentation
3. Respect user privacy and data security
4. Follow Islamic principles in content creation
5. Document all changes clearly
6. Test on multiple devices and browsers

---

## 💬 Support

For technical support or questions:

- **Developer:** Ngoila Karimou Abdoul Akeem
- **Course:** Web Technologies 2025A
- **University:** University of Niamey, Niger

Please check the FAQ page within the website first for common questions. For association-related inquiries, use the contact form.

---

## 📜 License

This project was created for educational purposes as part of the Web Technologies 2025A course at the University of Niamey. All rights reserved by the developer and AR-RAHMA Humanitarian Association.

---

## 🙏 Acknowledgments

I built this website with compassion for community service and humanitarian work. The project is inspired by Islamic principles of charity (Zakat, Sadaqah) and community support.

> *"Remember: Every small act of kindness creates ripples of positive change in our community."*

---

## 📊 Project Statistics

- **37+ Activities** documented and showcased
- **Complete CMS** for easy content management
- **3 User Roles** with different access levels
- **9 Database Tables** for efficient data management
- **20+ Pages** covering all aspects of the organization
- **Fully Responsive** design for all devices
- **Secure** authentication and data handling

---

**Built with ❤️ for AR-RAHMA Humanitarian Association**

*Making a difference, one line of code at a time.*