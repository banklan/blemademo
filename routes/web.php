<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::post('/users/logout', 'Auth\LoginController@userLogout')->name('user.logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/my_orders', 'HomeController@index');
Route::get('/my_order/{order}', 'HomeController@index');
Route::get('/account', 'HomeController@index');
Route::get('/messages', 'HomeController@index');
Route::get('/payment_successful', 'HomeController@index');

// Route::get('/checkout', 'HomeController@checkout');
Route::get('/checkout', 'CheckoutController@checkout')->name('checkout');
Route::post('/checkout', 'CheckoutController@checkoutPost');


Route::get('/contact_us', 'PagesController@contactUs')->name('contact_us');
Route::get('/about_us', 'PagesController@aboutUs')->name('about_us');
Route::get('/how_it_works', 'PagesController@howItWorks')->name('how_it_works');
Route::get('/terms_conditions', 'PagesController@termsAndConditions')->name('terms');

//products routes
Route::get('/raw_foodstuffs', 'ProductPageController@getFoodStuff')->name('foodstuff');
Route::get('/organic-foods/{id}/{name}', 'ProductPageController@getFoodStuff');
Route::get('/raw_foodstuff/{id}/images/products/organic/{any}', 'ProductPageController@getFoodStuff');
Route::get('/images/products/organic/{any}', 'ProductPageController@getFoodStuff');
Route::get('/soup-stew-ingredients', 'ProductPageController@getSoupRecipes')->name('soup_recipes');
Route::get('/soup-stew-ingredients/{id}/{slug}', 'ProductPageController@getSoupRecipes');
Route::get('/images/products/recipes/{any}', 'ProductPageController@getSoupRecipes');
Route::get('/fish-meat', 'ProductPageController@getProteins')->name('proteins');
Route::get('/fish-meat/{id}/{slug}', 'ProductPageController@getProteins');
Route::get('/images/products/proteins/{slug}', 'ProductPageController@getProteins');
Route::get('/fruits-vegetables', 'ProductPageController@getFruitsVegs')->name('fruits_vegs');
Route::get('/fruits-vegetables/{id}/{slug}', 'ProductPageController@getFruitsVegs');
Route::get('/images/products/fruits_vegs/{slug}', 'ProductPageController@getProteins');
Route::get('/herbs-spices', 'ProductPageController@getHerbsSpices')->name('herbs_spices');
Route::get('/herbs-spices/{id}/{slug}', 'ProductPageController@getHerbsSpices');
Route::get('/images/products/herbs_spices/{slug}', 'ProductPageController@getHerbsSpices');
Route::get('/coffee-tea', 'ProductPageController@getCoffeeTea')->name('coffee_tea');
Route::get('/coffee-tea/{id}/{slug}', 'ProductPageController@getCoffeeTea');
Route::get('/images/products/cofee_tea/{slug}', 'ProductPageController@getCoffeeTea');
Route::get('/beverages-drinks-water', 'ProductPageController@getBeveragesDrinks')->name('drinks');
Route::get('/beverages-drinks-water/{id}/{slug}', 'ProductPageController@getBeveragesDrinks');
Route::get('/images/products/drinks/{slug}', 'ProductPageController@getBeveragesDrinks');
Route::get('/breakfast-cereal', 'ProductPageController@getBreakfastCereal')->name('breakfast_cereal');
Route::get('/breakfast-cereal/{id}/{slug}', 'ProductPageController@getBreakfastCereal');
Route::get('/images/products/cereal/{slug}', 'ProductPageController@getBreakfastCereal');
Route::get('/eggs-dairy', 'ProductPageController@getEggsDairy')->name('eggs_dairy');
Route::get('/eggs-dairy/{id}/{slug}', 'ProductPageController@getEggsDairy');
Route::get('/images/products/eggs_dairy/{slug}', 'ProductPageController@getEggsDairy');
Route::get('/toiletries', 'ProductPageController@getToiletries')->name('toiletries');
Route::get('/toiletries/{id}/{slug}', 'ProductPageController@getToiletries');
Route::get('/images/products/toiletries/{slug}', 'ProductPageController@getToiletries');
Route::get('/household', 'ProductPageController@getHousehold')->name('household');
Route::get('/household/{id}/{slug}', 'ProductPageController@getHousehold');
Route::get('/images/products/household/{slug}', 'ProductPageController@getHousehold');
Route::get('/groceries', 'ProductPageController@getGroceries')->name('groceries');
Route::get('/groceries/{id}/{slug}', 'ProductPageController@getGroceries');
Route::get('/images/products/groceries/{slug}', 'ProductPageController@getGroceries');
Route::get('/kitchen', 'ProductPageController@getKitchen')->name('kitchen');
Route::get('/kitchen/{id}/{slug}', 'ProductPageController@getKitchen');
Route::get('/special_order', 'ProductPageController@getSpecialOrder')->name('special.order')->middleware('auth');
Route::get('/special_services/services', 'ProductPageController@services')->name('services');
Route::get('/search', 'ProductPageController@getFoodStuff');

//cart
Route::get('/my_cart', 'ProductPageController@cart')->name('cart');

//api calls
Route::get('/get_products/{id}', 'ProductController@getProducts');
Route::post('/search_for_product', 'ProductController@search');
Route::get('/get_product/{id}', 'ProductController@getSingleProduct');
Route::get('/get_similar_products/{cat}/{id}', 'ProductController@getSimilarProducts');
Route::get('/get_products_categories/{id}', 'ProductController@getProductsByCats');
Route::post('/post_special_orders', 'OrderController@sendSpecialOrder');
Route::get('/get_services', 'ServicesController@getServices');
Route::post('/pay_ondelivery', 'OrderController@payOnDelivery');
Route::get('/gen_trnx_id_for_payment', 'OrderController@genOrderIdForPymnt');
Route::post('/checkout_save', 'OrderController@saveOrderPayment');
Route::post('/send_orderreceived_emails/{id}', 'OrderController@sendOrderReceivedEmails');
Route::get('/get_pending_orders', 'OrderController@getUserPendingOrders');
Route::get('/get_all_orders_for_user', 'OrderController@getUserOrders');
Route::get('/get_special_orders_for_user', 'OrderController@getUserSpecialOrders');
Route::get('/get_userorder/{id}', 'OrderController@getUserOrder');
Route::get('/get_last_five_orders', 'OrderController@getLastFiveOrders');
Route::get('/get_userorders_byorder_id/{order}', 'OrderController@getUserOrdersByOrderId');
Route::get('/get_delivery_address', 'OrderController@getDeliveryContact');
Route::post('/update_users_address', 'UsersProfileController@updateUsersAddress');
Route::get('/get_user_account', 'UsersProfileController@getUserAccount');
Route::get('/get_user_locations', 'UsersProfileController@getLocations');
Route::post('/update_user_profile', 'UsersProfileController@update');
Route::post('/confirm_old_pswd', 'UsersProfileController@confirmOldPswd');
Route::post('/update_user_password', 'UsersProfileController@updateUserPassword');
Route::post('/post_user_messages', 'MessageController@sendMessage');
Route::get('/get_user_messages', 'MessageController@getMessages');
Route::get('/get_users_unread_msgs', 'MessageController@getUnreadMessages');
Route::get('/get_order_charges', 'ChargesController@getCharges');
Route::post('/send_message_contact', 'PagesController@sendMessage');
Route::post('/send_enquiry_email', 'PagesController@sendEnquiryEmail');
Route::get('/reset_unread_messages', 'MessageController@resetUnreadMsgs');

Route::get('/get_product_s3_image/{product}', 'ProductController@getProductS3Image');

//admin routes
Route::prefix('admin')->group(function(){
    Route::get('/login', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    Route::post('/password/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset', 'Auth\AdminResetPasswordController@reset');
    Route::get('/password/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::get('/', 'AdminController@index')->name('admin.home');
    Route::get('/orders', 'AdminController@index');
    Route::get('/orders/{id}/{order}', 'AdminController@index');
    Route::get('/messages', 'AdminController@index');
    Route::get('/messages/{userId}/{userSlug}', 'AdminController@index');
    Route::get('/users', 'AdminController@index');
    Route::get('/user/{id}/{slug}', 'AdminController@index');
    Route::get('/user/{user}/{slug}/orders', 'AdminController@index');
    Route::get('/products', 'AdminController@index');
    Route::get('/product/{product}/{slug}', 'AdminController@index');
    Route::get('/product/{product}/service/{id}/{slug}', 'AdminController@index');
    Route::get('/categories', 'AdminController@index');
    Route::get('/locations', 'AdminController@index');
    Route::get('/transaction_charges', 'AdminController@index');
    Route::get('/set_charges', 'AdminController@index');
    Route::get('/special_orders', 'AdminController@index');
    Route::get('/special_order/{id}/{special}', 'AdminController@index');
    Route::get('/settings', 'AdminController@index');
    Route::get('/enquiries', 'AdminController@index');
    Route::get('/enquiry/{mail}/{slug}', 'AdminController@index');
    Route::get('/all_orders_today', 'AdminController@index');

    Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
});


// admin api routes
Route::get('/get_users_count', 'AdminController@getUsersCount');
Route::get('/get_orders_count', 'AdminController@getOrdersCount');
Route::get('/get_products_count', 'AdminController@getProductsCount');
Route::get('/get_services_count', 'AdminController@getServicesCount');
Route::get('/get_categories_count', 'AdminController@getCategoriesCount');
Route::get('/admin_get_all_ordersummary', 'AdminController@getOrderSummaries');
Route::get('/admin_get_order/{order}', 'AdminController@getOrder');
Route::get('/admin_get_summary/{id}', 'AdminController@getOrderSummary');
Route::post('/admin_delete_order', 'AdminController@delOrder');
Route::get('/admin_get_users_order_history/{id}', 'AdminController@getUserHistory');
Route::get('/admin_del_order_summary/{id}', 'AdminController@delOrderSummary');
Route::post('/admin_order_actions/{id}', 'AdminController@changeOrderStatus');
// Route::post('/admin_send_message', 'AdminController@sendMessage');
Route::get('/admin_get_user/{id}', 'AdminController@getUser');
Route::get('/admin_get_messages/{id}', 'AdminController@getMessages');
Route::post('/admin_post_messages', 'AdminController@postMessages');
Route::get('/admin_get_all_messages', 'AdminController@getAllMessages');
Route::get('/admin_get_users', 'AdminController@getAllUsers');
Route::get('/admin_change_messages_status/{id}', 'AdminController@changeMessagesStatus');
Route::get('/admin_getAllLocations', 'AdminController@getLocations');
Route::post('/admin_create_user', 'AdminController@createUser');
Route::post('/admin_send_welcome_mail_to_user', 'AdminController@sendWelcomeMail');
Route::get('/admin_get_users_orders_count/{id}', 'AdminController@getUsersOrdersCount');
Route::get('/admin_get_users_special_orders_count/{id}', 'AdminController@getUsersSpecialOrdersCount');
Route::get('/admin_get_users_orders/{id}', 'AdminController@getUsersOrders');
Route::post('/admin_update_user/{id}', 'AdminController@updateUser');
Route::post('/admin_change_users_status/{id}', 'AdminController@changeUserStatus');
Route::post('/admin_reset_users_password/{id}', 'AdminController@ResetUserPassword');
Route::post('/admin_delete_user/{id}', 'AdminController@deleteUser');
Route::get('/admin_get_products', 'AdminController@getAllProducts');
Route::post('/admin_create_product', 'AdminController@createProduct');
Route::get('/admin_get_categories', 'AdminController@getCats');
Route::post('/admin_search_products', 'AdminController@searchProducts');
Route::get('/admin_filter_products_by_cats/{id}', 'AdminController@filterProdsByCats');
Route::get('/admin_delete_product/{id}', 'AdminController@deleteProd');
Route::get('/admin_get_product/{id}', 'AdminController@getProduct');
Route::post('/admin_update_product_img/{id}', 'AdminController@updateImage');
Route::post('/admin_update_product/{id}', 'AdminController@updateProduct');
Route::get('/admin_get_prod_services/{id}', 'AdminController@getProductServices');
Route::get('/admin_get_service/{id}', 'AdminController@getService');
Route::post('/admin_create_service/{id}', 'AdminController@createService');
Route::post('/admin_delete_service/{id}', 'AdminController@delService');
Route::post('/admin_update_service/{id}', 'AdminController@updateService');
Route::post('/admin_create_cat', 'AdminController@createCateg');
Route::post('/admin_delete_cat/{id}', 'AdminController@delCateg');
Route::post('/admin_update_categ/{id}', 'AdminController@updateCateg');
Route::get('/admin_get_locations', 'AdminController@getAllLocs');
Route::post('/admin_create_location', 'AdminController@createLocation');
Route::post('/admin_delete_loc/{id}', 'AdminController@delLoc');
Route::post('/admin_update_location/{id}', 'AdminController@updateLoc');
Route::post('/admin_search_charges', 'AdminController@searchCharges');
Route::get('/admin_get_trx_charges', 'AdminController@getTrxCharges');
Route::get('/admin_get_order_dates', 'AdminController@getOrderDates');
Route::post('/admin_filter_charges_by_dates', 'AdminController@filterChargesByDates');
Route::get('/admin_get_order_fees', 'AdminController@getDefaultCharges');
Route::post('/admin_update_fee/{id}', 'AdminController@updateFee');
Route::get('/admin_get_special_orders', 'AdminController@getSpecialOrders');
Route::post('/admin_filter_special_orders_by_dates', 'AdminController@filterSpecialOrdersByDates');
Route::post('/admin_del_special_order/{id}', 'AdminController@delSpecialOrder');
Route::get('/admin_get_special_order/{id}', 'AdminController@getSpecialOrder');
Route::post('/admin_set_special_orders_cost/{id}', 'AdminController@setSpecialOrderCost');
Route::post('/admin_send_order_cost_email', 'AdminController@sendCostEmail');
Route::post('/admin_process_special_order/{id}', 'AdminController@processSpecialOrder');
Route::get('/admin_filter_orders_by_dates', 'AdminController@filterOrdersByDates');
Route::get('/admin_get_profile', 'AdminController@getProfile');
Route::post('/admin_confirm_current_pswd', 'AdminController@confirmCurrentPswd');
Route::post('/admin_update_admin_pswd', 'AdminController@updateAdminPswd');
Route::get('/admin_get_all_mails_enquiries', 'AdminController@getMailsEnquiries');
Route::get('/admin_get_enquiry/{mail}', 'AdminController@getMail');
Route::get('/admin_change_msg_status/{mail}', 'AdminController@readEnquiry');
Route::post('/admin_delete_contact_enquiry/{id}', 'AdminController@delEnquiry');
Route::get('/unread_enquiries_count', 'AdminController@getUnreadCount');
Route::get('/admin_get_unread_chats', 'AdminController@getUnreadChats');
Route::get('/admin_get_todays_orders_counts', 'AdminController@getTodaysOrdersCounts');
Route::get('/admin_get_todays_orders', 'AdminController@getTodaysOrders');
Route::get('/admin_get_special_orders_count', 'AdminController@getSpecialOrdersCount');
Route::get('/admin_get_sales', 'AdminController@getSales');
Route::get('/admin_get_sales_period', 'AdminController@getSalesPeriod');
Route::post('/admin_delete_conversations', 'AdminController@delConversations');

Route::get('/checkout_confirm', 'CheckoutController@checkoutConfirm');

// paystack apis
Route::post('/pay', 'PaymentController@redirectToGateway')->name('pay');
Route::get('/payment/callback', 'PaymentController@handleGatewayCallback');
