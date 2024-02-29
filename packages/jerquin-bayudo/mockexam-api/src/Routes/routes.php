<?php

use Illuminate\Support\Facades\Route;
use Jerquin\Http\Controllers\ProfileController;
use Jerquin\Http\Controllers\UserController;
use Jerquin\Http\Controllers\CategoryController;
use Jerquin\Http\Controllers\InvoiceController;
use Jerquin\Http\Controllers\ServiceController;
use Jerquin\Http\Controllers\AttachmentController;
use Jerquin\Http\Controllers\SettingsController;
use Jerquin\Http\Controllers\ExamListController;
use Jerquin\Http\Controllers\ExamCategoryController;
use Jerquin\Http\Controllers\QuestionController;
use Jerquin\Enums\Permission;


Route::post('/register', 'Jerquin\Http\Controllers\UserController@register');
Route::post('/social-login-token', 'Jerquin\Http\Controllers\UserController@socialLogin');
Route::post('/token', 'Jerquin\Http\Controllers\UserController@token');
Route::post('/forget-password', 'Jerquin\Http\Controllers\UserController@forgetPassword');
Route::post('/reset-password', 'Jerquin\Http\Controllers\UserController@resetPassword');
Route::post('/verify-forget-password-token', 'Jerquin\Http\Controllers\UserController@verifyForgetPasswordToken');
Route::get('fetch-parent-category', 'Jerquin\Http\Controllers\CategoryController@fetchOnlyParent');
Route::get('fetch-children-category', 'Jerquin\Http\Controllers\CategoryController@fetchOnlyChildren');
Route::apiResource('profile', ProfileController::class, [
    'only' => ['index', 'show']
]);


Route::apiResource('profile', ProfileController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('user', UserController::class, [
    'only' => ['index', 'show']
]);

Route::apiResource('categories', CategoryController::class, [
    'only' => ['index', 'show']
]);
    Route::get('/me', 'Jerquin\Http\Controllers\UserController@me');
    Route::post('/logout', 'Jerquin\Http\Controllers\UserController@logout');

// Route::group(
//     ['middleware' => [ 'auth:sanctum', 'permission:' . Permission::STAFF . '|' . Permission::ADMIN]],
//      function () {
//     Route::get('/me', 'Jerquin\Http\Controllers\UserController@me');
//     Route::post('/logout', 'Jerquin\Http\Controllers\UserController@logout');
    
// });

Route::group(
    ['middleware' => [ 'auth:sanctum', 'permission:' . Permission::SUPER_ADMIN . '|' . Permission::ADMIN]],
     function () {

    
});



    Route::apiResource('categories', CategoryController::class, [
        'only' => ['store', 'update', 'destroy']
    ]);


    Route::apiResource('invoice', InvoiceController::class, [
        'only' => ['index', 'show', 'update','store', 'destroy']
    ]);
    Route::apiResource('examlist', ExamListController::class, [
        'only' => ['index', 'show', 'update','store', 'destroy']
    ]);
    Route::apiResource('question', QuestionController::class, [
        'only' => ['index', 'show', 'update','store', 'destroy']
    ]);
    Route::apiResource('examcategory', ExamCategoryController::class, [
        'only' => ['index', 'show', 'update','store', 'destroy']
    ]);
    Route::apiResource('settings', SettingsController::class, [
        'only' => ['index', 'show', 'update','store', 'destroy']
    ]);
    Route::apiResource('attachment', AttachmentController::class, [
        'only' => ['index', 'show', 'update','store', 'destroy']
    ]);