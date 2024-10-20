<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorksheetController;
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

    $router->get('/{category}', [
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


Route::group(['prefix' => 'worksheet'], function ($router) {

    $router->get('/list/{param1?}/{param2?}/{param3?}', [
        WorksheetController::class,
        'list'
    ])->name('worksheet.list');

    $router->get('/show/{worksheet}', [
        WorksheetController::class,
        'show'
    ])->name('worksheet.show');

    // $router->match(['get', 'post'], '/{worksheet}/like', [
    //     WorksheetController::class,
    //     'like'
    // ])->name('worksheet.like');

    // $router->match(['get', 'post'], '/{worksheet}/unlike', [
    //     WorksheetController::class,
    //     'unlike'
    // ])->name('worksheet.unlike');

    Route::group(['middleware' => ['auth:sanctum']], function ($router) {

        $router->get('/admin/{category?}', [
            WorksheetController::class,
            'listAdmin'
        ])->name('worksheet.admin.list');

        $router->get('/admin/show/{id_slug}', [
            WorksheetController::class,
            'showAdmin'
        ])->name('worksheet.admin.show');


        $router->post('/', [
            WorksheetController::class,
            'create'
        ])->name('worksheet.create');

        $router->post('/upload/banner', [
            WorksheetController::class,
            'uploadBanner'
        ])->name('worksheet.upload.banner');

        $router->post('/upload/file', [
            WorksheetController::class,
            'uploadFile'
        ])->name('worksheet.upload.file');


        $router->put('/{worksheet}', [
            WorksheetController::class,
            'update'
        ])->name('worksheet.update');

        // $router->put('/{worksheet}/state', [
        //     WorksheetController::class,
        //     'changeState'
        // ])->name('worksheet.change.state');

        $router->delete('/{worksheet}', [
            WorksheetController::class,
            'delete'
        ])->name('worksheet.delete');

        // $router->get('/favourites', [
        //     WorksheetController::class,
        //     'favourites'
        // ])->name('worksheet.favourites');

        // $router->delete('/{worksheet}/delete-favourite', [
        //     WorksheetController::class,
        //     'deleteFavourite'
        // ])->name('worksheet.deleteFavourite');

        // $router->get('/recents', [
        //     WorksheetController::class,
        //     'recents'
        // ])->name('worksheet.recents');

        // $router->delete('/{worksheet}/delete-recent', [
        //     WorksheetController::class,
        //     'deleteRecent'
        // ])->name('worksheet.deleteRecent');

        $router->get('/my', [
            WorksheetController::class,
            'my'
        ])->name('worksheet.my');
    });
});
