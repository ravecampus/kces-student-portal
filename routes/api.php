<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SYearController;
use App\Http\Controllers\AdvisoryController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherAdvisoryController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ScheduleController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('admin-windev',[AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    
    Route::put('user/{id}', [AuthController::class,'adminprofile']);
    Route::post('user/password', [AuthController::class,'adminpassword']);
    Route::post('teacher/password', [TeacherController::class,'passwordChange']);
    Route::post('teacher/profile', [TeacherController::class,'profile']);
    Route::post('teacher/credential', [TeacherController::class,'credential']);
    Route::get('teacher/restore/{id}', [TeacherController::class,'restore']);
    Route::resource('teacher', TeacherController::class);

    Route::get('subject/individual/{id}', [SubjectController::class,'subject']);
    Route::get('subject/list', [SubjectController::class, 'list']);
    Route::resource('subject', SubjectController::class);
    Route::get('section/list', [SectionController::class, 'list']);
    Route::resource('section', SectionController::class);
    Route::get('syear/list', [SYearController::class, 'list']);
    Route::resource('syear', SYearController::class);
    Route::get('advisory/user/{id}', [AdvisoryController::class,'getAdvise']);
    Route::resource('advisory', AdvisoryController::class);
    Route::post('student/profile',[StudentController::class,'profile']);
    Route::post('student/credential',[StudentController::class,'credential']);
    Route::post('student/password', [StudentController::class,'passwordChange']);
    Route::resource('student', StudentController::class);
    Route::post('teacher-advisory/ind', [TeacherAdvisoryController::class, 'getStudent']);
    Route::resource('teacher-advisory', TeacherAdvisoryController::class);
    Route::get('grade/student/{id}', [GradeController::class,'studentGrade']);
    Route::resource('grade', GradeController::class);
    Route::get('schedule/ind/{id}', [ScheduleController::class,'getSchedule']);
    Route::resource('schedule', ScheduleController::class);

});
