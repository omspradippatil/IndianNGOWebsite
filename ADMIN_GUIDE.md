# Admin Panel User Guide

## Overview
The improved admin panel is an easy-to-use interface for managing all aspects of the Indian NGO Management System.

## Access the Admin Panel
- **URL**: `http://localhost/IndianNGOWebsite/admin.php`
- **Default Admin Credentials**: 
  - Email: `admin@ngo.com`
  - Password: `admin123`

---

## Features & Sections

### 1. **Dashboard** 📊
- **Quick Overview**: See key statistics at a glance
  - Total Users
  - Total Donations (with amount raised)
  - Total Orders (with revenue)
  - Active Projects
  - Active Volunteers
  - Pending Messages

- **Recent Activity Cards**:
  - Recent users joined
  - Recent donations received

---

### 2. **Users Management** 👥
**Location**: Click "Users" in sidebar

**Features**:
- View all registered users (donors, volunteers, NGOs)
- Search users by **name** or **email**
- Filter by user type:
  - All Types
  - Donors
  - Volunteers
  - NGOs
- Delete users (except admin accounts)
- Pagination support (20 users per page)

**How to Use**:
1. Click on "Users" in the sidebar
2. Use the search bar to find specific users
3. Use the filter dropdown to show only certain user types
4. Click "Delete" to remove a user (with confirmation)

---

### 3. **Donations Management** ❤️
**Location**: Click "Donations" in sidebar

**Features**:
- View all donations received
- See donor name, amount, cause, and status
- Search by donor name or cause
- Filter by payment status:
  - Completed
  - Pending
  - Failed
- Delete donation records
- View donation date

**How to Use**:
1. Click on "Donations" in the sidebar
2. View all donations in the table
3. Use search to find specific donations
4. Filter by payment status to focus on pending/failed donations
5. Delete outdated records as needed

---

### 4. **Orders Management** 🛍️
**Location**: Click "Orders" in sidebar

**Features**:
- Track all merchandise orders
- View customer name, email, and order amount
- See both payment & delivery status
- Search by customer name or email
- Filter by:
  - Delivery Status (Pending, Shipped, Delivered)
  - Payment Status (Completed)
- Full order date tracking

**How to Use**:
1. Click on "Orders" in sidebar
2. View pending shipments
3. Filter by delivery status to see what needs to be shipped
4. Track payment completion

---

### 5. **Products Management** 📦
**Location**: Click "Products" in sidebar

**Features**:
- View all merchandise inventory
- Monitor stock levels
- See product pricing
- **Low Stock Alert**: Shows number of items below 10 units
- Categorized products
- Stock status indicator (Green = In Stock, Yellow = Low Stock)

**How to Use**:
1. Click on "Products" in sidebar
2. Review inventory levels
3. Note items with low stock (badge will show "Low Stock")
4. Stock warning: Card header shows total items needing reorder

---

### 6. **Messages Management** 💬
**Location**: Click "Messages" in sidebar

**Features**:
- View contact form submissions
- See sender name, email, and contact info
- Read message subject and preview
- Track message status:
  - **New**: Not yet read (Yellow badge)
  - **Read**: Opened (Gray badge)
  - **Replied**: Response sent (Green badge)
- Delete messages
- View submission date

**How to Use**:
1. Click on "Messages" in sidebar
2. Review pending messages (yellow badges first)
3. Read message preview
4. Delete spam or resolved messages
5. Status indicates whether you've responded

---

### 7. **Volunteers Management** 🤝
**Location**: Click "Volunteers" in sidebar

**Features**:
- View all registered volunteers
- Track volunteer status:
  - Active (Green)
  - Pending (Yellow)
  - Inactive (Gray)
- See hours contributed
- Monitor volunteer since date
- Contact information included

**How to Use**:
1. Click on "Volunteers" in sidebar
2. Check pending volunteers for approval/rejection
3. Monitor active volunteers and contributions
4. Track volunteer engagement by hours

---

## Navigation Tips

### Sidebar Menu
- **Always visible** on desktop
- **Collapsible** on mobile devices
- **Color indication**: Active page highlighted in blue
- **Quick access** to all sections

### Top Bar
- **Current section name** displayed
- Quick link back to main website
- Clean header for each section

### Search & Filter
- **Search box**: Enter text to find specific records
- **Filter dropdown**: Choose by category/status
- **Auto-paginate**: Results split into 20 per page
- **Easy to reset**: Clear search to see all records

---

## Common Tasks

### Delete a User
1. Go to Users section
2. Find the user in the table
3. Click "Delete" button
4. Confirm the deletion
✅ User account removed

### Check Pending Orders
1. Go to Orders section
2. Use Filter dropdown → "Pending"
3. View all orders awaiting shipment
✅ Easy order prioritization

### Monitor Low Stock Products
1. Go to Products section
2. Look for items with "Low Stock" badge
3. Stock count shown in the table
✅ Keep inventory current

### Respond to Messages
1. Go to Messages section
2. Find messages with "New" status (yellow)
3. Read message preview
4. Respond to sender via their email
5. Delete after replying
✅ Stay on top of inquiries

---

## Keyboard Shortcuts & Accessibility

- **Tab navigation**: Navigate through elements
- **Search focus**: Click search field to activate
- **Mobile friendly**: Responsive design adapts to all devices
- **Color contrast**: Good readability for all users

---

## Troubleshooting

### No data showing in a section?
- Check if filters are too restrictive
- Click "All Types" or "All Status" filter
- Reset the search bar (clear and search again)

### Can't delete an admin user?
- System protects all admin accounts
- Only non-admin users can be deleted
- This is a security feature

### Not seeing updates immediately?
- Refresh the page (Ctrl+R or Cmd+R)
- Check filters aren't hiding the data
- Ensure you have permission to view the data

---

## Security Notes

✅ **Protected Access**: Only admins can view this panel
✅ **Data Safety**: Confirmation required for deletions
✅ **Session Security**: Automatic logout for security
✅ **Input Validation**: All data is validated and secure

---

## Contact & Support

For issues with the admin panel:
1. Ensure you're logged in as admin
2. Clear browser cache and reload
3. Check database connection in config.php
4. Review error messages carefully

---

**Last Updated**: February 15, 2026
**Version**: 2.0 - Redesigned Admin Interface
