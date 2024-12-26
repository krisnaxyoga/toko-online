<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductVariant;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\CheckOngkirController;
use App\Http\Controllers\StoreSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
//  jika user belum login

Route::group(['middleware' => 'guest'], function() {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/dologin', [AuthController::class, 'dologin'])->name('dologin');
    Route::get('/register', [CustomerController::class, 'register'])->name('register');
    Route::post('/doregister', [CustomerController::class, 'doregister'])->name('doregister');
});

// untuk superadmin dan pegawai
Route::group(['middleware' => ['auth', 'checkrole:1,2']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/redirect', [RedirectController::class, 'cek']);
});

// untuk superadmin
Route::group(['middleware' => ['auth', 'checkrole:1']], function() {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('/category', CategoryController::class);
    Route::get('/subscategory/{id}', [CategoryController::class, 'subscategory'])->name('subscategory');
    Route::resource('/product', ProductController::class);
    Route::resource('/productvariant', ProductVariant::class);
    Route::get('/productvariant/index/{id}', [ProductVariant::class, 'index'])->name('productvariant.creatus.index');
    Route::get('/productvariant/create/{id}', [ProductVariant::class, 'create'])->name('productvariant.creatus');
    Route::get('/store-setting', [StoreSettingController::class, 'index'])->name('store-setting');
    Route::post('/store-setting', [StoreSettingController::class, 'update'])->name('store-setting.update');

    Route::get('/report', [ReportController::class, 'report'])->name('report');
    Route::get('/order/approve/{id}', [ReportController::class, 'approve'])->name('admin.order.approve');
    Route::get('/order/cancel/{id}', [ReportController::class, 'cancel'])->name('admin.order.cancel');
});

// untuk pegawai
Route::group(['middleware' => ['auth', 'checkrole:2']], function() {
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/home', [CustomerController::class, 'index'])->name('home');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cartstore', [CartController::class, 'cart'])->name('cart.store');
    Route::post('/cartsupdate/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
    Route::get('/checkoutSuccess', [CartController::class, 'checkoutSuccess'])->name('checkout-success');

    Route::post('/upload-bukti', [CartController::class, 'uploadBukti'])->name('upload-bukti');
});
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontEndController::class, 'index'])->name('front.index');

Route::get('provinces', [CheckOngkirController::class, 'province'])->name('provinces');
Route::get('cities', [CheckOngkirController::class, 'city'])->name('cities');
Route::post('check-ongkir', [CheckOngkirController::class, 'checkOngkir'])->name('check-ongkir');