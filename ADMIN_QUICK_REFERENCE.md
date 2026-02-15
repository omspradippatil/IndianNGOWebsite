# Admin Panel - Quick Reference Card

## Access Points
- **Admin Login**: http://localhost/IndianNGOWebsite/login.php
- **Admin Panel**: http://localhost/IndianNGOWebsite/admin.php
- **Credentials**: admin@ngo.com / admin123

---

## Dashboard Stats (At a Glance)
| Metric | Shows What |
|--------|-----------|
| Total Users | All registered members |
| Total Donations | Completed donations + amount |
| Total Orders | Completed orders + revenue |
| Active Projects | Ongoing projects |
| Active Volunteers | Approved volunteers |
| Pending Messages | New contact form submissions |

---

## Menu Map
```
┌─ Dashboard (📊)
│  ├─ Statistics cards
│  ├─ Recent users
│  └─ Recent donations
│
├─ Users (👥)
│  ├─ Search by name/email
│  ├─ Filter: All/Donor/Volunteer/NGO
│  └─ Delete user
│
├─ Donations (❤️)
│  ├─ Search by name/cause
│  ├─ Filter: Completed/Pending/Failed
│  └─ View amounts & dates
│
├─ Orders (🛍️)
│  ├─ Search customer
│  ├─ Filter: Payment/Delivery status
│  └─ Track shipments
│
├─ Products (📦)
│  ├─ Monitor stock levels
│  ├─ See low stock alerts
│  └─ View pricing
│
├─ Messages (💬)
│  ├─ View submissions
│  ├─ Track status (New/Read/Replied)
│  └─ Delete
│
└─ Volunteers (🤝)
   ├─ Active/Pending/Inactive
   ├─ Hours tracking
   └─ Member since date
```

---

## Common Operations

### Find a Specific User
1. Click **Users** in sidebar
2. Type name/email in search box
3. Click **Search**
4. Result appears immediately

### Check Pending Donations
1. Click **Donations** in sidebar
2. Use Filter → **Pending**
3. You'll see only pending donations
4. Check status & amount

### Manage Orders to Ship
1. Click **Orders** in sidebar
2. Use Filter → **Pending** (delivery)
3. View customer & order details
4. Update status when shipped

### Monitor Inventory
1. Click **Products** in sidebar
2. Look for **Yellow "Low Stock"** badges
3. Card header shows count needing reorder
4. Plan restocking

### Respond to Messages
1. Click **Messages** in sidebar
2. Look for **Yellow "New"** badges first
3. Read message content
4. Email customer to reply
5. Delete after handling

### Review Volunteers
1. Click **Volunteers** in sidebar
2. Check **Yellow "Pending"** status
3. Find contact info
4. Email approval/rejection
5. Mark as Active when approved

---

## Status Color Reference

| Color | Meaning |
|-------|---------|
| 🟢 Green (Success) | Completed, Active, Delivered |
| 🟡 Yellow (Warning) | Pending, Inactive, Low Stock |
| 🔴 Red (Danger) | Failed, Cancelled |
| 🔵 Blue (Info) | User Type, Donor/Volunteer |

---

## Keyboard Shortcuts

| Action | How |
|--------|-----|
| Search | Tab to search box, type query |
| Filter | Click dropdown, select filter |
| Delete | Click delete, confirm popup |
| Next Page | Click page number at bottom |
| Back | Click sidebar menu item |

---

## Safety Tips

✅ Always **confirm deletions**
✅ Don't delete **admin accounts**
✅ Review before deleting **donations**
✅ Save important records elsewhere
✅ Back up database regularly

---

## Troubleshooting Quick Fixes

| Problem | Solution |
|---------|----------|
| No data showing | Clear search box, click "All" filter |
| Can't delete user | User is admin (protected) |
| Page loads slow | Try different filter or search term |
| Data missing | Refresh page (Ctrl+R) |
| Not logged in | Click Website → Login with credentials |

---

## Performance Tips

⚡ **Search frequently used data** (instead of scrolling)
⚡ **Use filters** to narrow results
⚡ **Refresh page** if data seems outdated
⚡ **Close popup dialogs** after actions
⚡ **Clear old records** periodically

---

## Weekly Checklist

- [ ] Review pending messages (Monday)
- [ ] Check low stock products (Monday)
- [ ] Monitor donation progress (Wednesday)
- [ ] Review order shipments (Wednesday)
- [ ] Check volunteer applications (Friday)
- [ ] Generate backup (Friday)
- [ ] Delete spam messages (Friday)

---

## Important Numbers

- **Dashboard Stats**: Updated real-time
- **Pagination**: 20 items per page
- **Pending Messages**: Shown in dashboard widget
- **Low Stock Alert**: < 10 units
- **Session Timeout**: 1 hour of inactivity

---

## Security Notes

🔐 **Admin Password**: Keep secret, change from default
🔐 **Session Security**: Expires after 1 hour
🔐 **Data Protection**: All queries secured
🔐 **Backups**: Enable auto-backup if available

---

## Contact Information Fields in Admin

When finding user contact info:
- **Users table**: name, email, phone, city, state
- **Donations**: donor name, email (if not anonymous)
- **Orders**: customer name, email
- **Messages**: sender name, email, phone
- **Volunteers**: linked to user profile

---

## FAQ - Quick Answers

**Q: How do I change admin password?**
A: Go to Profile, update password there

**Q: Can I restore deleted users?**
A: No, backups only. Delete carefully!

**Q: How do I add new products?**
A: Go to Products section, add functionality (coming soon)

**Q: Can I export data?**
A: Currently view-only. Export feature coming soon

**Q: Are donations GDPR compliant?**
A: Yes, system supports anonymous donations

---

**Last Updated**: February 15, 2026
**Admin Panel Version**: 2.0
