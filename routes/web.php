<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\CardController;
use App\Http\Controllers\Dashboard\CardPreviewController;
use App\Http\Controllers\Dashboard\QrCodeController;
use App\Http\Controllers\Dashboard\MediaController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\AnalyticsController;
use App\Http\Controllers\Dashboard\SubscriptionController;
use App\Http\Controllers\Dashboard\LandingPageController;
use App\Http\Controllers\Dashboard\LandingPageBuilderController;
use App\Http\Controllers\Dashboard\LandingPagePreviewController;
use App\Http\Controllers\Dashboard\LandingPagePublishController;
use App\Http\Controllers\Dashboard\LandingPageAnalyticsController;
use App\Http\Controllers\Api\BlockApiController;
use App\Http\Controllers\Api\AutosaveApiController;
use App\Http\Controllers\Api\VersionApiController;
use App\Http\Controllers\Api\AnalyticsApiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminCardController;
use App\Http\Controllers\Admin\AdminCardPreviewController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\TemplatePreviewController;
use App\Http\Controllers\Admin\FontController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminLandingPageController;
use App\Http\Controllers\Admin\AdminLandingPageTemplateController;
use App\Http\Controllers\Admin\AdminLandingPageWidgetController;
use App\Http\Controllers\Public\PublicCardController;
use App\Http\Controllers\Public\PublicLandingPageController;
use App\Http\Controllers\Public\QrRedirectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/qr/{code}', [QrRedirectController::class, 'redirect'])->name('qr.redirect');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (User Panel)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Cards
    Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::get('/cards/{card}/edit', [CardController::class, 'edit'])->name('cards.edit');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    Route::post('/cards/{card}/publish', [CardController::class, 'publish'])->name('cards.publish');
    Route::get('/cards/{card}/builder', [CardController::class, 'builder'])->name('cards.builder');

    // Card Preview
    Route::get('/cards/{card}/preview', [CardPreviewController::class, 'preview'])->name('cards.preview');
    Route::get('/cards/{card}/preview-page', [CardPreviewController::class, 'previewPage'])->name('cards.preview-page');

    // Card sections AJAX
    Route::post('/cards/{card}/sections', [CardController::class, 'addSection'])->name('cards.sections.add');
    Route::put('/cards/{card}/sections/{section}', [CardController::class, 'updateSection'])->name('cards.sections.update');
    Route::delete('/cards/{card}/sections/{section}', [CardController::class, 'deleteSection'])->name('cards.sections.delete');
    Route::post('/cards/{card}/sections/reorder', [CardController::class, 'reorderSections'])->name('cards.sections.reorder');

    // Section Items
    Route::get('/cards/{card}/sections/{section}/items/list', [CardController::class, 'listItems'])->name('cards.sections.items.list');
    Route::post('/cards/{card}/sections/{section}/items', [CardController::class, 'addItem'])->name('cards.sections.items.add');
    Route::delete('/cards/{card}/sections/{section}/items/{item}', [CardController::class, 'deleteItem'])->name('cards.sections.items.delete');

    // Card Image Upload
    Route::post('/cards/{card}/upload/{field}', [CardController::class, 'uploadImage'])->name('cards.upload');

    // QR Codes
    Route::get('/qr-codes', [QrCodeController::class, 'index'])->name('qr.index');
    Route::get('/qr-codes/create', [QrCodeController::class, 'create'])->name('qr.create');
    Route::post('/qr-codes', [QrCodeController::class, 'store'])->name('qr.store');
    Route::get('/qr-codes/{qrCode}', [QrCodeController::class, 'show'])->name('qr.show');
    Route::delete('/qr-codes/{qrCode}', [QrCodeController::class, 'destroy'])->name('qr.destroy');
    Route::get('/qr-codes/{qrCode}/download/{format}', [QrCodeController::class, 'download'])->name('qr.download');
    Route::post('/qr-codes/preview', [QrCodeController::class, 'preview'])->name('qr.preview');
    Route::get('/qr-temp-download/{filename}', [QrCodeController::class, 'tempDownload'])->name('qr.tempDownload');

    // Media
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('/media/{media}/rename', [MediaController::class, 'rename'])->name('media.rename');
    Route::post('/media/folders', [MediaController::class, 'createFolder'])->name('media.folders.create');
    Route::delete('/media/folders/{folder}', [MediaController::class, 'deleteFolder'])->name('media.folders.delete');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/data', [AnalyticsController::class, 'data'])->name('analytics.data');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');

    // Subscription
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');

    // Landing Pages
    Route::get('/landing-pages', [LandingPageController::class, 'index'])->name('landing-pages.index');
    Route::get('/landing-pages/create', [LandingPageController::class, 'create'])->name('landing-pages.create');
    Route::post('/landing-pages', [LandingPageController::class, 'store'])->name('landing-pages.store');
    Route::get('/landing-pages/{landingPage}/edit', [LandingPageController::class, 'edit'])->name('landing-pages.edit');
    Route::put('/landing-pages/{landingPage}', [LandingPageController::class, 'update'])->name('landing-pages.update');
    Route::delete('/landing-pages/{landingPage}', [LandingPageController::class, 'destroy'])->name('landing-pages.destroy');
    Route::post('/landing-pages/{landingPage}/publish', [LandingPagePublishController::class, 'toggle'])->name('landing-pages.publish');
    Route::get('/landing-pages/{landingPage}/builder', [LandingPageBuilderController::class, 'index'])->name('landing-pages.builder');
    Route::get('/landing-pages/{landingPage}/preview', [LandingPagePreviewController::class, 'preview'])->name('landing-pages.preview');
    Route::get('/landing-pages/{landingPage}/analytics', [LandingPageAnalyticsController::class, 'index'])->name('landing-pages.analytics');
    Route::get('/landing-pages/{landingPage}/submissions', [LandingPageController::class, 'submissions'])->name('landing-pages.submissions');
    Route::post('/landing-pages/{landingPage}/qr', [LandingPageController::class, 'generateQr'])->name('landing-pages.qr');
});

/*
|--------------------------------------------------------------------------
| Landing Page Builder API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('api')->name('api.')->group(function () {
    Route::get('/landing-pages/{landingPage}/blocks/tree', [BlockApiController::class, 'tree']);
    Route::get('/landing-pages/{landingPage}/blocks/{block}', [BlockApiController::class, 'show']);
    Route::post('/landing-pages/{landingPage}/blocks', [BlockApiController::class, 'store']);
    Route::put('/landing-pages/{landingPage}/blocks/{block}', [BlockApiController::class, 'update']);
    Route::delete('/landing-pages/{landingPage}/blocks/{block}', [BlockApiController::class, 'destroy']);
    Route::post('/landing-pages/{landingPage}/blocks/reorder', [BlockApiController::class, 'reorder']);
    Route::post('/landing-pages/{landingPage}/blocks/{block}/duplicate', [BlockApiController::class, 'duplicate']);
    Route::post('/landing-pages/{landingPage}/autosave', [AutosaveApiController::class, 'save']);
    Route::get('/landing-pages/{landingPage}/versions', [VersionApiController::class, 'index']);
    Route::post('/landing-pages/{landingPage}/versions/{version}/restore', [VersionApiController::class, 'restore']);
    Route::post('/analytics/event', [AnalyticsApiController::class, 'track']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Roles & Permissions
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Cards
    Route::get('/cards', [AdminCardController::class, 'index'])->name('cards.index');
    Route::get('/cards/{card}/preview', [AdminCardPreviewController::class, 'preview'])->name('cards.preview');
    Route::get('/cards/{card}/preview-page', [AdminCardPreviewController::class, 'previewPage'])->name('cards.preview-page');
    Route::delete('/cards/{card}', [AdminCardController::class, 'destroy'])->name('cards.destroy');
    Route::post('/cards/{card}/feature', [AdminCardController::class, 'feature'])->name('cards.feature');

    // Templates
    Route::resource('templates', TemplateController::class)->except(['show']);
    Route::get('/templates/{template}/preview', [TemplatePreviewController::class, 'preview'])->name('templates.preview');
    Route::get('/templates/{template}/preview-page', [TemplatePreviewController::class, 'previewPage'])->name('templates.preview-page');

    // Fonts
    Route::get('/fonts', [FontController::class, 'index'])->name('fonts.index');
    Route::post('/fonts', [FontController::class, 'store'])->name('fonts.store');
    Route::delete('/fonts/{font}', [FontController::class, 'destroy'])->name('fonts.destroy');
    Route::post('/fonts/{font}/toggle', [FontController::class, 'toggle'])->name('fonts.toggle');

    // Plans
    Route::resource('plans', PlanController::class)->except(['show']);

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Landing Pages
    Route::get('/landing-pages', [AdminLandingPageController::class, 'index'])->name('landing-pages.index');
    Route::get('/landing-pages/{landingPage}/edit', [AdminLandingPageController::class, 'edit'])->name('landing-pages.edit');
    Route::put('/landing-pages/{landingPage}', [AdminLandingPageController::class, 'update'])->name('landing-pages.update');
    Route::delete('/landing-pages/{landingPage}', [AdminLandingPageController::class, 'destroy'])->name('landing-pages.destroy');

    // Landing Page Templates
    Route::get('/landing-page-templates', [AdminLandingPageTemplateController::class, 'index'])->name('landing-page-templates.index');
    Route::get('/landing-page-templates/create', [AdminLandingPageTemplateController::class, 'create'])->name('landing-page-templates.create');
    Route::post('/landing-page-templates', [AdminLandingPageTemplateController::class, 'store'])->name('landing-page-templates.store');
    Route::get('/landing-page-templates/{landingPageTemplate}/edit', [AdminLandingPageTemplateController::class, 'edit'])->name('landing-page-templates.edit');
    Route::put('/landing-page-templates/{landingPageTemplate}', [AdminLandingPageTemplateController::class, 'update'])->name('landing-page-templates.update');
    Route::delete('/landing-page-templates/{landingPageTemplate}', [AdminLandingPageTemplateController::class, 'destroy'])->name('landing-page-templates.destroy');

    // Landing Page Widgets
    Route::get('/landing-page-widgets', [AdminLandingPageWidgetController::class, 'index'])->name('landing-page-widgets.index');
    Route::post('/landing-page-widgets/{widget}/toggle', [AdminLandingPageWidgetController::class, 'toggle'])->name('landing-page-widgets.toggle');
});

/*
|--------------------------------------------------------------------------
| Public Landing Page Routes
|--------------------------------------------------------------------------
*/

Route::get('/p/{slug}', [PublicLandingPageController::class, 'show'])->name('lp.show');
Route::post('/p/{slug}/password', [PublicLandingPageController::class, 'verifyPassword'])->name('lp.password');
Route::post('/p/{slug}/submit', [PublicLandingPageController::class, 'submitForm'])->name('lp.form.submit');

/*
|--------------------------------------------------------------------------
| Public Card View (must be last to avoid catching other routes)
|--------------------------------------------------------------------------
*/

Route::get('/{slug}', [PublicCardController::class, 'show'])->name('card.show');

/*
|--------------------------------------------------------------------------
| Template Preview (temporary - for development)
|--------------------------------------------------------------------------
*/

Route::get('/preview/wedding/{template?}', function ($template = 'luxury') {
    $templates = [
        'luxury' => 'Luxury',
        'minimal' => 'Minimal',
        'elegant' => 'Elegant',
        'floral' => 'Floral',
        'royal' => 'Royal',
        'vintage' => 'Vintage',
        'romantic' => 'Romantic',
        'modern' => 'Modern',
        'persian' => 'Persian Traditional',
        'garden' => 'Garden',
        'boho' => 'Boho',
        'dark-luxury' => 'Dark Luxury',
        'gold-theme' => 'Gold Theme',
        'watercolor' => 'Watercolor',
        'glassmorphism' => 'Glassmorphism',
        'premium-animated' => 'Premium Animated',
        'silk' => 'Silk',
        'starry-night' => 'Starry Night',
        'rose-gold' => 'Rose Gold',
        'art-deco' => 'Art Deco',
    ];

    if (!isset($templates[$template])) {
        abort(404);
    }

    $card = (object) [
        'id' => 1,
        'title' => 'علی',
        'slug' => 'preview-ali-sara',
        'description' => 'با کمال میل شما را به مراسم عروسی دعوت می‌کنیم',
        'seo_title' => 'دعوت نامه عروسی علی و سارا',
        'seo_description' => 'مراسم عروسی علی محمدی و سارا کریمی',
        'phone' => '۰۹۱۲۱۲۳۴۵۶۷',
        'email' => 'ali@example.com',
        'website' => 'https://example.com',
        'address' => 'تهران، خیابان ولیعصر، سالن باغ بهشت',
        'map_lat' => 35.6892,
        'map_lng' => 51.3890,
        'theme_color' => null,
        'meta' => [
            'partner_name' => 'سارا کریمی',
            'wedding_date' => now()->addDays(45)->toIso8601String(),
        ],
        'sections' => collect([
            (object) ['id' => 1, 'type' => 'contact', 'title' => 'اطلاعات تماس', 'content' => null, 'sort_order' => 0, 'is_visible' => true, 'items' => collect()],
            (object) ['id' => 2, 'type' => 'social', 'title' => 'شبکه‌های اجتماعی', 'content' => null, 'sort_order' => 1, 'is_visible' => true, 'items' => collect()],
            (object) ['id' => 3, 'type' => 'gallery', 'title' => 'گالری عکس', 'content' => null, 'sort_order' => 2, 'is_visible' => true, 'items' => collect()],
            (object) ['id' => 4, 'type' => 'timeline', 'title' => 'داستان عشق ما', 'content' => null, 'sort_order' => 3, 'is_visible' => true, 'items' => collect([
                (object) ['name' => 'آشنایی', 'description' => 'در یک روز بهاری در دانشگاه با هم آشنا شدیم', 'sort_order' => 0],
                (object) ['name' => 'نامزدی', 'description' => 'پس از ۳ سال آشنایی، نامزد کردیم', 'sort_order' => 1],
                (object) ['name' => 'عروسی', 'description' => 'الان وقتشه که زندگی مشترکمون رو شروع کنیم', 'sort_order' => 2],
            ])],
        ]),
        'socialLinks' => collect([
            (object) ['platform' => 'instagram', 'url' => 'https://instagram.com/example', 'sort_order' => 0],
            (object) ['platform' => 'telegram', 'url' => 'https://t.me/example', 'sort_order' => 1],
            (object) ['platform' => 'whatsapp', 'url' => 'https://wa.me/989121234567', 'sort_order' => 2],
        ]),
        'galleryItems' => collect([
            (object) ['image_path' => 'https://picsum.photos/400/400?random=1', 'caption' => 'عکس ۱', 'sort_order' => 0],
            (object) ['image_path' => 'https://picsum.photos/400/400?random=2', 'caption' => 'عکس ۲', 'sort_order' => 1],
            (object) ['image_path' => 'https://picsum.photos/400/400?random=3', 'caption' => 'عکس ۳', 'sort_order' => 2],
            (object) ['image_path' => 'https://picsum.photos/400/400?random=4', 'caption' => 'عکس ۴', 'sort_order' => 3],
        ]),
        'products' => collect(),
        'services' => collect(),
        'testimonials' => collect(),
        'faqs' => collect(),
        'qrCodes' => collect(),
        'template' => null,
        'user' => (object) ['name' => 'علی محمدی', 'id' => 1],
    ];

    return view("templates.wedding.{$template}", compact('card'));
})->name('preview.wedding');
