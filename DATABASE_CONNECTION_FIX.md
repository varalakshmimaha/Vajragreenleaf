# Database Connection Error - Troubleshooting Guide

## Error
```
SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
```

## Root Cause
The MySQL database server is not running or Laravel cannot connect to it.

## Solutions (Try in Order)

### Solution 1: Start MySQL/XAMPP/WAMP

#### If using XAMPP:
1. Open XAMPP Control Panel
2. Click "Start" next to MySQL
3. Wait for it to show "Running" status
4. Refresh your Laravel application

#### If using WAMP:
1. Open WAMP Server
2. Start all services
3. Ensure MySQL is running (green icon)

#### If using Laragon:
1. Open Laragon
2. Click "Start All"
3. Ensure MySQL is running

### Solution 2: Check Database Configuration

**File**: `.env`

Verify these settings:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
```

**Common Issues**:
- Wrong DB_HOST (should be `127.0.0.1` or `localhost`)
- Wrong DB_PORT (default is `3306`)
- Wrong DB_DATABASE name
- Wrong credentials (username/password)

### Solution 3: Test Database Connection

Run this command to test:
```bash
php artisan tinker
```

Then type:
```php
DB::connection()->getPdo();
```

If successful, you'll see PDO connection details.
If failed, you'll see the exact error.

### Solution 4: Check MySQL Service (Windows)

1. Press `Win + R`
2. Type `services.msc` and press Enter
3. Look for "MySQL" or "MySQL80" (version may vary)
4. Right-click → Start (if not running)
5. Set "Startup type" to "Automatic" (optional)

### Solution 5: Restart MySQL Service

**Via Command Line (Admin)**:
```bash
net stop MySQL80
net start MySQL80
```

**Or**:
```bash
net stop MySQL
net start MySQL
```

### Solution 6: Clear Laravel Cache

Sometimes cached config causes issues:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Solution 7: Use SQLite (Temporary Alternative)

If MySQL won't start, use SQLite temporarily:

**1. Update `.env`**:
```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=your_database_name
# DB_USERNAME=root
# DB_PASSWORD=
```

**2. Create SQLite database**:
```bash
touch database/database.sqlite
```

**3. Run migrations**:
```bash
php artisan migrate
```

### Solution 8: Check Port Conflict

MySQL might be blocked by another service on port 3306.

**Check what's using port 3306**:
```bash
netstat -ano | findstr :3306
```

If another process is using it, either:
- Stop that process
- Change MySQL port in my.ini and .env

### Solution 9: Reinstall/Repair MySQL

If nothing works:
1. Backup your database (if accessible)
2. Uninstall MySQL
3. Reinstall MySQL or use XAMPP/WAMP

## Quick Fix Checklist

- [ ] Is XAMPP/WAMP/Laragon running?
- [ ] Is MySQL service started?
- [ ] Is `.env` configured correctly?
- [ ] Can you ping `127.0.0.1`?
- [ ] Is port 3306 available?
- [ ] Did you clear Laravel cache?
- [ ] Can you access phpMyAdmin?

## Immediate Action

**For Windows with XAMPP** (Most Common):

1. **Open XAMPP Control Panel**
2. **Click "Start" next to MySQL**
3. **Wait for green "Running" status**
4. **Refresh your browser**

That's it! 90% of the time, this fixes the issue.

## Prevention

To avoid this in the future:
1. Set MySQL to start automatically with Windows
2. Use a database management tool (XAMPP, Laragon)
3. Keep `.env` backed up
4. Monitor MySQL service status

## Alternative: Use SQLite for Development

SQLite requires no server and is perfect for development:

```env
DB_CONNECTION=sqlite
```

Create the file:
```bash
New-Item -Path database\database.sqlite -ItemType File
```

Run migrations:
```bash
php artisan migrate
```

---

**Most Likely Solution**: Start XAMPP/WAMP and click "Start" on MySQL service.

**Last Updated**: January 24, 2026
