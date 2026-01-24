# Multilevel Referral System Documentation

## Overview
Implemented a comprehensive multilevel referral system with 5-digit random referral IDs. The system supports unlimited referral levels with a tree-based structure and provides a user-friendly dashboard to visualize the referral network.

## System Features

### ✅ 5-Digit Random Referral ID Generation
- Each user receives a **unique 5-digit number** (10000-99999) as their referral ID
- Example IDs: `54321`, `10457`, `98765`
- IDs are generated during registration and stored in the database
- Uniqueness is guaranteed through database validation

### ✅ Multilevel Referral Tree
- Supports **unlimited referral levels**
- Hierarchical parent-child relationship structure
- Each user can refer multiple people
- All referee relationships are tracked via `sponsor_referral_id`

### ✅ Dashboard Display
- Shows referral network up to **3 levels** by default
- **"Load More"** button to fetch all levels dynamically
- Beautiful tree visualization with level indicators
- Real-time statistics cards showing total referrals per level

### ✅ Optional Referral System
- Users can register **with or without** a sponsor
- Skip button on sponsor validation page
- Referral ID field is optional in registration form

## Database Schema

### Updated `users` Table
```sql
- id (Primary Key)
- name
- email
- mobile
- username (Legacy - e.g., "USER123")
- referral_id (NEW - 5-digit unique number, e.g., "54321")
- sponsor_id (Legacy - stores username of referrer)
- sponsor_referral_id (NEW - stores referral_id of referrer)
- password
- address, state, city, pincode
- is_active
- role
- created_at, updated_at
```

### Key Relationships
```
User A (Referral ID: 54321)
├── User B (Referral ID: 10457, Sponsor Referral ID: 54321)
│   ├── User D (Referral ID: 37462, Sponsor Referral ID: 10457)
│   └── User E (Referral ID: 84591, Sponsor Referral ID: 10457)
├── User C (Referral ID: 98765, Sponsor Referral ID: 54321)
└── User F (Referral ID: 19283, Sponsor Referral ID: 54321)
```

## Files Created/Modified

### New Files Created
1. **Migration**: `database/migrations/2026_01_24_103044_add_referral_id_to_users_table.php`
   - Adds `referral_id` and `sponsor_referral_id` columns
   - Migrates existing data
   - Generates referral IDs for existing users

2. **Controller**: `app/Http/Controllers/ReferralController.php`
   - `getReferralTree()` - Fetch referral tree with level limits
   - `getReferralStats()` - Get statistics for levels 1-3
   - `dashboard()` - Display referral dashboard

3. **View**: `resources/views/referrals/dashboard.blade.php`
   - Beautiful UI with glassmorphism design
   - Stats cards for each level
   - Tree visualization with indentation
   - Dynamic loading with AJAX

### Modified Files
1. **Model**: `app/Models/User.php`
   - Added `referral_id` and `sponsor_referral_id` to fillable
   - New relationships: `referrals()`, `sponsorByReferralId()`
   - Recursive methods: `getAllReferrals()`, `hasReferralsBeyondLevel()`

2. **Controller**: `app/Http/Controllers/FrontendAuthController.php`
   - `generateReferralId()` - Generates unique 5-digit IDs
   - Updated `register()` to create referral_id
   - Updated `validateSponsor()` to accept both username and referral_id
   - Updated `showRegister()` to pass sponsorReferralId to view

3. **Routes**: `routes/web.php`
   - `/user/referrals` - Referral dashboard
   - `/user/referrals/tree` - API endpoint for tree data
   - `/user/referrals/stats` - API endpoint for statistics

4. **Views**:
   - `resources/views/auth/sponsor.blade.php`
     - Marked referral ID as optional
     - Added skip button
     - Updated validation to use referral_id
   
   - `resources/views/auth/register.blade.php`
     - Added optional referral ID field
     - Shows sponsor name if validated
   
   - `resources/views/auth/success.blade.php`
     - Updated to display the referral ID

## API Endpoints

### GET `/user/referrals/tree`
Fetch the referral tree for the authenticated user.

**Query Parameters:**
- `maxLevel` (optional, default: 3) - Number of levels to fetch

**Response:**
```json
{
  "referralID": "54321",
  "name": "John Doe",
  "referrals": [
    {
      "referralID": "10457",
      "name": "Friend 1",
      "email": "friend1@example.com",
      "level": 1,
      "created_at": "2026-01-24T10:30:44.000000Z",
      "children": [
        {
          "referralID": "37462",
          "name": "Friend 1A",
          "level": 2,
          "children": []
        }
      ]
    }
  ],
  "hasMore": true,
  "totalDirectReferrals": 3
}
```

### GET `/user/referrals/stats`
Get referral statistics by level.

**Response:**
```json
{
  "referralID": "54321",
  "totalReferrals": 15,
  "level1": 3,
  "level2": 7,
  "level3": 5
}
```

## User Flows

### Registration Flow (With Referral)
1. User visits `/join` (sponsor validation page)
2. Enters referral ID (5-digit or username for legacy support)
3. System validates and shows sponsor details
4. Clicks "Continue to Registration"
5. Registration form opens with referral ID pre-filled
6. Completes registration
7. System generates unique 5-digit referral ID for new user
8. Links new user to sponsor via `sponsor_referral_id`
9. Success page displays the new user's referral ID

### Registration Flow (Without Referral)
1. User visits `/join`
2. Clicks "Skip - Continue Without Sponsor"
3. Registration form opens (no sponsor)
4. Completes registration
5. System generates unique 5-digit referral ID
6. No sponsor linkage (`sponsor_referral_id` is NULL)
7. Success page displays the referral ID

### Viewing Referral Network
1. User logs in
2. Navigates to `/user/referrals`
3. Dashboard loads showing:
   - User's own referral ID
   - Statistics cards (Total, Level 1-3)
   - Referral tree (first 3 levels)
4. If more levels exist, "Load All Levels" button appears
5. Click button to load complete tree
6. Tree displays with visual level indicators

## Migration Instructions

### For Existing Systems
The migration automatically:
1. Adds new columns to users table
2. Generates unique 5-digit referral IDs for all existing users
3. Maps existing `sponsor_id` (username) to `sponsor_referral_id`
4. Preserves all existing data and relationships

### Running the Migration
```bash
php artisan migrate
```

### Rollback (if needed)
```bash
php artisan migrate:rollback
```

## Testing Checklist

- [ ] New user registration creates unique 5-digit referral ID
- [ ] Sponsor validation accepts both referral ID and username
- [ ] Skip sponsor option works correctly
- [ ] Referral tree displays correctly up to 3 levels
- [ ] Load More button appears when more levels exist
- [ ] Stats API returns accurate counts
- [ ] Multiple users can be referred by same sponsor
- [ ] Success page displays referral ID correctly
- [ ] Referral ID copy function works
- [ ] Dashboard is accessible at `/user/referrals`

## Security Considerations

✅ **Implemented:**
- Unique referral ID validation prevents duplicates
- Authentication required for dashboard access
- Input validation on all sponsor ID fields
- SQL injection protection via Eloquent ORM
- XSS protection via Blade templating

## Performance Optimizations

✅ **Implemented:**
- Eager loading with `with(['referrals'])` to prevent N+1 queries
- Level limiting to avoid deep recursion
- Lazy loading option for "Load All Levels"
- Database indexing on `referral_id` column (unique constraint)

## Future Enhancements (Optional)

- [ ] Referral rewards/points system
- [ ] Email notifications for new referrals
- [ ] Export referral tree as PDF/Excel
- [ ] Referral link generator (shareable URLs)
- [ ] Analytics dashboard (growth charts, conversion rates)
- [ ] Referral leaderboard
- [ ] Commission/reward calculation based on levels

## Support & Maintenance

### Common Issues

**Issue: Referral ID not generated**
- Check migration ran successfully
- Verify `referral_id` column exists in users table
- Check logs: `storage/logs/laravel.log`

**Issue: Tree not loading**
- Check authentication middleware
- Verify routes are registered
- Check browser console for API errors
- Ensure user has `referral_id` set

**Issue: Duplicate referral IDs**
- Should never occur due to uniqueness check
- If it does, run: `php artisan migrate:refresh`
- Check database for duplicate entries: `SELECT referral_id, COUNT(*) FROM users GROUP BY referral_id HAVING COUNT(*) > 1`

## Technical Stack

- **Backend**: Laravel 10+ (PHP 8.1+)
- **Frontend**: Blade Templates, Vanilla JavaScript
- **Styling**: Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **API**: RESTful JSON endpoints

## Credits

Developed as part of the Vajra Green Leaf MLM system.
Implementation follows industry best practices for referral tracking and multi-level marketing structures.
