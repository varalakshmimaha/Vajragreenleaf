# Reports Section - Access Guide

## ✅ Reports Section is Now Available!

The Reports & References module is now accessible in your admin panel.

### 📍 How to Access:

1. **Login to Admin Panel**:
   ```
   http://127.0.0.1:8000/admin
   ```

2. **Navigate to Reports**:
   - Look at the left sidebar
   - Scroll down to **"Reports & Analytics"** section
   - Click on **"Reports & References"**

   Or directly visit:
   ```
   http://127.0.0.1:8000/admin/reports
   ```

### 📊 Available Reports:

Once you access the Reports dashboard, you'll see 6 report types:

1. **Summary Report** - Overall referral statistics
2. **Level-Wise Report** - Distribution across levels
3. **User-Wise Report** - Individual performance
4. **Drill-Down Explorer** - Interactive tree
5. **Growth Report** - Trend analysis
6. **Inactive Users** - Zero/inactive users

### 🎯 What You'll See:

**Dashboard Cards**:
- Total Users
- Direct References (Level 1)
- Sub-References (Level 2+)
- Maximum Depth
- Average Referrals per User
- Growth Rate (30 days)
- Active Users

**Report Cards**:
- Colorful, clickable cards for each report type
- Icons and descriptions
- Hover effects

### ⚠️ Important Note:

**You need to start MySQL first!**

The reports require database access. Make sure:
1. XAMPP/WAMP is running
2. MySQL service is started
3. Database is accessible

If you see a database connection error, refer to:
`DATABASE_CONNECTION_FIX.md`

### 🚀 Quick Start:

1. **Start MySQL** (XAMPP/WAMP)
2. **Visit**: `http://127.0.0.1:8000/admin/reports`
3. **Click any report card** to view detailed analytics
4. **Use filters** to refine data
5. **Export** reports as CSV

### 📁 Files Created:

✅ `resources/views/admin/reports/index.blade.php` - Dashboard  
✅ `resources/views/layouts/admin.blade.php` - Added menu item  
✅ `app/Services/ReferralReportService.php` - Backend logic  
✅ `app/Http/Controllers/Admin/ReportController.php` - Controller  
✅ `routes/web.php` - Routes configured  

### 🔜 Next Steps:

To complete the module, we still need to create views for:
- Summary report page
- Level-wise report page
- User-wise report page
- Drill-down explorer page
- Growth report page
- Inactive users page

But the **dashboard is ready** and you can access it now!

---

**Access URL**: `http://127.0.0.1:8000/admin/reports`  
**Menu Location**: Admin Sidebar → Reports & Analytics → Reports & References

**Last Updated**: January 24, 2026
