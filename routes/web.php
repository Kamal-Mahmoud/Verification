<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\MerchantEnsureEmailIsVerified;
use App\Http\Middleware\MerchantMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix("merchant")->name("merchant.")->group(function(){
    Route::view("/","merchant.index")->name("home")
    ->middleware([MerchantMiddleware::class , MerchantEnsureEmailIsVerified::class]);
    require __DIR__.'/merchantAuth.php';
});