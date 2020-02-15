<?php

// $faker->addProvider(new Faker\Provider\en_US\Person($faker));
// $faker->addProvider(new Faker\Provider\en_US\Address($faker));
// $faker->addProvider(new Faker\Provider\en_US\PhoneNumber($faker));
// $faker->addProvider(new Faker\Provider\en_US\DateTime($faker));

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    $faker->addProvider(new Faker\Provider\ar_SA\Address($faker));
    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'phone'          => $faker->phoneNumber,
        'city'           => $faker->city,
        'address'        => $faker->address,
        'lat'            => $faker->latitude,
        'lng'            => $faker->longitude,
        'avatar'         => $faker->image(public_path('uploads/avatars/'), $width = 200, $height = 200, 'people', false),
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

// Category
$factory->define(App\Category::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Text($faker));
    $text = $faker->realText(10, 3);
    return [
        'name_ar' => $text,
        'name_en' => $faker->sentence(1, true),
        'logo'    => $faker->image(public_path('uploads/logos/'), $width = 150, $height = 150, 'cats', false),
        'status'  => 1,
        'user_id' => $faker->randomElement([5,6,3,1]),
    ];
});

// SubCategory
$factory->define(App\SubCategory::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Text($faker));
    $text = $faker->realText(10, 3);
    return [
        'user_id'     => $faker->randomElement([5,6,3,1]),
        'category_id' => $faker->randomElement([5,6,3,1,4,8,10,9]),
        'name_ar'     => $text,
        'name_en'     => $faker->sentence(1, true),
    ];
});

// products
$factory->define(App\Product::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Text($faker));
    return [
        'user_id'        => $faker->randomElement([5,6,3,1]),
        'subCategory_id' => $faker->randomElement([5,6,3,1,4,8,10,9]),
        'name_ar'        => $faker->realText(10, 1),
        'name_en'        => $faker->sentence(1, true),
    ];
});

// fields
$factory->define(App\Field::class, function (Faker\Generator $faker) { 
    $faker->addProvider(new Faker\Provider\ar_SA\Text($faker));
    $textAR = $faker->realText(10, 1);
    $textEN = $faker->sentence(1, true);
    $number = $faker->numberBetween($min = 1000, $max = 9000);

    return [
        'product_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50]),
        'name_ar'    => $faker->realText(10, 1),
        'name_en'    => $faker->sentence(1, true),
        'vaule_ar'   => $faker->randomElement([$textAR, $number]),
        'vaule_en'   => $faker->randomElement([$textEN, $number]),
    ];
});

// Pohots
$factory->define(App\Photo::class, function (Faker\Generator $faker) { 
    return [
        'product_id' => $faker->randomElement([1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50]),
        'path'       => $faker->image(public_path('uploads/products/'), $width = 400, $height = 480, 'abstract', false)
    ];
});

// Shops
$factory->define(App\Shop::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Company($faker));
    return [
        'user_id'                 => $faker->randomElement([16,17,18,19,20]),
        'name'                    => $faker->company,
        'desc'                    => $faker->paragraph(2, true),
        'commercial_register'     => $faker->numberBetween($min = 1000, $max = 9000),
        'commercial_register_img' => $faker->image(public_path('uploads/commercial/'), $width = 640, $height = 480, 'cats', false),
        'logo'                    => $faker->image(public_path('uploads/logos/'), $width = 640, $height = 480, 'cats', false),
    ];
});

// Ratings
$factory->define(App\Rating::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Text($faker));
    $textAR = $faker->realText(200, 2);
    $textEN = $faker->paragraph(3, true);
    return [
        'user_id'    => $faker->randomElement([1,5,6,11,12]),
        'shop_id'    => $faker->randomElement([16,17,18,19,20]) ,
        'product_id' => $faker->randomElement([1,2,4,6,10]),
        'rate'       => $faker->numberBetween($min = 1, $max = 5),
        'comment'    => $faker->randomElement([$textAR, $textEN]),
    ];
});

// Shops Products
$factory->define(App\ShopPro::class, function (Faker\Generator $faker) {
    return [
        'shop_id'    => $faker->randomElement([16,17,18,19,20]),
        'product_id' => $faker->randomElement([5,6,3,1,4,8,10,9]),
        'subCategory_id' => $faker->numberBetween($min = 1, $max = 50),
    ];
});

// users analysis
$factory->define(App\UserAna::class, function (Faker\Generator $faker) {
    return [
        'user_id'    => $faker->randomElement([16,17,18,19,20]),
        'last_seen' => $faker->dateTime($max = 'now', $timezone = null),
        'replying_speed' => $faker->numberBetween($min = 1, $max = 60),
        'complated_orders' => $faker->numberBetween($min = 1, $max = 100),
    ];
});

// maintenances
$factory->define(App\Maint::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Address($faker));
    return [
        'user_id'      => $faker->randomElement([21,22,23,24,25,31,33,35,37,39]),
        'subCategory_id' => $faker->numberBetween($min = 1, $max = 50),
        'type'         => $faker->numberBetween($min = 0, $max = 6),
        'image'        => $faker->image(public_path('uploads/maintenances/'), $width = 640, $height = 480, 'abstract', false),
        'address'      => $faker->address,
        'deliveryDay'  => $faker->date($format = 'Y-m-d', $max = 'now'),
        'deliveryTime' => $faker->time($format = 'H:i:s', $max = 'now'),
    ];
});

// maintenances auctions
$factory->define(App\MaintAucts::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Text($faker));
    $textAR = $faker->realText(200, 2);
    $textEN = $faker->paragraph(3, true);
    return [
        'shop_id'   => $faker->randomElement([16,17,18,19,20]),
        'maint_id'  => $faker->randomElement([1,5,9,10,2,3,4,6]),
        'note'         => $faker->randomElement([$textAR, $textEN]),
        'payment_method'         => $faker->numberBetween($min = 0, $max = 1),
        'deliveryDay'  => $faker->date($format = 'Y-m-d', $max = 'now'),
        'deliveryTime' => $faker->time($format = 'H:i:s', $max = 'now'),
    ];
});

// maintenance finished
$factory->define(App\MaintFinished::class, function (Faker\Generator $faker) {
    return [
        'user_id'      => $faker->randomElement([21,22,23,24,25,31,33,35,37,39]),
        'shop_id'   => $faker->randomElement([16,17,18,19,20]),
        'maint_id'  => $faker->randomElement([1,5,9,10,2,3,4,6]),
    ];
});


// new products
$factory->define(App\NewPro::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Address($faker));
    return [
        'user_id'      => $faker->randomElement([21,22,23,24,25,31,33,35,37,39]),
        'product_id' => $faker->randomElement([5,6,3,1,4,8,10,9]),
        'payment_method'         => $faker->numberBetween($min = 0, $max = 1),
        'airConditioningPipes'         => $faker->numberBetween($min = 0, $max = 1),
        'address'      => $faker->address,
        'deliveryDay'  => $faker->date($format = 'Y-m-d', $max = 'now'),
        'deliveryTime' => $faker->time($format = 'H:i:s', $max = 'now'),
    ];
});

// new products auctions
$factory->define(App\ProAucts::class, function (Faker\Generator $faker) {
    $faker->addProvider(new Faker\Provider\ar_SA\Text($faker));
    $textAR = $faker->realText(200, 2);
    $textEN = $faker->paragraph(3, true);
    return [
        'shop_id'        => $faker->randomElement([16,17,18,19,20]),
        'new_id'         => $faker->randomElement([1,5,9,10,2,3,4,6]),
        'note'           => $faker->randomElement([$textAR, $textEN]),
        'payment_method' => $faker->numberBetween($min = 0, $max = 1),
        'deliveryDay'    => $faker->date($format = 'Y-m-d', $max = 'now'),
        'deliveryTime'   => $faker->time($format = 'H:i:s', $max = 'now'),
        'price'          => $faker->numberBetween($min = 100, $max = 1000000),
    ];
});

// new products finished
$factory->define(App\NewProFinished::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->randomElement([21,22,23,24,25,31,33,35,37,39]),
        'shop_id' => $faker->randomElement([16,17,18,19,20]),
        'new_id'  => $faker->randomElement([1,5,9,10,2,3,4,6]),
    ];
});

// Basket
$factory->define(App\Basket::class, function (Faker\Generator $faker) {
    return [
        'user_id'    => $faker->randomElement([21,22,23,24,25,31,33,35,37,39]),
        'product_id' => $faker->randomElement([5,6,3,1,4,8,10,9]),
        'number'     => $faker->numberBetween($min = 1, $max = 100),
    ];
});


// offerss
$factory->define(App\Offer::class, function (Faker\Generator $faker) {
    return [
        'image_ar' => $faker->image(public_path('uploads/offers/'), $width = 640, $height = 480, 'abstract', false),
        'image_en' => $faker->image(public_path('uploads/offers/'), $width = 640, $height = 480, 'abstract', false),
        'link'     => $faker->url,
    ];
});

// site setting
// $factory->define(App\SiteSetting::class, function (Faker\Generator $faker) {
//     return [
//         'site_name' => $faker->image(public_path('uploads/offers/'), $width = 640, $height = 480, 'abstract', false),
//         'site_logo' => $faker->image(public_path('uploads/offers/'), $width = 640, $height = 480, 'abstract', false),
//         'site_description'  => $faker->url,
//         'site_tagged'  => $faker->url,
//         'site_copyrigth'  => $faker->url,
//     ];
// });

