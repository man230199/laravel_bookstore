<?php

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



$prefixStore = config('store.url.prefix_store');
Route::group(['prefix' => $prefixStore, 'namespace' => 'Store'], function () {

    // ============================== HOMEPAGE ==============================
    $prefix         = '';
    $controllerName = 'home';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::get('/',                             ['as' => $controllerName,                  'uses' => $controller . 'index']);
        Route::get('/not-found',                    ['as' => $controllerName . '/not-found',   'uses' => $controller . 'notfound']);
    });

    // ============================== CATEGORY ==============================
    $prefix         = 'danh-muc-san-pham';
    $controllerName = 'category';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::get('/{category_name}-{category_id}.html',  ['as' => $controllerName . '/index', 'uses' => $controller . 'index'])
            ->where('category_name', '[0-9a-zA-Z_-]+')
            ->where('category_id', '[0-9]+');
    });

    // ============================== PRODUCT ==============================
    $prefix         = 'san-pham';
    $controllerName = 'book';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::get('/{product_name}-{product_id}.html',  ['as' => $controllerName . '/index', 'uses' => $controller . 'index'])
            ->where('product_name', '[0-9a-zA-Z_-]+')
            ->where('product_id', '[0-9]+');
        Route::get('/{product_id}.html',  ['as' => $controllerName . '/ajaxQuickView', 'uses' => $controller . 'ajaxQuickView'])
            ->where('product_name', '[0-9a-zA-Z_-]+')
            ->where('product_id', '[0-9]+');

        Route::get('/danh-muc-san-pham.html',                             ['as' => $controllerName . '/list',                  'uses' => $controller . 'list']);
    });

    // ====================== ARTICLE ========================
    $prefix         = 'bai-viet';
    $controllerName = 'article';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';

        Route::get('/',                             ['as' => $controllerName,                  'uses' => $controller . 'index']);

        Route::get('/{article_name}-{article_id}.html',  ['as' => $controllerName . '/index', 'uses' => $controller . 'index'])
            ->where('article_name', '[0-9a-zA-Z_-]+')
            ->where('article_id', '[0-9]+');
    });

    // ====================== CATEGORY ARTICLE ========================
    $prefix         = 'danh-sach-bai-viet';
    $controllerName = 'categoryArticle';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';

        Route::get('/',                             ['as' => $controllerName,                  'uses' => $controller . 'index']);

        Route::get('/{category_article_name}-{category_article_id}.html',  ['as' => $controllerName . '/index', 'uses' => $controller . 'index'])
            ->where('category_article_name', '[0-9a-zA-Z_-]+')
            ->where('category_article_id', '[0-9]+');
    });

    // ====================== CONTACT ========================
    $prefix         = 'lien-he';
    $controllerName = 'contact';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::get('/thong-tin-lien-he.html',  ['as' => $controllerName . '/index', 'uses' => $controller . 'index']);
        Route::post('/sendContact',             ['as' => $controllerName . '/sendContact',  'uses' => $controller . 'sendContact']);
    });

    // ====================== ABOUT ========================
    $prefix         = 'gioi-thieu.html';
    $controllerName = 'about';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::get('/',  ['as' => $controllerName, 'uses' => $controller . 'index']);
    });


    // ====================== LOGIN ========================
    // news69/login
    $prefix         = '';
    $controllerName = 'auth';

    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::get('/login',        ['as' => $controllerName . '/login',      'uses' => $controller . 'login']); //->middleware('check.login');
        Route::post('/postLogin',   ['as' => $controllerName . '/postLogin',  'uses' => $controller . 'postLogin']);
        Route::get('/register',   ['as' => $controllerName . '/register',  'uses' => $controller . 'register']);
        Route::post('/postRegister',   ['as' => $controllerName . '/postRegister',  'uses' => $controller . 'postRegister']);

        // ====================== LOGOUT ========================
        Route::get('/logout',       ['as' => $controllerName . '/logout',     'uses' => $controller . 'logout']);
    });


    // ====================== CART ========================
    $prefix         = 'gio-hang.html';
    $controllerName = 'cart';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::get('/order/{product_id}-{price}-{quantity}',                             ['as' => "$controllerName/order",                  'uses' => $controller . 'order']);
        Route::get('/ajaxChangeQuantity/{product_id}-{price}-{quantity}',                             ['as' => "$controllerName/ajaxChangeQuantity",                  'uses' => $controller . 'ajaxChangeQuantity']);
        Route::get('/',                             ['as' => "$controllerName/show",                  'uses' => $controller . 'show']);
        Route::get('/coupon',                             ['as' => "$controllerName/coupon",                  'uses' => $controller . 'coupon']);
        Route::post('/buy',             ['as' => $controllerName . '/buy',  'uses' => $controller . 'buy']);
        Route::get('/removeItem/{product_id}',                             ['as' => "$controllerName/removeItem",                  'uses' => $controller . 'removeItem']);
        Route::get('/pull',                             ['as' => "$controllerName/pull",                  'uses' => $controller . 'pull']);
    });
    $prefix         = 'checkout.html';
    $controllerName = 'cart';
    Route::group(['prefix' =>  $prefix], function () use ($controllerName) {
        $controller = ucfirst($controllerName)  . 'Controller@';
        Route::post('/',                             ['as' => "$controllerName/checkout",                  'uses' => $controller . 'checkout']);
    });
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
