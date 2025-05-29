<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ClubForgotPasswordController;
use App\Http\Controllers\Auth\ClubResetPasswordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\ClubController;

Auth::routes();

Route::get('/club/password/reset', [ClubForgotPasswordController::class, 'showLinkRequestForm'])->name('club.password.request');
Route::post('/club/password/email', [ClubForgotPasswordController::class, 'sendResetLinkEmail'])->name('club.password.email');
Route::get('/club/password/reset/{token}', [ClubResetPasswordController::class, 'showResetForm'])->name('club.password.reset');
Route::post('/club/password/reset', [ClubResetPasswordController::class, 'reset'])->name('club.password.update');

Route::get('/club/verify-email/{encoded_id}', [App\Http\Controllers\Auth\ClubVerificationController::class, 'showVerificationForm'])->name('club.verify.email.form');
Route::post('/club/verify-email/{encoded_id}', [App\Http\Controllers\Auth\ClubVerificationController::class, 'verifyEmail'])->name('club.verify.email');
Route::get('/club/resend-verification-code/{encoded_id}', [App\Http\Controllers\Auth\ClubVerificationController::class, 'resendCode'])->name('club.resend.verification.code');

Route::get('/admin/club/verify-email', [App\Http\Controllers\Auth\ClubVerificationController::class, 'showAdminVerificationForm'])->name('admin.club.verify.email.form')->middleware('App\Http\Middleware\AdminMiddleware');
Route::post('/admin/club/verify-email', [App\Http\Controllers\Auth\ClubVerificationController::class, 'verifyAdminClubEmail'])->name('admin.club.verify.email')->middleware('App\Http\Middleware\AdminMiddleware');
Route::get('/admin/club/resend-verification-code', [App\Http\Controllers\Auth\ClubVerificationController::class, 'resendAdminVerificationCode'])->name('admin.club.resend.verification.code')->middleware('App\Http\Middleware\AdminMiddleware');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::fallback([HomeController::class, 'fallback']);

Route::post('/destroy', [LoginController::class, 'destroy'])->name('logoutusers');

Route::get('/verify-email', [RegisterController::class, 'verifyEmail'])->name('verify.email.form');

Route::post('/verify-email', [RegisterController::class, 'verifyCode'])->name('verify.email.code');

Route::get('/complete-profile', [App\Http\Controllers\Auth\ProfileCompletionController::class, 'show'])->name('profile.complete.show')->middleware('auth');
Route::post('/complete-profile', [App\Http\Controllers\Auth\ProfileCompletionController::class, 'update'])->name('profile.complete')->middleware('auth');
Route::post('/skip-profile-completion', [App\Http\Controllers\Auth\ProfileCompletionController::class, 'skip'])->name('profile.skip')->middleware('auth');

Route::get('/my-profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show')->middleware(['auth', 'App\Http\Middleware\ProfileCompletionCheck']);
Route::get('/my-profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit')->middleware(['auth', 'App\Http\Middleware\ProfileCompletionCheck']);
Route::put('/my-profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware(['auth', 'App\Http\Middleware\ProfileCompletionCheck']);

Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSend'])->name('contact.send');

Route::get('/coaches', [HomeController::class, 'coaches'])->name('all_coaches');
Route::get('/profile/{encoded_id}', [HomeController::class, 'profile'])->name('profile')->middleware(['App\Http\Middleware\ProfileCompletionCheck']);

Route::get('/payment', [HomeController::class, 'payment'])->name('payment')->middleware(['auth', 'App\Http\Middleware\ProfileCompletionCheck']);
Route::post('/process-payment', [HomeController::class, 'processPayment'])->name('process.payment')->middleware(['auth', 'App\Http\Middleware\ProfileCompletionCheck']);

Route::get('/clubs', [HomeController::class, 'clubs'])->name('all_clubs');
Route::get('/club_details/{club}', [HomeController::class, 'clubDetails'])->name('club_details');

Route::prefix('dashboard')->middleware('dashboard')->group(function () {
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.all');
    Route::get('/notifications/read/{id}', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::get('/notifications/check-new', [App\Http\Controllers\NotificationController::class, 'checkNewNotifications'])->name('notifications.checkNew');
    Route::get('/notifications/dropdown-content', [App\Http\Controllers\NotificationController::class, 'getDropdownContent'])->name('notifications.dropdownContent');

    Route::middleware('App\Http\Middleware\CoachMiddleware')->group(function () {
        Route::get('/coach', [CoachController::class, 'index'])->name('coach.dashboard');
        Route::get('/coach/club', [CoachController::class, 'clubDetails'])->name('coach.club');
        Route::get('/coach/clients', [CoachController::class, 'clients'])->name('coach.clients');
        Route::post('/coach/clients/assign', [CoachController::class, 'assignClient'])->name('coach.clients.assign');

        Route::get('/coach/search', [CoachController::class, 'search'])->name('coach.search');
        Route::post('/coach/search', [CoachController::class, 'searchResults'])->name('coach.search.results');

        Route::get('/coach/profile', [CoachController::class, 'profile'])->name('coach.profile');
        Route::get('/coach/profile/edit', [CoachController::class, 'editProfile'])->name('coach.profile.edit');
        Route::put('/coach/profile/update', [CoachController::class, 'updateProfile'])->name('coach.profile.update');
    });

    Route::middleware('App\Http\Middleware\AdminMiddleware')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

        Route::get('/all-users', [AdminController::class, 'allUsers'])->name('admin.users');
        Route::get('/user/{encoded_id}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::delete('/user/{encoded_id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/admin/users/trashed', [AdminController::class, 'trashedUsers'])->name('admin.users.trashed');
        Route::post('/user/{encoded_id}/restore', [AdminController::class, 'restoreUser'])->name('admin.users.restore');

        Route::get('/all-coaches', [AdminController::class, 'allCoaches'])->name('admin.coaches');
        Route::get('/coach/{encoded_id}', [AdminController::class, 'showCoach'])->name('admin.coaches.show');
        Route::delete('/coach/{encoded_id}', [AdminController::class, 'deleteCoach'])->name('admin.coaches.delete');
        Route::get('/admin/coaches/trashed', [AdminController::class, 'trashedCoaches'])->name('admin.coaches.trashed');
        Route::post('/coach/{encoded_id}/restore', [AdminController::class, 'restoreCoach'])->name('admin.coaches.restore');

        Route::get('/admin/search', [AdminController::class, 'search'])->name('admin.search');
        Route::post('/admin/search', [AdminController::class, 'searchResults'])->name('admin.search.results');

        Route::get('/admin/profile', [AdminController::class, 'viewProfile'])->name('admin.profile');
        Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
        Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        Route::get('/all-clubs', [ClubController::class, 'adminClubs'])->name('admin.clubs');
        Route::get('/clubs/trashed', [ClubController::class, 'trashedClubs'])->name('admin.trashed-clubs');
        Route::post('/clubs/{encoded_id}/restore', [ClubController::class, 'restore'])->name('clubs.restore');
        Route::delete('/clubs/{encoded_id}/force-delete', [ClubController::class, 'forceDelete'])->name('clubs.force-delete');
        Route::resource('clubs', ClubController::class)->parameters([
            'clubs' => 'encoded_id'
        ]);
    });

    Route::middleware('App\Http\Middleware\ClubMiddleware')->group(function () {
        Route::get('/club', [ClubController::class, 'index'])->name('club.dashboard');
        Route::get('/profile', [ClubController::class, 'profile'])->name('club.profile');
        Route::get('/search', [ClubController::class, 'search'])->name('club.search');
        Route::post('/search', [ClubController::class, 'searchResults'])->name('club.search.results');

        Route::get('/myclub/edit/{encoded_id}', [ClubController::class, 'edit'])->name('myclub.edit');
        Route::put('/myclub/update/{encoded_id}', [ClubController::class, 'update'])->name('myclub.update');

        Route::get('/users', [ClubController::class, 'users'])->name('club.users');
        Route::get('/users/create', [ClubController::class, 'createUser'])->name('club.users.create');
        Route::post('/users/store', [ClubController::class, 'storeUser'])->name('club.users.store');
        Route::get('/users/verify-email', [ClubController::class, 'showVerifyUserEmail'])->name('club.users.verify.email.form');
        Route::post('/users/verify-email', [ClubController::class, 'verifyUserEmail'])->name('club.users.verify.email');
        Route::get('/users/resend-verification', [ClubController::class, 'resendUserVerification'])->name('club.users.resend.verification');
        Route::get('/users/trashed', [ClubController::class, 'trashedUsers'])->name('club.users.trashed');
        Route::get('/users/{encoded_id}/edit', [ClubController::class, 'editUser'])->name('club.users.edit');
        Route::get('/users/{encoded_id}', [ClubController::class, 'showUser'])->name('club.users.show');
        Route::put('/users/{encoded_id}', [ClubController::class, 'updateUser'])->name('club.users.update');
        Route::delete('/users/{encoded_id}', [ClubController::class, 'deleteUser'])->name('club.users.delete');
        Route::patch('/users/{encoded_id}/restore', [ClubController::class, 'restoreUser'])->name('club.users.restore');

        Route::get('/subscription-plans', [ClubController::class, 'subscriptionPlans'])->name('club.subscription-plans');
        Route::get('/subscription-plans/create', [ClubController::class, 'createSubscriptionPlan'])->name('club.subscription-plans.create');
        Route::post('/subscription-plans', [ClubController::class, 'storeSubscriptionPlan'])->name('club.subscription-plans.store');
        Route::get('/subscription-plans/{encoded_id}/edit', [ClubController::class, 'editSubscriptionPlan'])->name('club.subscription-plans.edit');
        Route::put('/subscription-plans/{encoded_id}', [ClubController::class, 'updateSubscriptionPlan'])->name('club.subscription-plans.update');
        Route::delete('/subscription-plans/{encoded_id}', [ClubController::class, 'deleteSubscriptionPlan'])->name('club.subscription-plans.delete');
        Route::get('/subscription-plans/trashed', [ClubController::class, 'trashedSubscriptionPlans'])->name('club.subscription-plans.trashed');
        Route::post('/subscription-plans/{encoded_id}/restore', [ClubController::class, 'restoreSubscriptionPlan'])->name('club.subscription-plans.restore');

        Route::get('/coaches', [ClubController::class, 'coaches'])->name('club.coaches');
        Route::get('/coaches/create', [ClubController::class, 'createCoach'])->name('club.coaches.create');
        Route::post('/coaches/store', [ClubController::class, 'storeCoach'])->name('club.coaches.store');
        Route::get('/coaches/trashed', [ClubController::class, 'trashedCoaches'])->name('club.coaches.trashed');
        Route::get('/coaches/{encoded_id}/edit', [ClubController::class, 'editCoach'])->name('club.coaches.edit');
        Route::get('/coaches/{encoded_id}', [ClubController::class, 'showCoach'])->name('club.coaches.show');
        Route::put('/coaches/{encoded_id}', [ClubController::class, 'updateCoach'])->name('club.coaches.update');
        Route::delete('/coaches/{encoded_id}', [ClubController::class, 'deleteCoach'])->name('club.coaches.delete');
        Route::patch('/coaches/{encoded_id}/restore', [ClubController::class, 'restoreCoach'])->name('club.coaches.restore');

        Route::get('/coaches/verify/{tempId}', [ClubController::class, 'showCoachVerificationForm'])->name('club.coach.verify.form');
        Route::post('/coaches/verify/{tempId}', [ClubController::class, 'verifyCoachEmail'])->name('club.coach.verify');
        Route::get('/coaches/resend-code/{tempId}', [ClubController::class, 'resendCoachVerificationCode'])->name('club.coach.resend.code');
    });
});
