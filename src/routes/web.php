<?php

use Illuminate\Support\Facades\Route;
use App\Models\Shop;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\StaffAuthenticatedSessionController;

use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReservationCheckinController;

use App\Http\Controllers\Admin\OwnerController as AdminOwnerController;
use App\Http\Controllers\Admin\NotifyController as AdminNotifyController;
use App\Http\Controllers\Admin\ShopOwnerController as AdminShopOwnerController;

use App\Http\Controllers\Owner\ShopController as OwnerShopController;
use App\Http\Controllers\Owner\ReservationController as OwnerReservationController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{shop}', [ShopController::class, 'show'])->name('shops.show');

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/thanks', fn () => view('auth.thanks'))->name('thanks');


Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/email/verify', fn () => view('auth.verify-email'))->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('thanks');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/payments/success', [ReservationController::class, 'success'])->name('payment.success');
Route::get('/payments/cancel',  [ReservationController::class, 'cancel'])->name('payment.cancel');

Route::middleware('signed')->get('/r/{reservation}/checkin', [ReservationCheckinController::class, 'show'])->name('reservations.checkin');

Route::get('/shops/{shop}/ratings', [RatingController::class, 'index'])->name('ratings.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/shops/{shop}/favorite', [FavoriteController::class, 'store'])->name('favorites.toggle');

    Route::post('/reserve/{shop}', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reserve/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/done', function () {
    $reservation = session('reservation');
    return view('reservations.done', compact('reservation'));
    })->name('reservations.done');


    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::match(['put','patch'], '/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');

    Route::get('/shops/{shop}/rate', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/shops/{shop}/rate', [RatingController::class, 'store'])->name('ratings.store');

    Route::post('/reservations/{reservation}/checkout', [ReservationController::class, 'retry'])->name('checkout');
});

Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
    Route::resource('owners', AdminOwnerController::class)->names('owners');
    Route::post('notify', AdminNotifyController::class)->name('notify');
    Route::get('/shops/{shop}/owner', [AdminShopOwnerController::class, 'edit'])->name('shops.owner.edit');
    Route::put('/shops/{shop}/owner', [AdminShopOwnerController::class, 'update'])->name('shops.owner.update');
});

Route::prefix('owner')->name('owner.')->middleware(['auth','role:owner,admin'])->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/shop', [OwnerShopController::class, 'editOrCreate'])->name('shop.editOrCreate');
    Route::post('/shop', [OwnerShopController::class, 'store'])->name('shop.store');
    Route::put('/shop/{shop}', [OwnerShopController::class, 'update'])->name('shop.update');
    Route::get('/reservations', [OwnerReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [OwnerReservationController::class, 'show'])->name('reservations.show');
    Route::post('/reservations/{reservation}/confirm', [OwnerReservationController::class, 'confirm'])->name('reservations.confirm');
    Route::post('/reservations/{reservation}/cancel', [OwnerReservationController::class, 'cancel'])->name('reservations.cancel');
});

Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/login', [StaffAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [StaffAuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::post('/logout', [StaffAuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
});

Route::prefix('staff')->middleware(['auth','role:owner,admin'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        $shops = Shop::with('owner')
            ->where('owner_id', $user->id)
            ->get();

        return view('owner.dashboard', compact('shops'));
    })->name('staff.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reservations/{reservation}/qr', [ReservationController::class, 'qr'])
        ->name('reservations.qr');
});
