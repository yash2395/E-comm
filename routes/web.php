<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\TempImagesController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\AdminloginController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\ProductSubCategoryController;



// Route::get('/test', function () {
//     orderEmail(39);
//     });

Route::get ('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');
Route::get('/cart',[CartController::class,'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class,'updateCart'])->name('front.updateCart');
Route::post('/delete-item',[CartController::class,'deleteItem'])->name('front.deleteItem');
Route::get('/checkout',[CartController::class,'checkout'])->name('front.checkout');
Route::post('/process-checkout',[CartController::class,'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{id}',[CartController::class,'thankyou'])->name('front.thanks');
Route::post('/get-order-summery',[CartController::class,'getOrderSummery'])->name('front.getOrderSummery');
Route::get('/page/{slug}',[FrontController::class,'page'])->name('front.page');
Route::get('/forgot-password',[AuthController::class,'forgotPassword'])->name('front.forgotPassword');
Route::post('/process-forgot-password',[AuthController::class,'processForgotPassword'])->name('front.processForgotPassword');
Route::get('/reset-password/{token}',[AuthController::class,'resetPassword'])->name('front.resetPassword');
Route::post('/process-reset-password',[AuthController::class,'processRequestPassword'])->name('front.processRequestPassword');
Route::post('/save-rating/{product}',[ShopController::class,'saveRating'])->name('front.saveRating');

Route::post('/send-contact-email',[FrontController::class,'sendContactEmail'])->name('front.sendContactEmail');

// Apply Discount
Route::post('/apply-discount',[CartController::class,'applyDiscount'])->name('front.discount');
Route::post('/remove-discount',[CartController::class,'removeCoupon'])->name('front.removeCoupon');
Route::post('/add-to-wishlist',[FrontController::class,'addToWishList'])->name('front.addToWishList');




Route::group(['prefix' => 'account'],function(){
    Route::group(['middleware' => 'guest'],function(){
        Route::get('/register',[AuthController::class,'register'])->name('account.register');
        Route::post('/process-register',[AuthController::class,'processRegister'])->name('account.processRegister');
        Route::get('/login',[AuthController::class,'login'])->name('account.login');
        Route::post('/authenticate',[AuthController::class,'authenticate'])->name('account.authenticate');

    });
         Route::group(['middleware' => 'auth'],function(){
        Route::get('/profile',[AuthController::class,'profile'])->name('account.profile');
        Route::post('/update-profile',[AuthController::class,'updateProfile'])->name('account.updateProfile');
        Route::post('/update-address',[AuthController::class,'updateAddress'])->name('account.updateAddress');
        Route::get('/change-password',[AuthController::class,'showChangePasswordForm'])->name('account.changePassword');
        Route::post('/change-password',[AuthController::class,'changePassword'])->name('account.changePassword');
        Route::get('/logout',[AuthController::class,'logout'])->name('account.logout');
        Route::get('/order',[AuthController::class,'orders'])->name('account.orders');
        Route::get('/my-wishlist',[AuthController::class,'wishlist'])->name('account.wishlist');
        Route::post('/remove-product-from-wishlist',[AuthController::class,'removeProductFromWishlist'])->name('account.removeProductFromWishlist');
        Route::get('/order-detail/{orderId}',[AuthController::class,'orderDetail'])->name('account.orderDetail');
    });
});


Route::group(['prefix' => 'admin'],function(){
    Route::group(['middleware' => 'admin.guest'],function(){
        Route::get('/login',[AdminloginController::class,'index'])->name('admin.login');
        Route::post('/authenticate',[AdminloginController::class,'authenticate'])->name('admin.authenticate');

    });

    Route::group(['middleware' => 'admin.auth'],function(){
        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');

        // Category Routes
        Route::get('/categories',[CategoryController::class,'index'])->name('category');
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create');
        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');
        Route::post('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        Route::get('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');
        Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');

        // SubCategory Routes
        Route::get('/subcategories',[SubCategoryController::class,'index'])->name('subcategory');
        Route::post('/subcategories/store',[SubCategoryController::class,'store'])->name('subcategories.store');
        Route::get('/subcategories/create',[SubCategoryController::class,'create'])->name('subcategories.create');
        Route::get('/subcategories/{subcategory}/edit',[SubCategoryController::class,'edit'])->name('subcategories.edit');
        Route::post('/subcategories/{subcategory}',[SubCategoryController::class,'update'])->name('subcategories.update');
        Route::get('/subcategories/{subcategory}',[SubCategoryController::class,'destroy'])->name('subcategories.delete');

        // Brands Routes
        Route::get('/brands',[BrandController::class,'index'])->name('brands');
        Route::get('/brands/create',[BrandController::class,'create'])->name('brands.create');
        Route::post('/brands/store',[BrandController::class,'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit',[BrandController::class,'edit'])->name('brands.edit');
        Route::post('/brands/{brand}',[BrandController::class,'update'])->name('brands.update');

        // Product Routes
        Route::get('/products',[ProductController::class,'index'])->name('products');
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create');
        Route::post('/products/store',[ProductController::class,'store'])->name('products.store');
        Route::get('getSubcategory',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');
        Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');
        Route::post('/products/{product}',[ProductController::class,'update'])->name('products.update');
        Route::get('/products/{product}',[ProductController::class,'destroy'])->name('products.delete');
        Route::get('/get-products',[ProductController::class,'getProducts'])->name('products.getProducts');

        // Shipping Rooutes
        Route::get('/shipping/create',[ShippingController::class,'create'])->name('shipping.create');
        Route::post('/shipping/store',[ShippingController::class,'store'])->name('shipping.store');
        Route::get('/shipping/edit/{shipping}',[ShippingController::class,'edit'])->name('shipping.edit');
        Route::post('/shipping/update/{shipping}',[ShippingController::class,'update'])->name('shipping.update');
        Route::post('/shipping/delete/{shipping}',[ShippingController::class,'delete'])->name('shipping.delete');

        // Coupon Routes
        Route::get('/coupons',[DiscountCodeController::class,'index'])->name('coupons');
        Route::get('/coupons/create',[DiscountCodeController::class,'create'])->name('coupons.create');
        Route::post('/coupons/store',[DiscountCodeController::class,'store'])->name('coupons.store');
        // Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit');
        // Route::post('/categories/{category}',[CategoryController::class,'update'])->name('categories.update');
        // Route::get('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete');
        // Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');

        // Orders Route
        Route::get('/orders',[OrderController::class,'index'])->name('orders');
        Route::get('/orders/{id}',[OrderController::class,'detail'])->name('orders.details');
        Route::post('/orders/change-status/{id}',[OrderController::class,'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::post('/orders/send-email/{id}',[OrderController::class,'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');

        // User routes
        Route::get('/users',[UserController::class,'index'])->name('users');
        Route::get('/users/create',[UserController::class,'create'])->name('users.create');
        Route::post('/users/store',[UserController::class,'store'])->name('users.store');
        Route::get('/users/edit/{users}',[UserController::class,'edit'])->name('users.edit');
        Route::post('/users/update/{users}',[UserController::class,'update'])->name('users.update');
        Route::post('/users/delete/{users}',[UserController::class,'delete'])->name('users.delete');


        // Pages Routes
        Route::get('/pages',[PageController::class,'index'])->name('pages');
        Route::get('/pages/create',[PageController::class,'create'])->name('pages.create');
        Route::post('/pages/store',[PageController::class,'store'])->name('pages.store');
        Route::get('/pages/edit/{pages}',[PageController::class,'edit'])->name('pages.edit');
        Route::post('/pages/update/{pages}',[PageController::class,'update'])->name('pages.update');
        Route::post('/pages/delete/{pages}',[PageController::class,'delete'])->name('pages.delete');

        // Settings Route
        Route::get('/change-password',[SettingController::class,'showChangePasswordForm'])->name('admin.showChangePasswordForm');
        Route::post('/process-password',[SettingController::class,'processChangePassword'])->name('admin.processChangePassword');


    });
});
