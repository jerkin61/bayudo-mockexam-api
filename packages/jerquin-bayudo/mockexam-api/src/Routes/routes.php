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
use Jerquin\Http\Controllers\GroupController;
use Jerquin\Enums\Permission;

// Route::middleware('auth:api')->get('/check-access', function (Request $request) {
//     $user = Auth::user();
//     return $user->getPermissionNames();
//     return ['can:' . Permission::USER, 'auth:sanctum'];
//     if (Auth::check()) {
//     $permissions = $user->getAllPermissions()->pluck('name');
//         return response()->json(['permissions' => $permissions], 200);
//     } else {
//         // User is not authenticated
//         return response()->json(['error' => 'Unauthorized'], 401);
//     }
// });
Route::middleware(['auth:api'])->get('/check-accessed', function (Request $request) {
    $user = Auth::user();

    try {
      $permissions = $user->getPermissionNames();
    return response()->json(['permissions' => 'here' .$permissions], 200);
    } catch (\Throwable $th) {
        return $th;
    }
 
});
Route::middleware(['auth:api', 'permission:' . Permission::STAFF ])->get('/check-access', function (Request $request) {
    $user = Auth::user();

    try {
      $permissions = $user->getPermissionNames();
    return response()->json(['permissions' => 'here' .$permissions], 200);
    } catch (\Throwable $th) {
        return $th;
    }
 
});
Route::apiResource('group', GroupController::class, [
            'only' => [ 'index', 'show']
        ]);
Route::post('/change-password', 'Jerquin\Http\Controllers\UserController@changePassword');
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
    'only' => [ 'show']
]);
Route::apiResource('attachments', AttachmentController::class, [
    'only' => ['index', 'show','update','store', 'destroy']
]);
Route::apiResource('categories', CategoryController::class, [
    'only' => ['index', 'show']
]);
   
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
Route::get('examcategorytakenbyexamcategoryid/{id}', [ExamCategoryTakenController::class, 'showByExamCategoryId']);
Route::apiResource('attachment', AttachmentController::class, [
            'only' => ['index', 'show']
        ]);
Route::get('/me', 'Jerquin\Http\Controllers\UserController@me');
Route::post('/logout', 'Jerquin\Http\Controllers\UserController@logout');

Route::get('answerexams/{questionNo}/{examCategoryTaken}', [AnswerExamsController::class, 'showRelatedQuestion']);
Route::get('showByGroup/{groupId}', [GroupController::class, 'showByGroup']);
Route::get('show-group-by-examlist/{examId}', [ExamList::class, 'showGroupByExamlist']);
Route::group(
    ['middleware' => ['permission:' . Permission::USER . '|' . Permission::STAFF, 'auth:sanctum']],
     function () {

         Route::apiResource('answerexams', AnswerExamsController::class, [
            'only' => ['update','store', 'destroy']
        ]);
    Route::apiResource('examcategorytaken', ExamCategoryTakenController::class, [
            'only' => ['update','store', 'destroy']
        ]);
    Route::apiResource('examtaken', ExamTakenController::class, [
            'only' => ['update','store', 'destroy']
        ]);
});


Route::group(
     ['middleware' => ['permission:' . Permission::STAFF . '|' . Permission::SUPER_ADMIN, 'auth:sanctum']],
     function () {

        Route::apiResource('question-feedback', QuestionFeedbackController::class, [
        'only' => ['update','store', 'destroy']
        ]);

});


Route::group(
     ['middleware' => ['permission:' . Permission::SUPER_ADMIN . '|' . Permission::ADMIN, 'auth:sanctum']],
     function () {
        Route::apiResource('categories', CategoryController::class, [
            'only' => ['store', 'update', 'destroy']
        ]);
        Route::apiResource('user', UserController::class, [
            'only' => ['index','store','update']
        ]);
        Route::apiResource('examlist', ExamListController::class, [
            'only' => [ 'update']
        ]);
        Route::apiResource('question', QuestionController::class, [
            'only' => ['update','store', 'destroy']
        ]);
        Route::apiResource('examcategory', ExamCategoryController::class, [
            'only' => [ 'update','store', 'destroy']
        ]);
        Route::apiResource('group', GroupController::class, [
                    'only' => ['update']
                ]);

        Route::apiResource('attachment', AttachmentController::class, [
            'only' => [ 'update','store', 'destroy']
        ]);
     
        Route::put('question-feedback-approve/{questionId}', [QuestionFeedbackController::class, 'approveQuestionFeedback']);
});
Route::group(
     ['middleware' => ['permission:' . Permission::SUPER_ADMIN, 'auth:sanctum']],
     function () {
 
        Route::apiResource('examlist', ExamListController::class, [
            'only' => [ 'store', 'destroy']
        ]);
               Route::apiResource('user', UserController::class, [
            'only' => ['destroy']
        ]);
        Route::apiResource('group', GroupController::class, [
                    'only' => ['store', 'destroy']
                ]);
      
        Route::post('export-questions', 'Jerquin\Http\Controllers\QuestionController@exportQuestions');
        Route::post('import-questions', 'Jerquin\Http\Controllers\QuestionController@importQuestions');
        Route::post('users/ban-user', 'Jerquin\Http\Controllers\UserController@banUser');
        Route::post('users/active-user', 'Jerquin\Http\Controllers\UserController@activeUser');
});


   