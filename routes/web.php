<?php
// Admin Table QR Code Display
use App\Models\Table;
Route::middleware(['auth', 'admin'])->get('/admin/tables/{table}/show', function (Table $table) {
    return view('admin.tables.show', compact('table'));
})->name('admin.tables.show');
// Admin Menu Management (Menu Categories & Items)
use App\Http\Controllers\Admin\MenuController;
Route::prefix('admin/menu')->middleware(['auth', 'admin'])->name('admin.menu.')->group(function () {
    // Index
    Route::get('/', [MenuController::class, 'index'])->name('index');

    // Category CRUD
    Route::get('/category/create', [MenuController::class, 'createCategory'])->name('createCategory');
    Route::post('/category', [MenuController::class, 'storeCategory'])->name('storeCategory');
    Route::get('/category/{category}/edit', [MenuController::class, 'editCategory'])->name('editCategory');
    Route::put('/category/{category}', [MenuController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/category/{category}', [MenuController::class, 'destroyCategory'])->name('destroyCategory');

    // Item CRUD (nested under category)
    Route::get('/category/{category}/item/create', [MenuController::class, 'createItem'])->name('createItem');
    Route::post('/category/{category}/item', [MenuController::class, 'storeItem'])->name('storeItem');
    Route::get('/category/{category}/item/{item}/edit', [MenuController::class, 'editItem'])->name('editItem');
    Route::put('/category/{category}/item/{item}', [MenuController::class, 'updateItem'])->name('updateItem');
    Route::delete('/category/{category}/item/{item}', [MenuController::class, 'destroyItem'])->name('destroyItem');

    // Custom: Image upload (AJAX)
    Route::post('/item/image-upload', [MenuController::class, 'imageUpload'])->name('imageUpload');

    // Custom: Sorting update (AJAX)
    Route::post('/sort', [MenuController::class, 'updateSort'])->name('updateSort');
});

// API endpoints for AJAX (status toggles, sorting, etc.)
use Illuminate\Support\Facades\Route as RouteFacade;
RouteFacade::prefix('api/admin/menu')->middleware(['auth', 'admin'])->name('api.admin.menu.')->group(function () {
    // Toggle category active status
    RouteFacade::post('/category/{category}/toggle', [MenuController::class, 'toggleCategoryStatus'])->name('toggleCategoryStatus');
    // Toggle item available status
    RouteFacade::post('/item/{item}/toggle', [MenuController::class, 'toggleItemStatus'])->name('toggleItemStatus');
    // Update sorting (drag-and-drop)
    RouteFacade::post('/sort', [MenuController::class, 'apiUpdateSort'])->name('apiUpdateSort');
});
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodCategoryController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\FoodStyleController;
use App\Http\Controllers\InviteWorkerController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\AccountSetupController;


require __DIR__.'/auth.php';
use App\Http\Controllers\InviteController;
use Illuminate\Http\Request;

// Public: Accept Invitation (must be above any auth middleware group, now signed for extra security)
Route::get('/invitations/accept/{token}', [InviteController::class, 'accept'])
    ->name('invitations.accept')
    ->middleware('signed');

// Manual invitation token entry (for lost invitations)
Route::get('/invitations/recover', function () {
    return view('invitations.recover');
})->name('invitations.recover');

Route::post('/invitations/recover', function (Request $request) {
    $request->validate(['token' => 'required|string']);
    $url = URL::signedRoute('invitations.accept', ['token' => $request->token]);
    return redirect($url);
})->name('invitations.recover.submit');

// Admin-only: Invitations and User Management
use App\Http\Controllers\Admin\UserManagementController;
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/invitations', [InviteController::class, 'index'])->name('invitations.index');
    Route::post('/invitations', [InviteController::class, 'store'])->name('invitations.store');
    Route::post('/invitations/{invitation}/resend', [InviteController::class, 'resend'])->name('invitations.resend');
    Route::post('/invitations/{invitation}/revoke', [InviteController::class, 'revoke'])->name('invitations.revoke');

    // User management
    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/promote', [UserManagementController::class, 'promote'])->name('admin.users.promote');
    Route::post('/admin/users/{user}/demote', [UserManagementController::class, 'demote'])->name('admin.users.demote');
});

// Onboarding feedback for first admin after Google OAuth registration
Route::get('/onboarding/admin', function () {
    return view('onboarding.admin');
})->name('onboarding.admin');



// Landing Page
Route::get('/', [PageController::class, 'index'])->name('home');

// Register
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Demo Login (auto-login for demo user)
use App\Http\Controllers\DemoController;
Route::middleware(['web'])->group(function () {
    Route::get('/demo-login', [DemoController::class, 'login'])->middleware('throttle:10,1')->name('demo.login');
});

// Social Login
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Route::get('/worker', [WorkerController::class, 'index']);

// Worker Login
Route::get('/worker/login', [LoginController::class, 'workerLogin'])->name('worker.login');
Route::post('/worker/login', [LoginController::class, 'workerLoginStore'])->name('worker.login.store');

// Payments
Route::get('/payment', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');

// Customer Login
Route::get('/customer/login', [LoginController::class, 'customerLogin'])->name('customer.login');

// Restaurant Login
Route::get('/restaurant/login', [LoginController::class, 'restaurantLogin'])->name('restaurant.login');


// PUBLIC CUSTOMER MENU ROUTES (no auth required)
// Public customer menu routes with constraints
Route::get('/menu/{table}', [CustomerMenuController::class, 'showMenu'])
    ->where('table', '[0-9]+')
    ->name('customer.menu.public');
Route::get('/menu/{table}/category/{category}', [CustomerMenuController::class, 'viewMenu'])
    ->where(['table' => '[0-9]+', 'category' => '[0-9]+'])
    ->name('customer.menu.category');

// QR CODE GENERATION FOR ADMIN (requires auth & admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/tables/{table}/qr', [CustomerMenuController::class, 'generateQr'])
        ->where('table', '[0-9]+')
        ->name('admin.tables.qr');
    // Table management interface
    Route::get('/admin/tables', [TableController::class, 'index'])->name('admin.tables.index');
    Route::get('/admin/tables/create', [TableController::class, 'create'])->name('admin.tables.create');
    Route::post('/admin/tables', [TableController::class, 'store'])->name('admin.tables.store');
    Route::get('/admin/tables/{table}/edit', [TableController::class, 'edit'])
        ->where('table', '[0-9]+')
        ->name('admin.tables.edit');
    Route::put('/admin/tables/{table}', [TableController::class, 'update'])
        ->where('table', '[0-9]+')
        ->name('admin.tables.update');
    Route::delete('/admin/tables/{table}', [TableController::class, 'destroy'])
        ->where('table', '[0-9]+')
        ->name('admin.tables.destroy');
    Route::get('/admin/tables/{table}/download-qr', [TableController::class, 'downloadQr'])
        ->where('table', '[0-9]+')
        ->name('admin.tables.downloadQr');
});

Route::group(['middleware' => ['auth', 'check.customer.ban', 'no.customers', 'restrict.demo']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Pricing
    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');

    // Table
    Route::get('/tables/{id}', [TableController::class, 'index'])->name('tables.details');

    // Orders
    Route::post('/orders/bulk/prepare', [OrderController::class, 'bulkPrepare']);
    Route::post('/orders/bulk/deliver', [OrderController::class, 'bulkDeliver']);
    Route::post('/orders/bulk/complete', [OrderController::class, 'bulkComplete']);

    // setup restaurant controller
    Route::get('/setup-restaurant', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::post('/restaurant', [RestaurantController::class, 'store'])->name('restaurants.store');
    Route::put('/restaurant/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');

    // Food Style Routes
    Route::get('/food-styles', [FoodStyleController::class, 'index'])->name('food.styles.index');
    Route::get('/getfoodstyles', [FoodStyleController::class, 'getFoodStyles'])->name('food.styles.get');
    Route::post('/createupdatefoodstyle/{id}', [FoodStyleController::class, 'storeOrUpdate'])->name('food.styles.storeOrUpdate');
    Route::get('/editfoodstyle/{id}', [FoodStyleController::class, 'edit'])->name('food.style.edit');
    Route::delete('/deletefoodstyle/{id}', [FoodStyleController::class, 'delete'])->name('food.style.delete');

    Route::get('/food-styles/export', [FoodStyleController::class, 'export'])->name('food.styles.export');
    Route::post('/food-styles/import', [FoodStyleController::class, 'import'])->name('food.styles.import');

    // Food Style Categories
    Route::get('/food-categories', [FoodCategoryController::class, 'index'])->name('food.categories.index');
    Route::get('/getfoodcategories', [FoodCategoryController::class, 'getFoodCategories'])->name('food.categories.get');
    Route::post('/createupdatefoodcategory/{id}', [FoodCategoryController::class, 'storeOrUpdate'])->name('food.categories.storeOrUpdate');
    Route::get('/editfoodcategory/{id}', [FoodCategoryController::class, 'edit'])->name('food.category.edit');
    Route::delete('/deletefoodcategory/{id}', [FoodCategoryController::class, 'delete'])->name('food.category.delete');

    Route::get('/food-categories/export', [FoodCategoryController::class, 'export'])->name('food.categories.export');
    Route::post('/food-categories/import', [FoodCategoryController::class, 'import'])->name('food.categories.import');

    // setup food menu controller
    Route::get('/food-menu', [FoodItemController::class, 'index'])->name('food.menu.index');
    Route::delete('/getfooditems', [FoodItemController::class, 'getfooditems']);
    Route::post('/createupdatefooditem/{id}', [FoodItemController::class, 'storeOrUpdate'])
        ->where('id', '[0-9]+|add')
        ->name('fooditems.storeOrUpdate');
    Route::get('/editfooditem/{id}', [FoodItemController::class, 'edit'])->name('fooditem.edut');
    Route::get('/deletefooditem/{id}', [FoodItemController::class, 'delete'])->name('fooditem.delete');
    Route::post('/import-food-menu', [FoodItemController::class, 'import'])->name('foodMenu.import');
    Route::get('/export-food-menu', [FoodItemController::class, 'export'])->name('foodMenu.export');
    Route::post('/deletefoodmedia/{id}', [FoodItemController::class, 'deleteMedia'])->name('foodItem.deleteMedia');

    // setup tables controller
    Route::get('/setup-tables', [TableController::class, 'index'])->name('tables.index');
    Route::delete('/gettables', [TableController::class, 'gettables']);
    Route::post('/createupdatetable/{id}', [TableController::class, 'storeOrUpdate'])->name('tables.storeOrUpdate');
    Route::get('/edittable/{id}', [TableController::class, 'edit'])->name('table.edut');
    Route::get('/deletetable/{id}', [TableController::class, 'delete'])->name('table.delete');

    Route::get('/printqr/{id}', [TableController::class, 'printQr']);
    Route::get('/waittable/{id}', [TableController::class, 'waitTable']);

    // Invite Worker controller
    Route::get('/invite-workers', [WorkerController::class, 'index'])->name('workers.index');
    Route::get('/getworkers', [WorkerController::class, 'getworkers']);
    Route::post('/inviteworker', [InviteWorkerController::class, 'invite'])->name('invite.worker');
    Route::delete('/deleteworker/{id}', [WorkerController::class, 'delete'])->name('worker.delete');

    Route::get('/editworker/{id}', [WorkerController::class, 'edit'])->name('worker.edit');

    // Order controller
    Route::get('/view_table_requests', [CustomerOrderController::class, 'index'])->name('orders.index');

    Route::get('/getrequests', [CustomerOrderController::class, 'getrequests']);
    Route::get('/approve_orders', [CustomerOrderController::class, 'approve']);

    Route::get('/getapproveorderlist', [CustomerOrderController::class, 'getapproveorderlist']);

    Route::get('/getapprovedorderlist', [CustomerOrderController::class, 'getapprovedorderlist']);

    Route::get('/approveorder/{id}', [CustomerOrderController::class, 'approveOrder'])->name('order.approve');
    Route::get('/prepareorder/{id}', [CustomerOrderController::class, 'prepareOrder'])->name('order.prepare');
    Route::get('/deliverorder/{id}', [CustomerOrderController::class, 'deliverOrder'])->name('order.deliverorder');
    Route::get('/completeOrder/{id}', [CustomerOrderController::class, 'completeOrder'])->name('order.completed');

    Route::post('/approve-orders-bulk', [OrderController::class, 'approveBulk']);

    Route::get('/listentable', [CustomerOrderController::class, 'listen']);

    Route::get('/table_pings', [CustomerOrderController::class, 'tablePings']);
    Route::get('/get-table-pings', [CustomerOrderController::class, 'getTablePings'])->name('get.table.pings');
    Route::post('/table/pings/{id}/mark/attended', [CustomerOrderController::class, 'markTablePingAttended'])->name('table.ping.attend');
    Route::post('/table/pings/{id}/toggle/ban', [CustomerOrderController::class, 'toggleTablePingBan'])->name('table.ping.toggle.ban');

    Route::post('/tables/{id}/toggle-assignment', [TableController::class, 'toggleAssignment'])
        ->name('tables.toggle-assignment');

    Route::get('/banCustomer/{id}', [CustomerOrderController::class, 'banCustomer'])->name('order.ban');

    Route::delete('/orderPendingDetail/{id}', [CustomerOrderController::class, 'orderPendingDetail']);
    Route::delete('/orderCompletedDetail/{id}', [CustomerOrderController::class, 'orderCompletedDetail']);
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/report/daily-sales', [ReportController::class, 'dailySales']);
    Route::get('/report/monthly-sales', [ReportController::class, 'monthlySales']);
    Route::get('/report/yearly-sales', [ReportController::class, 'yearlySales']);

    // Subscriptions
    Route::get('/subscribe/create', [SubscriptionController::class, 'create'])->name('subscription.create');
    Route::get('/subscribe/approve', [SubscriptionController::class, 'approve'])->name('subscription.approve');
    Route::get('/subscribe/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Customer Rewards
    Route::get('/customer/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/data', [RewardController::class, 'data'])->name('customer.rewards');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    // API Keys
    Route::get('/api-keys', [ApiKeyController::class, 'index'])->name('api.index');
    Route::post('/api-keys', [ApiKeyController::class, 'store'])->name('api.store');
    Route::delete('/api-keys/{id}', [ApiKeyController::class, 'destroy'])->name('api.destroy');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    Route::post('/contact/bulk', [ContactController::class, 'sendBulk'])->name('contact.bulk');
});

Route::get('/expired-link', function () {
    return view('table.expired-link');
})->name('expired.link');

Route::get('/banned-notice', function () {
    return view('errors.banned');
})->name('banned.notice');


// Accept Invite (signed for extra security)
Route::get('/workers/invite/{token}', [InviteWorkerController::class, 'acceptInvite'])
    ->name('workers.accept-invite')
    ->middleware('signed');

// Access denied feedback
Route::get('/access-denied', function () {
    return view('errors.access-denied');
})->name('access.denied');

Route::get('/itemdetail/{id}', [CustomerOrderController::class, 'itemDetail']);
Route::delete('/getorderlist/{id}', [CustomerOrderController::class, 'getorderlist']);
Route::post('/createupdateorder/{id}', [CustomerOrderController::class, 'storeOrUpdate'])->name('orders.storeOrUpdate');
Route::get('/editorder/{id}', [CustomerOrderController::class, 'edit'])->name('order.edut');
Route::get('/deleteorder/{id}', [CustomerOrderController::class, 'delete'])->name('order.delete');

Route::get('/submittokithcen/{id}', [CustomerOrderController::class, 'submittokithcen'])->name('submittokithcen');

Route::get('/callwaiter/{id}', [TableController::class, 'callWaiter'])->name('call.waiter');
Route::get('/endcalling/{id}', [TableController::class, 'endcalling'])->name('call.end');

Route::get('/getfoodsbycategory/{id}', [CustomerOrderController::class, 'getFoodsByCategory']);

Route::get('/symlink', function () {
    Artisan::call('storage:link');

    return 'Storage link created';
});

// Account type selection routes
Route::middleware(['auth'])->group(function () {
    Route::get('/account/type/select', [AccountTypeController::class, 'show'])->name('account.type.select');
    Route::post('/account/type/save', [AccountTypeController::class, 'save'])->name('account.type.save');
});

// Account setup flow
Route::middleware(['auth'])->group(function () {
    Route::get('/account/type/select', [AccountSetupController::class, 'showAccountType'])->name('account.type.select');
    Route::post('/account/type/save', [AccountSetupController::class, 'updateAccountType'])->name('account.type.save');
});
