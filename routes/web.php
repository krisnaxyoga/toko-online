<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductVariant;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FrontEndController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\WishlistController;
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
    Route::resource('/pageabout', PageController::class);
    Route::resource('/admin/gallery', GalleryController::class);
    Route::get('/report', [ReportController::class, 'report'])->name('report');
    Route::get('/order/approve/{id}', [ReportController::class, 'approve'])->name('admin.order.approve');
    Route::get('/order/cancel/{id}', [ReportController::class, 'cancel'])->name('admin.order.cancel');
    Route::post('/slider/store', [StoreSettingController::class, 'addslidder'])->name('admin.setting.slider.store');
    Route::put('/slider/update/{id}', [StoreSettingController::class, 'editslider'])->name('admin.setting.slider.update');
    Route::delete('/slider/destroy/{id}', [StoreSettingController::class, 'deleteslider'])->name('admin.setting.slider.destroy');

    Route::post('/resiadd',[AdminController::class, 'resiadd'])->name('resi.add.admin');
    Route::get('/pesanan',[ReportController::class, 'pesanan'])->name('pesanan');
    Route::get('/detailpesananorder/{id}',[ReportController::class, 'detail'])->name('detailpesananorder');
    Route::post('/detailpesananorder-update/{id}',[ReportController::class, 'updateorder'])->name('detailpesananorder-update');
    Route::get('/pelanggan',[CustomerController::class, 'customerdata'])->name('pelanggan');
    Route::resource('/bank-account', BankController::class);

    Route::get('/admin/allpayment', [ReportController::class, 'reportpayment'])->name('admin.allpayment');
    Route::get('/admin/payment/detail/{id}', [ReportController::class, 'detailpayment'])->name('admin.payment.detail');
    Route::post('/admin/payment/status/{id}', [ReportController::class, 'paymentstatus'])->name('admin.payment.status');

    Route::resource('/news', NewsController::class);
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
    Route::get('/myorder', [CustomerController::class, 'myorder'])->name('myorder');
    Route::get('/order-detail/{id}', [CustomerController::class, 'showorder'])->name('order-detail');
    Route::post('/upload-bukti', [CartController::class, 'uploadBukti'])->name('upload-bukti');
    Route::post('/uploadbuktigagal',[CartController::class, 'uploadBuktigagal'])->name('upload-bukti-gagal');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/wishlist/store/{id}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/destroy/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/invoice/{id}', [CartController::class, 'invoice'])->name('invoice');
    Route::post('/review', [CustomerController::class, 'review'])->name('submit-review');
    Route::post('/send-invoice-email/{id}', [CustomerController::class, 'sendInvoiceEmail'])->name('send-invoice-email');
    Route::get('/barang-diterima/{id}',[CustomerController::class, 'barangditerima'])->name('barang.diterima');
    Route::get('/mypayment', [CustomerController::class, 'mypayment'])->name('mypayment');
    Route::get('/mypaymentdetail/{id}', [CustomerController::class, 'mypaymentdetail'])->name('mypayment.detail');
    Route::get('/myreview', [CustomerController::class, 'myreview'])->name('myreview');
    Route::get('/createmyreview/{id}', [CustomerController::class, 'createmyreview'])->name('createmyreview');
    Route::get('/customer-profile',[CustomerController::class,'profile'])->name('profile-customer');
    Route::post('/customer-update',[CustomerController::class,'updateprofile'])->name('customer.update');

    Route::post('/addaddressprofile',[CustomerController::class,'addaddress'])->name('addaddressprofile');
    Route::post('/updateaddressprofile',[CustomerController::class,'updateaddress'])->name('updatedaddressprofile');
    Route::delete('/deleteaddressprofile/{id}',[CustomerController::class,'deleteaddress'])->name('deleteaddressprofile');
    Route::get('/activeinactive/{id}',[CustomerController::class,'activeinactive'])->name('activeinactive');


    Route::get('/batalkanorder/cancel/{id}', [ReportController::class, 'cancel'])->name('cust.order.cancel');
});
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontEndController::class, 'index'])->name('front.index');
Route::get('/shop', [FrontEndController::class, 'product'])->name('front.shop');
Route::get('/about', [FrontEndController::class, 'about'])->name('front.about');
Route::get('/contact', [FrontEndController::class, 'contact'])->name('front.contact');
Route::get('/frony/gallery', [FrontEndController::class, 'gallery'])->name('front.gallery');
Route::get('/category-product', [FrontEndController::class, 'categoryproduct'])->name('front.category.product');
Route::get('/detailproduct/{id}', [FrontEndController::class, 'productDetail'])->name('front.detailproduct');
Route::get('/pagedetail/{id}',[NewsController::class, 'show'])->name('front.pagedetail');
Route::get('/berita',[NewsController::class, 'frontpage'])->name('front.berita');


Route::get('provinces', [CheckOngkirController::class, 'province'])->name('provinces');
Route::get('cities', [CheckOngkirController::class, 'city'])->name('cities');
Route::post('check-ongkir', [CheckOngkirController::class, 'checkOngkir'])->name('check-ongkir');