<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group([], function ($router) {

    $router->post('login', [
        AuthController::class,
        'login'
    ])->name('auth.login');

    $router->post('register', [
        AuthController::class,
        'register'
    ])->name('auth.register');

    $router->post('register-verify', [
        AuthController::class,
        'registerVerify'
    ])->name('auth.register.verify');

    $router->post('login-verify', [
        AuthController::class,
        'loginVerify'
    ])->name('auth.login.verify');

    $router->post('password-verify', [
        UserController::class,
        'passwordVerify'
    ])->name('user.password.verify');

    $router->post('send-otp', [
        AuthController::class,
        'sendOtp'
    ])->name('auth.send.otp');

    $router->post('resend-verification-code', [
        AuthController::class,
        'resendVerificationCode'
    ])->name('auth.register.resend.verification.code');

    $router->get('home-data', [
        UserController::class,
        'homeData'
    ])->name('user.home.data');
});

Route::group(['middleware' => ['auth:sanctum']], function ($router) {

    $router->post('change-email', [
        UserController::class,
        'changeEmail'
    ])->name('change.email');

    $router->post('change-email-submit', [
        UserController::class,
        'changeEmailSubmit'
    ])->name('change.email.submit');

    $router->match(['post', 'put'], 'change-password', [
        UserController::class,
        'changePassword'
    ])->name('change.password');

    $router->post('logout', [
        UserController::class,
        'logout'
    ])->name('auth.logout ');

    $router->post('reset-password', [
        UserController::class,
        'resetPassword'
    ])->name('auth.reset.password ');
});


Route::group(['prefix' => 'user'], function ($router) {
    Route::group(['middleware' => ['auth:sanctum']], function ($router) {

        $router->delete('/me', [
            UserController::class,
            'unregister'
        ])->name('user.unregister');

        $router->delete('/{user}', [
            UserController::class,
            'delete'
        ])->name('user.delete');

        $router->get('/me', [
            UserController::class,
            'me'
        ])->name('user.me');

        $router->get('/get/{user}', [
            UserController::class,
            'get'
        ])->name('user.get');

        $router->get('/list', [
            UserController::class,
            'list'
        ])->name('user.list');

        $router->put('/{user}', [
            UserController::class,
            'update'
        ])->name('user.update');

        // $router->put('/{user}/reset-password', [
        //     UserController::class,
        //     'resetPassword'
        // ])->name('user.reset-password');
    });
});

Route::group(['prefix' => 'category'], function ($router) {

    $router->get('/', [
        CategoryController::class,
        'list'
    ])->name('category.list');

    $router->get('/{grade?}/{subject?}/{topic?}', [
        CategoryController::class,
        'show'
    ])->name('category.show');

    Route::group(['middleware' => ['auth:sanctum']], function ($router) {

        $router->post('/', [
            CategoryController::class,
            'create'
        ])->name('category.create');

        $router->put('/{category}', [
            CategoryController::class,
            'update'
        ])->name('category.update');

        $router->delete('/{category}', [
            CategoryController::class,
            'delete'
        ])->name('category.delete');
    });
});

// Route::group(['prefix' => 'form'], function ($router) {

//     Route::group(['middleware' => ['auth:sanctum']], function ($router) {
//         $router->get('/{category}', [
//             CategoryFormController::class, 'show'
//         ])->name('form.show');

//         $router->post('/{category?}', [
//             CategoryFormController::class, 'create'
//         ])->name('form.create');
//     });
// });


Route::group(['prefix' => 'product'], function ($router) {

    $router->get('/list/{param1?}/{param2?}/{param3?}', [
        ProductController::class,
        'list'
    ])->name('product.list');

    $router->get('/show/{product}', [
        ProductController::class,
        'show'
    ])->name('product.show');


    Route::group(['middleware' => ['auth:sanctum']], function ($router) {

        $router->get('/admin/{category?}', [
            ProductController::class,
            'listAdmin'
        ])->name('product.admin.list');

        $router->get('/admin/show/{id_slug}', [
            ProductController::class,
            'showAdmin'
        ])->name('product.admin.show');

        $router->post('/', [
            ProductController::class,
            'create'
        ])->name('product.create');

        $router->post('/upload/banner', [
            ProductController::class,
            'uploadBanner'
        ])->name('product.upload.banner');

        $router->post('/upload/file', [
            ProductController::class,
            'uploadFile'
        ])->name('product.upload.file');

        $router->put('/{product}', [
            ProductController::class,
            'update'
        ])->name('product.update');

        // $router->put('/{product}/state', [
        //     ProductController::class,
        //     'changeState'
        // ])->name('product.change.state');

        $router->delete('/{product}', [
            ProductController::class,
            'delete'
        ])->name('product.delete');

        $router->match(['get', 'post'], '/{product}/like', [
            ProductController::class,
            'like'
        ])->name('product.like');

        $router->match(['get', 'post'], '/{product}/unlike', [
            ProductController::class,
            'unlike'
        ])->name('product.unlike');

        $router->get('/favourites/{param1?}/{param2?}/{param3?}', [
            ProductController::class,
            'favourites'
        ])->name('product.favourites');

        // $router->delete('/{product}/delete-favourite', [
        //     ProductController::class,
        //     'deleteFavourite'
        // ])->name('product.deleteFavourite');

        $router->get('/recents', [
            ProductController::class,
            'recents'
        ])->name('product.recents');

        // $router->delete('/{product}/delete-recent', [
        //     ProductController::class,
        //     'deleteRecent'
        // ])->name('product.deleteRecent');

        $router->get('/{product}/download-pdf', [
            ProductController::class,
            'downloadPdf'
        ])->name('product.downloadPdf');

        $router->get('/{product}/download-word', [
            ProductController::class,
            'downloadWord'
        ])->name('product.downloadWord');


        $router->get('/my', [
            ProductController::class,
            'my'
        ])->name('product.my');
    });
});


Route::group(['prefix' => 'cart'], function ($router) {
    Route::group(['middleware' => ['auth:sanctum']], function ($router) {
        $router->get('/', [
            CartController::class,
            'current'
        ])->name('cart.current');

        $router->post('/', [
            CartController::class,
            'create'
        ])->name('cartitem.create');

        $router->put('/{cartItem}', [
            CartController::class,
            'update'
        ])->name('cartitem.update');

        $router->delete('/{product}', [
            CartController::class,
            'delete'
        ])->name('cartitem.delete');
    });
});


Route::group(['prefix' => 'order'], function ($router) {
    Route::group(['middleware' => ['auth:sanctum']], function ($router) {
        $router->get('/', [
            OrderController::class,
            'list'
        ])->name('order.list');

        $router->get('/current', [
            OrderController::class,
            'current'
        ])->name('order.current');

        $router->get('/my', [
            OrderController::class,
            'my'
        ])->name('order.my');

        $router->get('/{order}/show', [
            OrderController::class,
            'show'
        ])->name('order.show');

        $router->post('/', [
            OrderController::class,
            'createOrUpdate'
        ])->name('order.createOrUpdate');

        // $router->put('/{order}', [
        //     OrderController::class,
        //     'update'
        // ])->name('order.update');

        $router->post('/{order}/payment', [
            OrderController::class,
            'payment'
        ])->name('order.payment');

        $router->get('/payment/callback', [
            OrderController::class,
            'callback'
        ])->name('order.callback');

        $router->delete('/{order}', [
            OrderController::class,
            'delete'
        ])->name('order.delete');
    });
});
