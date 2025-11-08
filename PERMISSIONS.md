# RT Net - Role-Based Access Control (RBAC) Documentation

## 🔐 User Roles & Permissions

### Role Hierarchy
```
1. admin_rt      (Super Admin - Full Access)
2. ketua_rt      (Management - Create/Edit Most Resources)
3. bendahara     (Finance Only - Finance Management)
4. warga         (View Only - Read Access)
```

---

## 📋 Permissions Matrix

### **Data Warga (Residents)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View List | ✅ | ✅ | ✅ | ✅ |
| View Details | ✅ | ✅ | ✅ | ✅ |
| Create | ✅ | ✅ | ❌ | ❌ |
| Edit | ✅ | ✅ | ❌ | ❌ |
| Delete | ✅ | ❌ | ❌ | ❌ |

**Policy**: `ResidentPolicy`
- **Everyone** can view residents
- **admin_rt & ketua_rt** can manage (create/edit)
- **Only admin_rt** can delete

---

### **Keuangan RT (Finances)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View List | ✅ | ✅ | ✅ | ✅ |
| View Details | ✅ | ✅ | ✅ | ✅ |
| Create | ✅ | ✅ | ✅ | ❌ |
| Edit | ✅ | ✅ | ✅ | ❌ |
| Delete | ✅ | ✅ | ❌ | ❌ |

**Policy**: `FinancePolicy`
- **Everyone** can view finances
- **admin_rt, ketua_rt & bendahara** can create/edit transactions
- **admin_rt & ketua_rt** can delete

---

### **Laporan Keuangan (Financial Reports)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View List | ✅ | ✅ | ✅ | ✅ |
| View Details | ✅ | ✅ | ✅ | ✅ |
| Create | ✅ | ✅ | ✅ | ❌ |
| Edit | ✅ | ✅ | ✅ | ❌ |
| Delete | ✅ | ✅ | ❌ | ❌ |

**Policy**: `FinancialReportPolicy`
- **Everyone** can view reports
- **admin_rt, ketua_rt & bendahara** can create/edit reports
- **admin_rt & ketua_rt** can delete

---

### **Surat Digital (Digital Letters)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View List | ✅ | ✅ | ✅ | ✅ |
| View Details | ✅ | ✅ | ✅ | ✅ |
| Create | ✅ | ✅ | ❌ | ❌ |
| Edit | ✅ | ✅ | ❌ | ❌ |
| Delete | ✅ | ❌ | ❌ | ❌ |

**Policy**: `DigitalLetterPolicy`
- **Everyone** can view letters
- **admin_rt & ketua_rt** can create/edit letters
- **Only admin_rt** can delete

---

### **Pengumuman (Announcements)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View List | ✅ | ✅ | ✅ | ✅ |
| View Details | ✅ | ✅ | ✅ | ✅ |
| Create | ✅ | ✅ | ❌ | ❌ |
| Edit | ✅ | ✅ | ❌ | ❌ |
| Delete | ✅ | ✅ | ❌ | ❌ |

**Policy**: `AnnouncementPolicy`
- **Everyone** can view announcements
- **admin_rt & ketua_rt** can create/edit/delete announcements

---

### **Acara & Kegiatan (Events)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View List | ✅ | ✅ | ✅ | ✅ |
| View Details | ✅ | ✅ | ✅ | ✅ |
| Create | ✅ | ✅ | ❌ | ❌ |
| Edit | ✅ | ✅ | ❌ | ❌ |
| Delete | ✅ | ✅ | ❌ | ❌ |

**Policy**: `EventPolicy`
- **Everyone** can view events
- **admin_rt & ketua_rt** can create/edit/delete events

---

### **Dokumen (Documents)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View List | ✅ | ✅ | ✅ | ✅ |
| View Details | ✅ | ✅ | ✅ | ✅ |
| Create | ✅ | ✅ | ❌ | ❌ |
| Edit | ✅ | ✅ | ❌ | ❌ |
| Delete | ✅ | ✅ | ❌ | ❌ |

**Policy**: `DocumentPolicy`
- **Everyone** can view documents
- **admin_rt & ketua_rt** can upload/edit/delete documents

---

### **Manajemen Pengguna (Users)**
| Action | admin_rt | ketua_rt | bendahara | warga |
|--------|----------|----------|-----------|-------|
| View Navigation | ✅ | ✅ | ❌ | ❌ |
| View List | ✅ | ✅ | ❌ | ❌ |
| View Details | ✅ | ✅ | ❌ | ❌ |
| Create | ✅ | ❌ | ❌ | ❌ |
| Edit | ✅ | ❌ | ❌ | ❌ |
| Delete | ✅ | ❌ | ❌ | ❌ |

**Policy**: `UserPolicy`
- **Navigation hidden** for bendahara & warga
- **admin_rt & ketua_rt** can view users
- **Only admin_rt** can create/edit/delete users
- **admin_rt cannot delete themselves** (safety)

---

## 🔧 User Model Helper Methods

### Role Checking Methods
```php
// Check specific role
$user->isAdminRT();      // Returns true if admin_rt
$user->isKetuaRT();      // Returns true if ketua_rt
$user->isBendahara();    // Returns true if bendahara
$user->isWarga();        // Returns true if warga

// Check permissions
$user->canManage();              // admin_rt OR ketua_rt
$user->canManageFinances();      // admin_rt OR ketua_rt OR bendahara
```

### Usage Examples
```php
// In a controller or blade view
if (auth()->user()->canManage()) {
    // Show management buttons
}

if (auth()->user()->canManageFinances()) {
    // Show finance creation form
}

if (auth()->user()->isAdminRT()) {
    // Show admin-only features
}
```

---

## 🎯 Policy Implementation

All policies check two conditions:
1. **User is active**: `$user->is_active === true`
2. **User has proper role**: Based on permission requirements

### Policy Files Location
```
app/Policies/
├── ResidentPolicy.php
├── FinancePolicy.php
├── FinancialReportPolicy.php
├── DigitalLetterPolicy.php
├── AnnouncementPolicy.php
├── EventPolicy.php
├── DocumentPolicy.php
└── UserPolicy.php
```

---

## 🚀 Testing Permissions

### Test Users (from DatabaseSeeder)
```php
// Super Admin (Full Access)
Email: admin@rtnet.local
Password: password
Role: admin_rt

// Management (Most Features)
Email: ketua@rtnet.local
Password: password
Role: ketua_rt

// Finance Only
Email: bendahara@rtnet.local
Password: password
Role: bendahara

// View Only
Email: warga@rtnet.local
Password: password
Role: warga
```

### Testing Checklist
- [ ] Login as **warga** → Can only view, no create/edit/delete buttons
- [ ] Login as **bendahara** → Can manage finances, view everything else
- [ ] Login as **ketua_rt** → Can manage most resources, cannot manage users
- [ ] Login as **admin_rt** → Full access to everything

---

## 🔒 Security Features

### Inactive User Protection
All policies check `$user->is_active` before granting access.
- Inactive users are **automatically blocked** from all operations
- Toggle `is_active` in User Management to disable accounts

### Self-Delete Protection
```php
// UserPolicy prevents admins from deleting themselves
public function delete(User $user, User $model): bool
{
    return $user->isAdminRT() && $user->id !== $model->id;
}
```

### Navigation Hiding
Resources automatically hide from sidebar if user lacks `viewAny` permission:
- **UserResource** hidden from bendahara & warga
- All other resources visible to all authenticated users

---

## 📊 Permission Summary by Role

### **admin_rt** (Super Admin)
- ✅ Full CRUD on all resources
- ✅ Can manage users (create/edit/delete)
- ✅ Can force delete and restore all records
- ✅ Sees all navigation items

### **ketua_rt** (Management)
- ✅ Full CRUD on residents, announcements, events, documents, letters
- ✅ Full CRUD on finances and reports
- ✅ Can view users (read-only)
- ❌ Cannot create/edit/delete users
- ❌ Cannot force delete residents or letters

### **bendahara** (Finance)
- ✅ Full CRUD on finances and financial reports
- ✅ View-only access to all other resources
- ❌ Cannot create/edit residents, letters, announcements, events, documents
- ❌ Cannot view or manage users
- ❌ User navigation hidden

### **warga** (View Only)
- ✅ View access to all resources (except users)
- ❌ Cannot create, edit, or delete anything
- ❌ User navigation hidden
- ❌ All action buttons (Create, Edit, Delete) hidden

---

## 🛠️ Customizing Permissions

### Adding New Permissions
1. Add method to `User` model:
```php
public function canDoSomething(): bool
{
    return in_array($this->role, ['admin_rt', 'ketua_rt']);
}
```

2. Use in policies:
```php
public function create(User $user): bool
{
    return $user->is_active && $user->canDoSomething();
}
```

### Modifying Existing Permissions
Edit the respective policy file in `app/Policies/`:
```php
// Example: Allow bendahara to edit residents
public function update(User $user, Resident $resident): bool
{
    return $user->is_active && in_array($user->role, ['admin_rt', 'ketua_rt', 'bendahara']);
}
```

---

## 📝 Notes

- All policies are **automatically registered** by Laravel
- Filament **automatically checks policies** for CRUD operations
- Navigation items **automatically hide** based on `canViewAny()` policy
- **Inactive users** (`is_active = false`) are blocked from all operations
- **Guests** (unauthenticated users) are **redirected to login** automatically

---

**Last Updated**: November 8, 2025  
**Laravel Version**: 12.37.0  
**Filament Version**: 4.1.10
