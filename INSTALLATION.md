# Module 03: User Management — ইনস্টলেশন গাইড

## ⏱️ সময়: ১৫-২০ মিনিট

### Step 1: Files Copy করুন

```bash
cd /path/to/your/laravel-project

cp -r swadhin-alo-module-03/app/Modules/UserManagement app/Modules/
cp -r swadhin-alo-module-03/database/migrations/* database/migrations/
cp -r swadhin-alo-module-03/database/factories/* database/factories/
cp swadhin-alo-module-03/routes/user-management.php routes/
cp swadhin-alo-module-03/config/user-management.php config/
```

### Step 2: User Model Update করুন

`app/Modules/Authentication/Models/User.php` খুলুন:

```php
<?php

use App\Modules\UserManagement\Traits\HasProfileAndPreferences;

class User extends Model
{
    use HasProfileAndPreferences;  // Add this line
    // ... rest of code
}
```

### Step 3: Routes Register করুন

`routes/api.php`-তে যোগ করুন:

```php
require base_path('routes/user-management.php');
```

### Step 4: Composer Autoload

```bash
composer dump-autoload
```

### Step 5: Run Migrations

```bash
php artisan migrate
```

Output:
```
Migrating: 2024_01_01_000020_create_user_profiles_table.php
Migrated:  2024_01_01_000020_create_user_profiles_table.php
Migrating: 2024_01_01_000021_create_user_preferences_table.php
Migrated:  2024_01_01_000021_create_user_preferences_table.php
Migrating: 2024_01_01_000022_create_user_notification_preferences_table.php
Migrated:  2024_01_01_000022_create_user_notification_preferences_table.php
```

### Step 6: Test করুন

```bash
php artisan test
```

---

## ✅ Verification

### Database চেক করুন

```sql
SHOW TABLES LIKE 'user_%';
```

আপনার দেখতে পাবেন:
- `user_profiles`
- `user_preferences`
- `user_notification_preferences`

### API Test করুন

```bash
# Get your profile
curl -X GET http://localhost:8000/api/v1/profile \
  -H "Authorization: Bearer YOUR_TOKEN"

# Response:
# {
#   "status": "success",
#   "data": {
#     "id": 1,
#     "user_id": 1,
#     "full_name": "John Doe",
#     ...
#   }
# }
```

### User Methods Test করুন

```bash
php artisan tinker
```

```php
>>> $user = \App\Modules\Authentication\Models\User::first();
>>> $user->profile;
// Returns: UserProfile instance

>>> $user->getFullName();
// Returns: "John Doe"

>>> $user->preference;
// Returns: UserPreference instance

>>> $user->getTheme();
// Returns: "light"

>>> $user->notificationPreferences;
// Returns: Collection of notification preferences
```

---

## 🐛 Troubleshooting

### "Trait not found"

```bash
composer dump-autoload
php artisan cache:clear
```

### Migration failed

```bash
php artisan migrate:reset
php artisan migrate
```

### Routes not working

Verify `routes/api.php` contains:
```php
require base_path('routes/user-management.php');
```

And list routes:
```bash
php artisan route:list | grep profile
```

---

## 📝 Configuration

Optional in `.env`:

```env
APP_TIMEZONE=UTC
```

Or in `config/user-management.php`:
- `avatar.max_size` — Default: 2MB
- `cover.max_size` — Default: 5MB
- `profile.bio_max_length` — Default: 1000 chars

---

## ✅ Installation Complete!

এখন আপনার সিস্টেমে সম্পূর্ণ User Management আছে। 🎉

পরবর্তী: Module 04 (Settings) অথবা Module 06 (Article Management)
