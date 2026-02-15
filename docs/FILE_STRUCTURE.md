# Project File Structure

## 📁 Main Files

### Configuration & Core
- `config.php` - Database configuration (DB: om, Host: localhost, User: root)
- `db_connection.php` - Database connection and helper functions
- `auth.php` - Authentication and session management

### Database Files
- `database.sql` - Complete SQL schema with sample data (IMPORT THIS!)
- `setup.php` - Auto-setup script (creates database automatically)
- `test_connection.php` - Connection tester (use this to diagnose issues)

### Public Pages
- `index.php` - Home page (start here)
- `about.php` - About the platform
- `contact.php` - Contact form

### NGO Features
- `ngos.php` - List of all NGOs
- `ngo_detail.php` - Individual NGO details
- `projects.php` - Projects listing
- `donate.php` - Donation form

### User Management
- `register.php` - New user registration
- `login.php` - User login
- `logout.php` - User logout

### Volunteer System
- `volunteers.php` - Volunteer registration and opportunities

### E-Commerce
- `products.php` - Product catalog
- `cart.php` - Shopping cart
- `add_to_cart.php` - Add to cart handler
- `checkout.php` - Checkout process
- `order_confirmation.php` - Order confirmation

### Styling
- `css/style.css` - Custom CSS styles

### Documentation
- `docs/README.md` - Project overview and features
- `docs/START_HERE.md` - Setup and troubleshooting guide ⭐ READ THIS FIRST
- `docs/INSTALLATION.md` - Detailed installation instructions
- `docs/QUICKFIX.md` - Quick troubleshooting guide
- `docs/QUICKSTART.md` - Quick reference guide

### Helper Scripts
- `docs/check_xampp.bat` - Check if XAMPP services are running

---

## 🗄️ Database Structure

### Database: `om`

**Tables:**
1. **users** - User accounts (donors, volunteers, NGOs, admins)
2. **ngos** - NGO organizations with contact info
3. **projects** - NGO projects with funding goals
4. **donations** - Donation transaction records
5. **volunteers** - Volunteer profiles and details
6. **products** - Product catalog for e-commerce
7. **cart** - Shopping cart items
8. **orders** - Order records
9. **order_items** - Individual items in orders
10. **contact_messages** - Contact form submissions

---

## 🔄 How Files Work Together

### User Registration Flow
1. `register.php` → Creates user in `users` table
2. `login.php` → Validates credentials and creates session
3. `auth.php` → Manages session and authentication
4. `logout.php` → Destroys session

### Donation Flow
1. `ngos.php` → Browse NGOs
2. `ngo_detail.php` → View NGO details
3. `donate.php` → Fill donation form
4. → Saves to `donations` table

### Shopping Flow
1. `products.php` → Browse products
2. `add_to_cart.php` → Add to cart (saves to `cart` table)
3. `cart.php` → View cart and update quantities
4. `checkout.php` → Complete purchase (creates `orders` and `order_items`)
5. `order_confirmation.php` → Show order details

### Volunteer Flow
1. `register.php` → Create user account
2. `volunteers.php` → Fill volunteer application
3. → Saves to `volunteers` table with 'pending' status

---

## 🎨 Page Dependencies

### Every PHP page includes:
- `config.php` - Gets database configuration
- `db_connection.php` - Establishes database connection

### Protected pages also include:
- `auth.php` - Ensures user is logged in

---

## 🔧 Configuration Files

### config.php
```php
DB_HOST = 'localhost'
DB_USER = 'root'
DB_PASS = ''
DB_NAME = 'om'
SITE_URL = 'http://localhost/IndianNGOWebsite/'
```

---

## 📊 Sample Data Included

When you run `setup.php` or import `database.sql`:

**NGOs:** 5 sample organizations
- Helping Hands NGO (Education)
- Rural India Foundation (Rural Development)
- Women Empowerment Trust (Women Empowerment)
- Healthcare for All (Healthcare)
- Green Earth Initiative (Environment)

**Projects:** 6 ongoing projects linked to NGOs

**Products:** 8 items in the shop
- T-Shirts, Caps, Mugs
- Books, Notebooks, Pens
- Tote Bags, Water Bottles

**Users:** 1 admin account
- Email: admin@ngo.com
- Password: admin123

---

## 🚀 Files to Run First

1. **test_connection.php** - Check if everything is configured
2. **setup.php** - Create database and tables
3. **index.php** - Access the website

---

## ⚠️ Important Notes

- **Delete setup.php** after running it (security)
- **Change admin password** after first login
- **Keep config.php secure** - don't expose publicly
- All passwords in `users` table are hashed with bcrypt

---

## 🔍 File Sizes

- `database.sql` - Complete schema (~15KB)
- `setup.php` - Auto-setup script (~8KB)
- `config.php` - Configuration (~0.5KB)
- `db_connection.php` - Connection functions (~2KB)
- `css/style.css` - Styling (~variable size)

---

## 📝 Adding New Pages

To add a new page:
1. Create `newpage.php`
2. Include at top:
   ```php
   <?php
   require_once 'config.php';
   require_once 'db_connection.php';
   require_once 'auth.php'; // if login required
   ```
3. Add to navigation in other pages

---

## 🔐 Security Features

- Password hashing (bcrypt)
- SQL injection prevention (prepared statements)
- Session management
- Input sanitization
- CSRF protection recommended for production

---

**For detailed setup instructions, see [START_HERE.md](START_HERE.md)**
