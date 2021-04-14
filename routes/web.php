<?php

use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
//Route::get('/', function () {
//    return view('welcome');
//
//})->middleware(['auth.shopify'])->name('home');

Route::group(['middleware'=>['auth.shopify','shop-active']], function () {
    // customer routes
        Route::get('/', 'UserController@user_dashboard')->middleware(['billable'])->name('home');
        Route::post('setup-page', 'UserController@setup_page')->name('setup-page');

        Route::get('user-shop-detail', 'UserController@user_shop_detail')->name('user-shop-detail');
        Route::post('user-shop-detail-save', 'UserController@user_shop_detail_save')->name('user-shop-detail-save');

        Route::get('countries', 'UserController@countries_index')->name('countries');

         Route::post('country-user-save', 'UserController@country_user_save')->name('country-user-save');

        Route::get('user-plans', 'UserController@user_plans')->name('user-plans');

        Route::get('sms-campaign-index', 'UserController@sms_campaign_index')->name('sms-campaign-index');

        Route::post('sms-campaign-save', 'UserController@sms_campaign_save')->name('sms-campaign-save');
        Route::post('edit-campaign-save/{id}', 'UserController@edit_campaign_save')->name('edit-campaign-save');
        Route::get('edit-status-campaign-save/{id}', 'UserController@edit_status_campaign_save')->name('edit-status-campaign-save');
        Route::get('delete-campaign/{id}', 'UserController@delete_campaign')->name('delete-campaign');

        Route::get('enable-sms', 'UserController@enable_sms')->name('enable-sms');

        Route::get('/customers', 'AdminController@customers_index')->name('customers');
        Route::get('/welcome-campaign', 'UserController@welcome_campaign')->name('welcome-campaign');
        Route::post('/welcome-sms-campaign-save', 'UserController@welcome_sms_campaign_save')->name('welcome-sms-campaign-save');

        Route::get('/abandoned-cart-campaign', 'UserController@abandoned_cart_campaign')->name('abandoned-cart-campaign');
        Route::post('/abandoned-cart-campaign-save', 'UserController@abandoned_cart_campaign_save')->name('abandoned-cart-campaign-save');

//        Route::get('/orderconfirm-campaign-save', 'UserController@orderconfirm_campaign')->name('orderconfirm-campaign-save');
        Route::post('/orderconfirm-campaign-save', 'UserController@order_confirm_campaign_save')->name('orderconfirm-campaign-save');
        Route::post('/orderrefund-campaign-save', 'UserController@order_refund_campaign_save')->name('orderrefund-campaign-save');
        Route::post('/orderdispatch-campaign-save', 'UserController@order_dispatch_campaign_save')->name('orderdispatch-campaign-save');

        Route::get('sms-triggers-index', 'UserController@sms_trigger_index')->name('sms-triggers-index');

        Route::get('get-reports', 'UserController@get_reports')->name('get-reports');

        Route::get('/customer-sync', 'CustomerController@customer_sync')->name('customer-sync');

        Route::post('stripe', [StripeController::class, 'stripePost'])->name('stripe.process.payment');


    Route::get('user', function(){
            $user = \App\User::find(auth::user()->id);
            dd($user->countries);
        });

        Route::get('test', 'CustomerController@test');
        Route::get('test', 'CustomerController@test');
        Route::get('/webhook','UserController@webhooks');
});

Route::group(['middleware'=>['auth.shopify']], function () {

    Route::post('shop-detail', 'UserController@shop_detail')->name('shop-detail');
    Route::get('shop-status-detail/{id}', 'UserController@shop_status_detail')->name('shop-status-detail');
    Route::post('shop-status-detail-save', 'UserController@shop_status_detail_save')->name('shop-status-detail-save');
    Route::post('country-shop-preferences-save', 'UserController@country_shop_preferences_save')->name('country-shop-preferences-save');

});


Route::get('/base', function() {
    $auth = "Basic ". base64_encode("shopifyapp.textglobal:TGshopify1!");

    dd($auth);
});


// admin routes
Route::group(['middleware'=>['auth', 'prevent-back-history', 'prevent-user-access']], function () {
    Route::get('/home', 'HomeController@index');
//    Route::get('/admin-dashboard', 'AdminController@admin_dashboard')->name('admin-dashboard');
    Route::get('/shops', 'AdminController@shops_index')->name('shops');

    Route::get('/plans', 'AdminController@plans_index')->name('plans');
    Route::post('/plan-save', 'AdminController@plan_save')->name('plan-save');
    Route::post('/edit-plan-save/{id}', 'AdminController@edit_plan_save')->name('edit-plan-save');
    Route::get('/delete-plan/{id}', 'AdminController@plan_delete')->name('delete-plan');

    Route::get('/credits', 'AdminController@credits_index')->name('credits');
    Route::post('/credits-save', 'AdminController@credits_save')->name('credits-save');
    Route::post('/edit-credits-save/{id}', 'AdminController@edit_credits_save')->name('edit-credits-save');
    Route::get('/delete-credits/{id}', 'AdminController@credits_delete')->name('credits-plan');

//    Route::get('/package-create', 'AdminController@package_create')->name('package-create');
});

Route::get('/admin', function() {
    return view('auth.login');
})->name('admin-login');

Route::post('/admin-login-post','Auth\LoginController@login')->name('admin-login-post');

Route::get('/admin-logout', '\App\Http\Controllers\HomeController@logout')->name('admin-logout');


