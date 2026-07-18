# CardX — Complete Technical Architecture Report

> **Project**: CardX — QR Code & Digital Card SaaS Platform
> **Date**: July 18, 2026
> **Status**: Production-ready SaaS platform

---

## 1. Environment

| Component | Version |
|---|---|
| Laravel | 12.63.0 |
| PHP | 8.2.12 |
| Livewire | 4.3.3 |
| Alpine.js | Via Livewire (bundled) |
| Bootstrap | 5.3.3 (CDN) |
| Vite | 7.0.7 |
| Node | Not explicitly specified |
| Composer | 2.8.8 |
| Tailwind CSS | 4.0.0 |
| MySQL | Default connection |

### Installed Composer Packages

| Package | Version | Purpose |
|---|---|---|
| `laravel/framework` | ^12.0 | Core framework |
| `livewire/livewire` | * (4.3.3) | Real-time UI components |
| `spatie/laravel-permission` | ^6.25 | Roles & permissions |
| `spatie/laravel-activitylog` | ^4.12 | Activity logging |
| `chillerlan/php-qrcode` | ^6.0 | QR code generation |
| `barryvdh/laravel-dompdf` | ^3.1 | PDF generation |
| `intervention/image` | ^3.11 | Image manipulation |
| `morilog/jalali` | * | Persian/Jalali date conversion |
| `laravel/tinker` | ^2.10.1 | REPL |

### Installed NPM Packages

| Package | Version | Purpose |
|---|---|---|
| `tailwindcss` | ^4.0.0 | Utility CSS framework |
| `@tailwindcss/vite` | ^4.0.0 | Tailwind Vite plugin |
| `axios` | ^1.11.0 | HTTP client |
| `concurrently` | ^9.0.1 | Parallel script runner |
| `laravel-vite-plugin` | ^2.0.0 | Vite Laravel integration |
| `vite` | ^7.0.7 | Build tool |

### Project Structure Overview

```
cardx/
├── app/                    # Application code (MVC + Services)
├── bootstrap/              # Application bootstrapping
├── config/                 # Configuration files
├── database/               # Migrations, seeders, factories
├── docs/                   # Documentation (empty)
├── public/                 # Public assets
├── resources/              # Views, JS, CSS
├── routes/                 # Route definitions
├── storage/                # File storage, cache, logs
├── tests/                  # Test suites
├── vendor/                 # Composer dependencies
├── composer.json           # PHP dependencies
├── package.json            # Node dependencies
├── vite.config.js          # Vite configuration
├── artisan                 # Laravel CLI
├── .env                    # Environment variables
└── README.md               # Project readme
```

---

## 2. Folder Structure

### `app/` — Application Code

| Directory | Purpose |
|---|---|
| `Config/` | Application configuration classes |
| `Console/Commands/` | Artisan commands (empty after cleanup) |
| `Contracts/` | Interfaces (empty after cleanup) |
| `Data/` | Data transfer objects (empty after cleanup) |
| `Enums/` | PHP 8.1 enums (PaymentStatusEnum, QrCodeTypeEnum, SubscriptionStatusEnum) |
| `Events/` | Event classes (PaymentCompleted, QrCodeCreated, QrCodeScanned, UserRegistered) |
| `Http/Controllers/` | HTTP controllers organized by domain |
| `Http/Middleware/` | Request middleware (7 custom middleware) |
| `Jobs/` | Queued jobs (empty) |
| `Listeners/` | Event listeners (SendWelcomeEmail, UpdateQrScanCount) |
| `Livewire/` | Livewire components (QrGenerator) |
| `Mail/` | Mailable classes (4 emails) |
| `Models/` | Eloquent models (11 models) |
| `Observers/` | Model observers (empty) |
| `Policies/` | Authorization policies (MediaPolicy, QrCodePolicy) |
| `Providers/` | Service providers (AppServiceProvider, EventServiceProvider) |
| `Services/` | Business logic services |
| `Traits/` | Reusable traits (HasActivityLog, HasSlug) |

### `config/` — Configuration

| File | Purpose |
|---|---|
| `app.php` | Application config |
| `auth.php` | Authentication config |
| `cache.php` | Cache driver config |
| `database.php` | Database connections |
| `filesystems.php` | Storage disks |
| `livewire.php` | Livewire configuration |
| `logging.php` | Log channels |
| `mail.php` | Mail drivers |
| `permission.php` | Spatie Permission config |
| `queue.php` | Queue connections |
| `services.php` | Third-party services |
| `session.php` | Session driver |

### `database/` — Database

| Directory | Purpose |
|---|---|
| `migrations/` | 22 migration files |
| `seeders/` | 7 seeder files |
| `factories/` | Model factories |

### `resources/` — Frontend Assets

| Directory | Purpose |
|---|---|
| `views/` | Blade templates (12 subdirectories) |
| `js/` | JavaScript files (app.js, bootstrap.js) |
| `css/` | Stylesheets (app.css via Tailwind) |
| `lang/` | Localization files |

### `routes/` — Routing

| File | Purpose |
|---|---|
| `web.php` | Web routes (126 lines) |
| `console.php` | Console/scheduled routes |

---

## 3. Application Architecture

### MVC Structure

The application follows standard Laravel MVC with a **Service Layer** pattern:

```
Request → Route → Controller → Service → Model → Database
                  ↓
              Livewire Component (for real-time UI)
                  ↓
              Blade View
```

### Service Layer

Services are used for complex business logic that spans multiple models:

| Service | Responsibility |
|---|---|
| `QrRenderer` | QR code generation (PNG, SVG, DataURI) |
| `PersianFontService` | Persian font loading and management |
| `MapProviderFactory` | Map provider abstraction (Google, Neshan, Balad) |

### Middleware Stack

| Middleware | Alias | Purpose |
|---|---|---|
| `AdminMiddleware` | `admin` | Checks admin role |
| `SuperAdminMiddleware` | `super-admin` | Checks super-admin role |
| `ManagerMiddleware` | `manager` | Checks manager role |
| `ActiveAccountMiddleware` | `active-account` | Checks user is active |
| `VerifiedPhoneMiddleware` | `verified-phone` | Checks phone verification |
| `SubscriptionMiddleware` | `subscription` | Checks active subscription |
| `SetLocale` | `locale` | Sets application locale (global) |

### Events & Listeners

| Event | Listener | Trigger |
|---|---|---|
| `UserRegistered` | `SendWelcomeEmail` | New user registration |
| `QrCodeScanned` | `UpdateQrScanCount` | QR code scanned |
| `PaymentCompleted` | (none) | Payment processed |
| `QrCodeCreated` | (none) | QR code created |

### Dependency Diagram

```
┌─────────────────────────────────────────────────────────┐
│                    Presentation Layer                     │
│  Blade Views ← Livewire Components ← Alpine.js          │
└─────────────────────┬───────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────┐
│                   HTTP Layer                             │
│  Routes → Controllers → Middleware → Form Requests       │
└─────────────────────┬───────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────┐
│                  Service Layer                           │
│  QrRenderer, PersianFontService, MapProviderFactory     │
└─────────────────────┬───────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────┐
│                   Data Layer                             │
│  Eloquent Models → MySQL Database                        │
│  Policies ← Gates ← Auth                                │
└─────────────────────────────────────────────────────────┘
```

---

## 4. Database

### Tables (22 total)

| Table | Purpose | Model |
|---|---|---|
| `users` | User accounts | `User` |
| `cache` | Application cache | (Framework) |
| `jobs` | Queued jobs | (Framework) |
| `job_batches` | Batch job tracking | (Framework) |
| `failed_jobs` | Failed job tracking | (Framework) |
| `sessions` | User sessions | (Framework) |
| `password_reset_tokens` | Password reset tokens | (Framework) |
| `notifications` | Notification storage | (Framework) |
| `permissions` | Spatie permissions | (Spatie) |
| `roles` | Spatie roles | (Spatie) |
| `model_has_permissions` | Permission pivot | (Spatie) |
| `model_has_roles` | Role pivot | (Spatie) |
| `role_has_permissions` | Role-permission pivot | (Spatie) |
| `activity_log` | Activity audit trail | (Spatie) |
| `qr_codes` | QR code definitions | `QrCode` |
| `qr_scans` | QR code scan records | `QrScan` |
| `media_folders` | Media folder structure | `MediaFolder` |
| `media` | Uploaded media files | `Media` |
| `fonts` | Custom font files | `Font` |
| `plans` | Subscription plans | `Plan` |
| `subscriptions` | User subscriptions | `Subscription` |
| `payments` | Payment records | `Payment` |
| `coupons` | Discount coupons | `Coupon` |
| `settings` | Application settings | `Setting` |

### ER Diagram (Simplified)

```
users ──┬── qr_codes ──── qr_scans
        ├── media ──── media_folders
        ├── subscriptions ──── plans
        ├── payments ──── coupons
        ├── fonts
        └── settings
```

---

## 5. Models

### User
- **Table**: `users`
- **Fillable**: name, email, password, phone, avatar, is_active, phone_verified_at, last_login_at, settings
- **Casts**: email_verified_at, phone_verified_at (datetime), password (hashed), settings (array), last_login_at (datetime), is_active (boolean)
- **Relationships**: qrCodes (HasMany), media (HasMany), subscriptions (HasMany), payments (HasMany)
- **Scopes**: active
- **Traits**: HasFactory, HasRoles (Spatie), Notifiable, HasActivityLog

### QrCode
- **Table**: `qr_codes`
- **Fillable**: user_id, type, title, content, unique_code, foreground_color, background_color, gradient_from, gradient_to, logo_path, logo_size, logo_padding, style, shape, pattern, size, error_correction, eye_style, eye_color, frame_style, frame_color, text, text_position, text_font, text_size, text_color, margin, resolution, is_active, scans_count, settings
- **Casts**: settings (array), is_active (boolean), scans_count/size/logo_size/logo_padding/margin/resolution/text_size (integer)
- **Relationships**: user (BelongsTo), qrScans/scans (HasMany)
- **Boot**: Auto-generates unique_code on creating

### QrScan
- **Table**: `qr_scans`
- **Relationships**: qrCode (BelongsTo)

### Media
- **Table**: `media`
- **Relationships**: user (BelongsTo), folder (BelongsTo MediaFolder)

### MediaFolder
- **Table**: `media_folders`
- **Relationships**: user (BelongsTo), parent (BelongsTo self), children (HasMany self), media (HasMany)

### Font
- **Table**: `fonts`
- **Relationships**: user (BelongsTo)

### Plan
- **Table**: `plans`
- **Fillable**: name, slug, description, price_monthly, price_yearly, features, max_qr_codes, max_media_storage, max_templates, is_active, sort_order
- **Casts**: features (array), price_monthly/price_yearly (decimal:2), is_active (boolean), sort_order/max_qr_codes/max_media_storage/max_templates (integer)
- **Relationships**: subscriptions (HasMany)

### Subscription
- **Table**: `subscriptions`
- **Relationships**: user (BelongsTo), plan (BelongsTo)

### Payment
- **Table**: `payments`
- **Relationships**: user (BelongsTo), plan (BelongsTo)

### Coupon
- **Table**: `coupons`

### Setting
- **Table**: `settings`

---

## 6. Controllers

### Auth Controllers

| Controller | Methods | Routes |
|---|---|---|
| `LoginController` | showForm, login, logout | GET/POST /login, POST /logout |
| `RegisterController` | showForm, register | GET/POST /register |
| `PasswordResetController` | showLinkRequestForm, sendResetLinkEmail, showResetForm, reset | GET/POST /forgot-password, GET/POST /reset-password |

### Dashboard Controllers

| Controller | Methods | Routes |
|---|---|---|
| `DashboardController` | index | GET /dashboard |
| `QrCodeController` | index, create, store, show, destroy, download, preview, tempDownload | /dashboard/qr-codes/* |
| `MediaController` | index, upload, destroy, rename, createFolder, deleteFolder | /dashboard/media/* |
| `AnalyticsController` | index, data | GET /dashboard/analytics, GET /dashboard/analytics/data |
| `ProfileController` | edit, update, changePassword | GET/PUT /dashboard/profile, POST /dashboard/profile/password |
| `SubscriptionController` | index, upgrade | GET/POST /dashboard/subscription |

### Admin Controllers

| Controller | Methods | Routes |
|---|---|---|
| `DashboardController` | index | GET /admin |
| `UserController` | index, show, update, destroy | /admin/users/* |
| `RoleController` | index, store, update, destroy | /admin/roles/* |
| `FontController` | index, store, destroy, toggle | /admin/fonts/* |
| `PlanController` | index, create, store, edit, update, destroy | /admin/plans/* |
| `SettingController` | index, update | GET/POST /admin/settings |

### Public Controllers

| Controller | Methods | Routes |
|---|---|---|
| `QrRedirectController` | redirect | GET /qr/{code} |

---

## 7. Routes

### Public Routes
```
GET  /                              → welcome view
GET  /qr/{code}                     → QrRedirectController@redirect (qr.redirect)
```

### Auth Routes (guest middleware)
```
GET  /register                      → RegisterController@showForm (register)
POST /register                      → RegisterController@register
GET  /login                         → LoginController@showForm (login)
POST /login                         → LoginController@login
GET  /forgot-password               → PasswordResetController@showLinkRequestForm (password.request)
POST /forgot-password               → PasswordResetController@sendResetLinkEmail (password.email)
GET  /reset-password/{token}        → PasswordResetController@showResetForm (password.reset)
POST /reset-password                → PasswordResetController@reset (password.update)
POST /logout                        → LoginController@logout (logout)
```

### Dashboard Routes (auth, verified middleware)
```
GET  /dashboard                     → DashboardController@index (dashboard.index)
GET  /dashboard/qr-codes            → QrCodeController@index (dashboard.qr.index)
GET  /dashboard/qr-codes/create     → QrCodeController@create (dashboard.qr.create)
POST /dashboard/qr-codes            → QrCodeController@store (dashboard.qr.store)
GET  /dashboard/qr-codes/{qrCode}   → QrCodeController@show (dashboard.qr.show)
DELETE /dashboard/qr-codes/{qrCode} → QrCodeController@destroy (dashboard.qr.destroy)
GET  /dashboard/qr-codes/{qrCode}/download/{format} → QrCodeController@download (dashboard.qr.download)
POST /dashboard/qr-codes/preview    → QrCodeController@preview (dashboard.qr.preview)
GET  /dashboard/qr-temp-download/{filename} → QrCodeController@tempDownload (dashboard.qr.tempDownload)
GET  /dashboard/media               → MediaController@index (dashboard.media.index)
POST /dashboard/media/upload        → MediaController@upload (dashboard.media.upload)
DELETE /dashboard/media/{media}     → MediaController@destroy (dashboard.media.destroy)
POST /dashboard/media/{media}/rename → MediaController@rename (dashboard.media.rename)
POST /dashboard/media/folders       → MediaController@createFolder (dashboard.media.folders.create)
DELETE /dashboard/media/folders/{folder} → MediaController@deleteFolder (dashboard.media.folders.delete)
GET  /dashboard/analytics           → AnalyticsController@index (dashboard.analytics.index)
GET  /dashboard/analytics/data      → AnalyticsController@data (dashboard.analytics.data)
GET  /dashboard/profile             → ProfileController@edit (dashboard.profile.edit)
PUT  /dashboard/profile             → ProfileController@update (dashboard.profile.update)
POST /dashboard/profile/password    → ProfileController@changePassword (dashboard.profile.password)
GET  /dashboard/subscription        → SubscriptionController@index (dashboard.subscription.index)
POST /dashboard/subscription/upgrade → SubscriptionController@upgrade (dashboard.subscription.upgrade)
```

### Admin Routes (auth, verified, admin middleware)
```
GET  /admin                         → AdminDashboardController@index (admin.dashboard)
GET  /admin/users                   → UserController@index (admin.users.index)
GET  /admin/users/{user}            → UserController@show (admin.users.show)
PUT  /admin/users/{user}            → UserController@update (admin.users.update)
DELETE /admin/users/{user}          → UserController@destroy (admin.users.destroy)
GET  /admin/roles                   → RoleController@index (admin.roles.index)
POST /admin/roles                   → RoleController@store (admin.roles.store)
PUT  /admin/roles/{role}            → RoleController@update (admin.roles.update)
DELETE /admin/roles/{role}          → RoleController@destroy (admin.roles.destroy)
GET  /admin/fonts                   → FontController@index (admin.fonts.index)
POST /admin/fonts                   → FontController@store (admin.fonts.store)
DELETE /admin/fonts/{font}          → FontController@destroy (admin.fonts.destroy)
POST /admin/fonts/{font}/toggle     → FontController@toggle (admin.fonts.toggle)
GET  /admin/plans                   → PlanController@index (admin.plans.index)
GET  /admin/plans/create            → PlanController@create (admin.plans.create)
POST /admin/plans                   → PlanController@store (admin.plans.store)
GET  /admin/plans/{plan}/edit       → PlanController@edit (admin.plans.edit)
PUT  /admin/plans/{plan}            → PlanController@update (admin.plans.update)
DELETE /admin/plans/{plan}          → PlanController@destroy (admin.plans.destroy)
GET  /admin/settings                → SettingController@index (admin.settings.index)
POST /admin/settings                → SettingController@update (admin.settings.update)
```

---

## 8. Livewire Components

### QrGenerator
- **File**: `app/Livewire/QrGenerator.php`
- **View**: `resources/views/livewire/qr-generator.blade.php`
- **Purpose**: Real-time QR code designer with live preview
- **Properties**: title, type, content, foregroundColor, backgroundColor, gradientFrom, gradientTo, eyeColor, style, shape, pattern, size, errorCorrection, eyeStyle, frameStyle, frameColor, text, textPosition, textFont, textSize, textColor, logo, logoPreview, logoSize, logoPadding, margin, resolution, selectedTemplate, previewImage, generating
- **Methods**: mount, updated, setStyle, setShape, setFrameStyle, updatedLogo, removeLogo, selectTemplate, updatePreview, downloadPng, downloadSvg, save
- **Events emitted**: show-toast

---

## 9. Blade Views

### Layout Hierarchy

```
layouts/
├── admin.blade.php          # Admin panel layout
├── dashboard.blade.php      # User dashboard layout
├── app.blade.php            # Public layout
└── guest.blade.php          # Guest layout
```

### View Directories

| Directory | Purpose |
|---|---|
| `admin/` | Admin panel views (dashboard, fonts, plans, roles, settings, users) |
| `auth/` | Authentication views (login, register, password reset) |
| `components/` | Reusable Blade components |
| `dashboard/` | User dashboard views (analytics, media, qr-codes, subscription) |
| `emails/` | Email templates |
| `errors/` | Error pages |
| `layouts/` | Main layout templates |
| `livewire/` | Livewire component views |
| `public/` | Public-facing views |
| `qr/` | QR code related views |

---

## 10. JavaScript

### Files

| File | Purpose |
|---|---|
| `resources/js/app.js` | Main JS entry (imports bootstrap.js) |
| `resources/js/bootstrap.js` | Axios setup |
| `public/assets/js/app.js` | Compiled/published JS |

### Frontend Libraries (CDN)
- Bootstrap 5.3.3 JS
- Bootstrap Icons 1.11.3
- SweetAlert2
- Select2 4.1.0
- jQuery 3.7.1
- Chart.js 4 (analytics)
- SortableJS 1.15.6 (drag & drop)
- DataTables 1.13.8 (admin)

---

## 11. CSS

### Architecture
- **Tailwind CSS 4.0** — Primary utility framework (via Vite)
- **Bootstrap 5.3.3** — UI components (CDN)
- **Custom CSS** — Published in `public/assets/css/`

### Files
| File | Purpose |
|---|---|
| `resources/css/app.css` | Main stylesheet |
| `public/assets/css/app.css` | Published CSS |
| `public/assets/css/admin.css` | Admin panel styles |

### Fonts
- Vazirmatn (Google Fonts) — Persian font
- Dynamic fonts via PersianFontService

### Icons
- Bootstrap Icons 1.11.3 (CDN)

---

## 12. Authentication

### Guards
- `web` — Session-based (default)
- `sanctum` — API token (configured but not actively used)

### Login Flow
1. User visits `/login`
2. `LoginController@showForm` renders login view
3. `LoginController@login` validates credentials
4. On success, redirects to `/dashboard`
5. On failure, returns with errors

### Authorization
- **Roles**: super-admin, admin, manager, support, customer
- **Permissions**: users.*, qr_codes.*, media.*, fonts.*, plans.*, subscriptions.*, payments.*, settings.*, reports.*, analytics.*
- **Policies**: MediaPolicy (user ownership), QrCodePolicy (user ownership)

### Middleware Stack
1. `auth` — Authentication check
2. `verified` — Email verification
3. `admin` — Admin role check
4. `active-account` — Account active check
5. `verified-phone` — Phone verification
6. `subscription` — Active subscription check

---

## 13. Admin Panel

### Modules

| Module | Routes | Permissions |
|---|---|---|
| Dashboard | GET /admin | admin.dashboard |
| Users | CRUD /admin/users | admin.users.* |
| Roles & Permissions | CRUD /admin/roles | admin.roles.* |
| Fonts | CRUD /admin/fonts | admin.fonts.* |
| Plans | CRUD /admin/plans | admin.plans.* |
| Settings | GET/POST /admin/settings | admin.settings.* |

### Navigation
- Dashboard (home)
- Users management
- Roles & Permissions
- Fonts management
- Plans management
- Settings

---

## 14. User Dashboard

### Modules

| Module | Routes | Description |
|---|---|---|
| Dashboard | GET /dashboard | Overview with stats |
| QR Codes | CRUD /dashboard/qr-codes | Create, manage, download QR codes |
| Media | CRUD /dashboard/media | Upload, organize, delete files |
| Analytics | GET /dashboard/analytics | QR scan statistics |
| Profile | GET/PUT /dashboard/profile | User profile management |
| Subscription | GET/POST /dashboard/subscription | View/upgrade plans |

### Workflows
1. **QR Code Creation**: User fills form → Livewire generates preview → Save to DB
2. **Media Upload**: User selects file → Upload to storage → Create media record
3. **Analytics View**: Fetch scan data → Render Chart.js charts

---

## 15. QR Code System

### Architecture

```
User Input → QrGenerator (Livewire) → QrRenderer (Service) → Image Output
                                    ↓
                              QrCode (Model) → Database
                                    ↓
                              QrRedirectController → Public URL
                                    ↓
                              QrScan → Analytics
```

### Models
- `QrCode` — QR code definition with styling options
- `QrScan` — Scan tracking with device/browser/OS data

### Services
- `QrRenderer` — Generates QR images (PNG, SVG, DataURI)
  - Methods: fromModel(), fromLivewire(), toPng(), toSvg(), toSvgDataUri(), toPngDataUri()
  - Supports: colors, gradients, logos, eye styles, frames, text, patterns

### Flow
1. User designs QR in Livewire component with live preview
2. On save, creates QrCode record with unique_code
3. Dynamic QRs redirect via `/qr/{unique_code}`
4. Each scan creates QrScan record with metadata
5. Analytics aggregated from QrScan data

---

## 16. Media Manager

### Architecture

```
Upload → MediaController → Storage → Media Model
                                    ↓
                              MediaFolder (organization)
```

### Features
- File upload (images, documents)
- Folder organization (nested)
- File renaming
- Storage on `public` disk
- Folder creation/deletion

### Models
- `Media` — File records with user ownership
- `MediaFolder` — Hierarchical folder structure

---

## 17. Settings System

### Storage
- `settings` table with key-value pairs
- Grouped by `group` column (e.g., 'general')
- JSON `value` column for flexible data

### Flow
1. Admin visits `/admin/settings`
2. `SettingController@index` loads all settings
3. Admin edits values
4. `SettingController@update` saves changes
5. Available globally via `Setting::getGroup('general')`

---

## 18. Subscription System

### Architecture

```
Plan → Subscription → User
            ↓
      Payment (transactions)
            ↓
      Coupon (discounts)
```

### Models
- `Plan` — Pricing tiers with limits
- `Subscription` — User plan assignments
- `Payment` — Transaction records
- `Coupon` — Discount codes

### Flow
1. Admin creates Plans with limits
2. User selects plan → Subscription created
3. Payment processed → Payment record created
4. Subscription checked via middleware

---

## 19. Installed Libraries

### Backend (Composer)

| Package | Version | Purpose | Maintenance |
|---|---|---|---|
| `laravel/framework` | ^12.0 | Core framework | Active |
| `livewire/livewire` | 4.3.3 | Real-time UI | Active |
| `spatie/laravel-permission` | ^6.25 | RBAC | Active |
| `spatie/laravel-activitylog` | ^4.12 | Audit logging | Active |
| `chillerlan/php-qrcode` | ^6.0 | QR generation | Active |
| `barryvdh/laravel-dompdf` | ^3.1 | PDF generation | Active |
| `intervention/image` | ^3.11 | Image processing | Active |
| `morilog/jalali` | * | Persian dates | Active |

### Frontend (NPM)

| Package | Version | Purpose | Maintenance |
|---|---|---|---|
| `tailwindcss` | ^4.0.0 | CSS framework | Active |
| `vite` | ^7.0.7 | Build tool | Active |
| `axios` | ^1.11.0 | HTTP client | Active |

### CDN Libraries

| Library | Version | Purpose |
|---|---|---|
| Bootstrap | 5.3.3 | UI components |
| Bootstrap Icons | 1.11.3 | Icons |
| jQuery | 3.7.1 | DOM manipulation |
| SweetAlert2 | 11 | Alert dialogs |
| Select2 | 4.1.0 | Enhanced selects |
| Chart.js | 4 | Analytics charts |
| SortableJS | 1.15.6 | Drag & drop |
| DataTables | 1.13.8 | Data tables |

---

## 20. Remaining Features

### Complete
- ✅ User authentication (register, login, password reset)
- ✅ Role-based access control (5 roles, granular permissions)
- ✅ QR code generation with live preview
- ✅ QR code download (PNG, SVG)
- ✅ QR code scanning with analytics
- ✅ Media management (upload, folders, organize)
- ✅ Persian font management
- ✅ Subscription plans management
- ✅ Payment processing
- ✅ Admin dashboard with stats
- ✅ User dashboard with stats
- ✅ Activity logging
- ✅ Responsive design (Bootstrap + Tailwind)

### Missing
- ❌ API endpoints (no api.php)
- ❌ WebSocket/real-time notifications
- ❌ Two-factor authentication
- ❌ Email verification flow
- ❌ File versioning
- ❌ Batch operations
- ❌ Export functionality
- ❌ Webhook integrations

---

## 21. TODO

### Completed
- ✅ Core authentication system
- ✅ QR code generation & management
- ✅ Media management
- ✅ Subscription & payment system
- ✅ Admin panel
- ✅ Activity logging
- ✅ Persian localization
- ✅ Role-based permissions
- ✅ Card system removed
- ✅ Landing page builder removed
- ✅ Template system removed

### Missing
- ❌ API for mobile apps
- ❌ Real-time notifications
- ❌ Two-factor authentication
- ❌ Advanced analytics dashboard
- ❌ Batch QR generation
- ❌ QR code templates/presets saving
- ❌ Webhook integrations
- ❌ Export to Excel/PDF
- ❌ Advanced media optimization
- ❌ CDN integration
- ❌ Caching strategy
- ❌ Queue job processing
- ❌ Scheduled tasks (cron)
- ❌ Comprehensive test suite

### Recommended
- 📋 Add API authentication (Sanctum)
- 📋 Implement rate limiting
- 📋 Add comprehensive test coverage
- 📋 Implement caching for dashboard stats
- 📋 Add queue jobs for heavy operations
- 📋 Implement proper file versioning
- 📋 Add webhook system for integrations
- 📋 Implement advanced analytics
- 📋 Add batch operations for QR codes
- 📋 Implement proper error tracking (Sentry)

---

## 22. Code Quality Review

### Duplicated Code
- **Media upload logic** — Similar patterns in MediaController and QrCodeController (logo upload)
- **User ownership checks** — Repeated `$this->authorize('view', $model)` pattern

### Dead Code
- **Empty directories** — `app/Contracts/`, `app/Data/`, `app\Jobs/`, `app/Observers/`, multiple empty service directories
- **Unused config** — `config/permission.php` has wildcard_permission commented out

### Possible Bugs
- **QR scan without user check** — Public QR scans don't verify user ownership
- **Media upload size** — No explicit size validation in MediaController
- **Race condition** — Unique code generation in QrCode could have collisions under high load

### Performance Issues
- **No caching** — Dashboard stats queried on every page load
- **No pagination** — Some queries load all records
- **CDN dependencies** — Multiple CDN calls for Bootstrap, icons, jQuery

### Architecture Issues
- **Tight coupling** — Controllers directly use models without repository pattern
- **Missing repository layer** — Despite directory structure suggesting it
- **No form requests** — Validation done inline in controllers
- **Mixed CSS frameworks** — Both Tailwind and Bootstrap used

### Security Considerations
- **CSRF protection** — Enabled via Laravel middleware
- **XSS protection** — Blade auto-escaping enabled
- **SQL injection** — Protected via Eloquent ORM
- **Rate limiting** — Not explicitly configured
- **File upload validation** — Basic validation present

---

## 23. Final Summary

### Counts

| Category | Count |
|---|---|
| Total Models | 11 |
| Total Controllers | 18 (3 Auth + 6 Dashboard + 6 Admin + 1 Public + 2 empty) |
| Total Livewire Components | 1 (QrGenerator) |
| Total Services | 4 (QrRenderer, PersianFontService, MapProviderFactory + empty dirs) |
| Total Blade Views | ~50+ (across 12 directories) |
| Total Routes | ~45 |
| Total Database Tables | 22 |
| Total Migrations | 22 |
| Total Middleware | 7 custom |
| Total Policies | 2 (MediaPolicy, QrCodePolicy) |
| Total Events | 4 |
| Total Listeners | 2 |
| Total Jobs | 0 |
| Total JavaScript files | 2 (app.js, bootstrap.js) |
| Total CSS files | 2 (app.css, admin.css) |
| Total Mail classes | 4 |
| Total Enums | 3 |
| Total Traits | 2 |

### Architecture Summary

**CardX** is a Laravel 12 SaaS platform for QR code generation and management. The application uses:

- **Backend**: Laravel 12 with Livewire 4 for real-time UI, Spatie Permission for RBAC, and custom services for QR generation
- **Frontend**: Bootstrap 5.3.3 + Tailwind CSS 4 + Alpine.js (via Livewire) with Vite as the build tool
- **Database**: MySQL with 22 tables covering users, QR codes, scans, media, subscriptions, payments, and settings
- **Architecture**: Standard MVC with Service Layer pattern, organized by domain (Auth, Dashboard, Admin, Public)
- **Key Features**: QR code generation with live preview, media management, subscription billing, role-based access control, Persian localization
- **Current State**: Core platform is complete and functional. The project is ready for new feature development.

The codebase follows Laravel conventions with clear separation of concerns. The main areas for improvement are: adding comprehensive tests, implementing caching, adding API endpoints, and completing the test suite. The application is production-ready for its current feature set.
