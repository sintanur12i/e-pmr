<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\PeriodController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\CoachController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ManagementController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\AgendaController as PublicAgendaController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\PermissionController as AdminPermissionController;
use App\Http\Controllers\MemberUnitController;
use App\Http\Controllers\Admin\MemberUnitController as AdminMemberUnitController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\MaterialController as PublicMaterialController;
use App\Http\Controllers\GalleryController as PublicGalleryController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\MemberDashboardController;
use App\Http\Controllers\CandidateDashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [RegistrationController::class, 'create'])->name('register.create');
    Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/agendas', [PublicAgendaController::class, 'index'])->name('agendas.index');
    Route::post('/agendas/{agenda}/attend', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/materials', [PublicMaterialController::class, 'index'])->name('materials.index');
    Route::get('/galleries', [PublicGalleryController::class, 'index'])->name('galleries.index');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route Izin — dipakai bareng oleh member DAN candidate_member, TIDAK di dalam prefix apapun
    Route::middleware('role:member,candidate_member')->group(function () {
        Route::get('/agendas/{agenda}/permission', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/agendas/{agenda}/permission', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/my-units', [MemberUnitController::class, 'index'])->name('member-units.index');
        Route::post('/my-units/{unit}', [MemberUnitController::class, 'store'])->name('member-units.store');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('periods', PeriodController::class);
        Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
        Route::get('/registrations/{registration}', [AdminRegistrationController::class, 'show'])->name('registrations.show');
        Route::get('/registrations/{registration}/approveForm', [AdminRegistrationController::class, 'approveForm'])->name('registrations.approveForm');
        Route::post('/registrations/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
        Route::post('/registrations/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');
        Route::resource('coaches', CoachController::class);
        Route::resource('units', UnitController::class);
        Route::resource('managements', ManagementController::class);
        Route::resource('agendas', AgendaController::class);
        Route::get('/agendas/{agenda}/attendances', [AdminAttendanceController::class, 'show'])->name('attendances.show');
        Route::get('/permissions', [AdminPermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/{permission}', [AdminPermissionController::class, 'show'])->name('permissions.show');
        Route::post('/permissions/{permission}/approve', [AdminPermissionController::class, 'approve'])->name('permissions.approve');
        Route::post('/permissions/{permission}/reject', [AdminPermissionController::class, 'reject'])->name('permissions.reject');
        Route::get('/member-units', [AdminMemberUnitController::class, 'index'])->name('member-units.index');
        Route::post('/member-units/{memberUnit}/approve', [AdminMemberUnitController::class, 'approve'])->name('member-units.approve');
        Route::post('/member-units/{memberUnit}/reject', [AdminMemberUnitController::class, 'reject'])->name('member-units.reject');
        Route::resource('materials', AdminMaterialController::class)->except(['show']);
        Route::get('/galleries', [AdminGalleryController::class, 'index'])->name('galleries.index');
        Route::get('/galleries/create', [AdminGalleryController::class, 'create'])->name('galleries.create');
        Route::post('/galleries', [AdminGalleryController::class, 'store'])->name('galleries.store');
        Route::delete('/galleries/{gallery}', [AdminGalleryController::class, 'destroy'])->name('galleries.destroy');
        Route::delete('/galleries/agenda/{agenda}', [AdminGalleryController::class, 'destroyByAgenda'])->name('galleries.destroyByAgenda');
        Route::delete('/galleries/no-agenda', [AdminGalleryController::class, 'destroyWithoutAgenda'])->name('galleries.destroyWithoutAgenda');
    });

        Route::middleware('role:member')->prefix('member')->name('member.')->group(function () {
        Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    });

        Route::middleware('role:member')->group(function () {
        Route::resource('trainings', TrainingController::class)->except(['show']);
    });

    Route::middleware('role:candidate_member')->prefix('candidate')->name('candidate.')->group(function () {
        Route::get('/dashboard', [CandidateDashboardController::class, 'index'])->name('dashboard');
    });
});