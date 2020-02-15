<?php

use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', ['icon' => 'icoooon2'], function (Request $request) {
    return $request->user();
});

# User
Route::get('user/profile', 'api\UsersController@userProfile')->middleware('auth:api');

Route::get('site/settings', 'api\ApiController@siteSetting');
# contact us
Route::post('contactUs', 'api\ApiController@contactUs');
#upgrad order
Route::post('upgrade', 'api\ApiController@Upgrade');
#roles
Route::get('roles', 'api\ApiController@Roles');
#about application
Route::get('about', 'api\ApiController@aboutApp');
#upgrade page
Route::get('upgrade-page', 'api\ApiController@UpgradePage');
#cities list
Route::get('cities', 'api\ApiController@Cities');

/*Categories List*/
Route::get('categories-list', 'api\CategoriesController@categoriesList');
# places list for current category
Route::any('sub-categories-list', 'api\CategoriesController@subCategoriesList');
Route::any('categories/childs', 'api\CategoriesController@categoriesChild');
# categorProducts
Route::any('category-products', 'api\CategoriesController@categoryProducts');
# SHow Product
Route::post('show-product', 'api\ProductsController@show');
# SHow Product
Route::get('categories', 'api\CategoriesController@categories');

# basket
Route::get('basketList', 'api\ProductsController@basketList')->middleware('auth:api');
Route::post('basketAdd', 'api\ProductsController@basketAdd')->middleware('auth:api');
Route::post('basketRemove', 'api\ProductsController@basketRemove')->middleware('auth:api');
Route::post('quantity/update', 'api\ProductsController@updateQuantity')->middleware('auth:api');
Route::post('visitor/basket', 'api\ProductsController@fetchBasketList');

// product
Route::post('products/new', 'api\ProductsController@productNew')->middleware('auth:api');

// maintenances
Route::post('maintenances/new', 'api\MaintenancesController@maintenancesNew')->middleware('auth:api');

// shops
Route::post('shops/show', 'api\ShopsController@show')->middleware('auth:api');
Route::get('shops/new/products', 'api\ShopsController@newProducts')->middleware('auth:api');
Route::post('shops/new/finished', 'api\ShopsController@newFinished')->middleware('auth:api');
Route::get('shops/new/maintenance', 'api\ShopsController@newMaintenance')->middleware('auth:api');
Route::post('shops/maintenance/finished', 'api\ShopsController@maintenanceFinished')->middleware('auth:api');
Route::get('shops/orders/underway', 'api\ShopsController@underwayOrders')->middleware('auth:api');
# maintenances auction
Route::post('shops/auction/maintenance', 'api\ShopsController@maintenancesAuction')->middleware('auth:api');
Route::post('shops/auction/product', 'api\ShopsController@productsAuction')->middleware('auth:api');

// Users
Route::post('users/new/finished', 'api\UsersController@newFinished')->middleware('auth:api');
Route::post('users/maintenance/finished', 'api\UsersController@maintenanceFinished')->middleware('auth:api');
Route::post('users/ratings', 'api\UsersController@ratings')->middleware('auth:api');
Route::post('users/maint/ratings', 'api\UsersController@maintRatings')->middleware('auth:api');
Route::post('users/settings', 'api\UsersController@settings')->middleware('auth:api');
Route::get('users/orders', 'api\UsersController@myOrders')->middleware('auth:api');
Route::get('users/orders/maint', 'api\UsersController@myOrdersMaint')->middleware('auth:api');
// hires
Route::post('users/orders/hire', 'api\ProductsController@hire')->middleware('auth:api');

// Notifications
Route::get('notifications/all', 'api\NotificationsController@index')->middleware('auth:api');
Route::get('notifications/read', 'api\NotificationsController@read')->middleware('auth:api');
Route::post('notifications/remove', 'api\NotificationsController@remove')->middleware('auth:api');
Route::get('notifications/remove/all', 'api\NotificationsController@removeAll')->middleware('auth:api');

# Offers
Route::get('offers', 'api\ApiController@offers');
/*show place*/
Route::get('show-place/{id}', 'api\ApiController@ShowPlace');
/*Arrive Me*/
Route::get('arrive-me/{id}', 'api\ApiController@ArriveMe');
/*Register*/
Route::post('register', 'api\AuthController@Register');
/*register company*/
Route::post('register/company', 'api\AuthController@RegisterCompany');
/*Update User*/
Route::post('update/user', 'api\AuthController@UpdateUser')->middleware('auth:api');
/*Update Company*/
Route::post('update/company', 'api\AuthController@UpdateCompany');
/*login*/
Route::post('login', 'api\AuthController@Login');
/*Application*/
Route::get('application', 'api\ApiController@Application');
/*map*/
Route::get('map/{id}', 'api\ApiController@Map');
/*Favorite*/
Route::post('favorite', 'api\ApiController@Favorite');
/*UnFavorite*/
Route::post('unfavorite', 'api\ApiController@UnFavorite');
/*Favorite List*/
Route::get('favorite-list/{id}', 'api\ApiController@FavoriteList');
/*The Near*/
Route::get('the-near', 'api\ApiController@theNear');
#The Newest
Route::post('the-newest', 'api\ApiController@theNewest');
/*comment*/
Route::post('comment', 'api\ApiController@Comment');
#comments and rating for current place
Route::get('comments-list/{id}', 'api\ApiController@CommentList');
#prfile page
Route::get('account-page/{id}', 'api\ApiController@ProfilePage');
/*Update Account Page*/
Route::post('update-account/{id}', 'api\ApiController@UpdateAccount');
/*Upload Photo*/
Route::post('update-photo', 'api\ApiController@UpdatePhoto');
/*Call*/
Route::get('call/{id}', 'api\ApiController@Call');
/*Search*/
Route::get('search', 'api\ApiController@Search');
#Rating List For User
Route::get('rating-list/{id}', 'api\ApiController@RatingList');
/*Update Comment*/
Route::post('update-comment', 'api\ApiController@UpdateComment');
/*Delete Comment*/
Route::post('delete-comment', 'api\ApiController@DeleteComment');

/*Branches List*/
Route::get('Branches-list/{id}', 'api\ApiController@BranchList');

#add place
Route::any('add-place', 'api\ApiController@AddPlace');
#uploade images
Route::post('upload-images', 'api\ApiController@UploadeImages');

Route::any('edit_place', 'api\ApiController@edit_place');
Route::post('delete_place/{id}', 'api\ApiController@delete_place');
Route::any('my_places', 'api\ApiController@my_places');

/*Forget Password*/
Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken');
/*Reset Password*/
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
// password change
Route::post('password/change', 'api\AuthController@passwordChange')->middleware('auth:api');

Route::post('testDatabase', function () {
    factory(App\User::class, 50)->create();
    factory(App\Category::class, 50)->create();
    factory(App\SubCategory::class, 50)->create();
    factory(App\Product::class, 50)->create();
    factory(App\Field::class, 100)->create();
    factory(App\Photo::class, 100)->create();
    factory(App\Shop::class, 30)->create();
    factory(App\Rating::class, 50)->create();
    factory(App\ShopPro::class, 30)->create();
    factory(App\UserAna::class, 50)->create();
    factory(App\Maint::class, 50)->create();
    factory(App\MaintAucts::class, 50)->create();
    factory(App\MaintFinished::class, 50)->create();
    factory(App\NewPro::class, 50)->create();
    factory(App\ProAucts::class, 50)->create();
    factory(App\NewProFinished::class, 50)->create();
    factory(App\Basket::class, 50)->create();
    factory(App\Offer::class, 20)->create();
    return 'Done';
});

Route::get('password', function () {
    return bcrypt('123456');
});

Route::get('testFcm', function (Request $request) {
    $user = $request->user();
    #check if device android or ios
    if ($user->device_type === 'ios') {
        $notificationBuilder = new PayloadNotificationBuilder('shahna');
        $notificationBuilder->setBody(trans('messagesFCM.user_refused_order'))
            ->setSound('default');
        $option       = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'data_1'       => 'first_data',
            'click_action' => '.Activities.MainActivity',
        ]);

        $data               = $dataBuilder->build();
        $token              = $user->device_id;
        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();
        return "Done IOS";
    } else {
        $notificationBuilder = new PayloadNotificationBuilder('shahna');
        $notificationBuilder->setBody(trans('messagesFCM.user_refused_order'))
            ->setSound('default')
            ->setIcon('notification_icon');
        // $option       = $optionBuilder->build();
        $notification = $notificationBuilder->build();

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'title' => 'shahna',
            'body'  => trans('messagesFCM.user_refused_order'),
        ]);

        $data               = $dataBuilder->build();
        $token              = $user->device_id;
        $downstreamResponse = FCM::sendTo($token, null, $notification, $data);
        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();
        return response()->json($downstreamResponse->numberSuccess());
        return "Done Android -> " . $token;
    }

})->middleware('auth:api');
