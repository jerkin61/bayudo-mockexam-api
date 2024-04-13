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
use Jerquin\Http\Controllers\ExamTakenController;
use Jerquin\Http\Controllers\ExamCategoryTakenController;
use Jerquin\Http\Controllers\AnswerExamsController;
use Jerquin\Http\Controllers\QuestionFeedbackController;
use Jerquin\Enums\Permission;

Route::post('/register', 'Jerquin\Http\Controllers\UserController@register');
Route::post('/register-staff', 'Jerquin\Http\Controllers\UserController@registerStaff');
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
Route::post('users/ban-user', 'Jerquin\Http\Controllers\UserController@banUser');
Route::post('users/active-user', 'Jerquin\Http\Controllers\UserController@activeUser');

Route::apiResource('profile', ProfileController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('user', UserController::class, [
    'only' => ['index', 'show']
]);

Route::apiResource('categories', CategoryController::class, [
    'only' => ['index', 'show']
]);
   
  

// Route::group(
//     ['middleware' => [ 'auth:sanctum', 'permission:' . Permission::STAFF . '|' . Permission::ADMIN]],
//      function () {
//     Route::get('/me', 'Jerquin\Http\Controllers\UserController@me');
//     Route::post('/logout', 'Jerquin\Http\Controllers\UserController@logout');
    
// });
Route::apiResource('settings', SettingsController::class, [
    'only' => ['index']
]);
Route::apiResource('examcategory', ExamCategoryController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('examlist', ExamListController::class, [
    'only' => ['index', 'show']
]);
Route::apiResource('question-feedback', QuestionFeedbackController::class, [
  'only' => ['index', 'show']
]);
Route::get('question-feedback-id/{questionId}', [QuestionFeedbackController::class, 'showPerQuestionFeedback']);


Route::apiResource('question', QuestionController::class, [
        'only' => ['index', 'show']
    ]);
Route::apiResource('examtaken', ExamTakenController::class, [
            'only' => ['index', 'show']
        ]);
Route::apiResource('answerexams', AnswerExamsController::class, [
            'only' => ['index', 'show']
        ]);
Route::apiResource('examcategorytaken', ExamCategoryTakenController::class, [
            'only' => ['index', 'show']
        ]);
Route::apiResource('attachment', AttachmentController::class, [
            'only' => ['index', 'show']
        ]);
        Route::get('/me', 'Jerquin\Http\Controllers\UserController@me');

Route::group(
    ['middleware' => ['can:' . Permission::USER, 'auth:sanctum']],
     function () {
        Route::post('/logout', 'Jerquin\Http\Controllers\UserController@logout');

        Route::apiResource('settings', SettingsController::class, [
            'only' => ['store']
        ]);

});

Route::get('answerexams/{questionNo}/{examCategoryTaken}', [AnswerExamsController::class, 'showRelatedQuestion']);
Route::group(
     ['middleware' => ['permission:' . Permission::STAFF, 'auth:sanctum']],
     function () {
        Route::post('/logout', 'Jerquin\Http\Controllers\UserController@logout');

        Route::apiResource('question-feedback', QuestionFeedbackController::class, [
        'only' => ['update','store']
        ]);

});


Route::group(
     ['middleware' => ['permission:' . Permission::SUPER_ADMIN, 'auth:sanctum']],
     function () {

        Route::post('/logout', 'Jerquin\Http\Controllers\UserController@logout');
        Route::apiResource('categories', CategoryController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('question-feedback', QuestionFeedbackController::class, [
        'only' => ['update','store', 'destroy']
        ]);
        Route::apiResource('examlist', ExamListController::class, [
            'only' => [ 'update','store', 'destroy']
        ]);
        Route::apiResource('question', QuestionController::class, [
            'only' => ['update','store', 'destroy']
        ]);
        Route::apiResource('examcategory', ExamCategoryController::class, [
            'only' => [ 'update','store', 'destroy']
        ]);
        Route::apiResource('examtaken', ExamTakenController::class, [
            'only' => ['update','store', 'destroy']
        ]);
        Route::apiResource('answerexams', AnswerExamsController::class, [
            'only' => ['update','store', 'destroy']
        ]);

        Route::apiResource('examcategorytaken', ExamCategoryTakenController::class, [
            'only' => ['update','store', 'destroy']
        ]);
        Route::apiResource('settings', SettingsController::class, [
            'only' => ['store']
        ]);
        Route::apiResource('attachment', AttachmentController::class, [
            'only' => [ 'update','store', 'destroy']
        ]);
        Route::post('export-questions', 'Jerquin\Http\Controllers\QuestionController@exportQuestions');
        Route::post('import-questions', 'Jerquin\Http\Controllers\QuestionController@importQuestions');

        Route::put('question-feedback-approve/{questionId}', [QuestionFeedbackController::class, 'approveQuestionFeedback']);
        Route::post('users/ban-user', 'Jerquin\Http\Controllers\UserController@banUser');
        Route::post('users/active-user', 'Jerquin\Http\Controllers\UserController@activeUser');
});


   