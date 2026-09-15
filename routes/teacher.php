<?php

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

require base_path('routes/general.php');

Route::group(['namespace' => 'Teacher'], function() {

    Route::get('/home', 'SettingController@home')->name('home');
    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Profile
    Route::get('profile/edit', 'SettingController@editProfile')->name('edit-profile');
    Route::post('profile/update', 'SettingController@updateProfile')->name('update-profile');
    Route::get('password/edit', 'SettingController@editPassword')->name('edit-password');
    Route::post('password/update', 'SettingController@updatePassword')->middleware('throttle:10,1')->name('update-password');


    //Curriculum
    Route::get('curriculum/{grade}', 'CurriculumController@curriculum')->name('curriculum.home');
    Route::get('levels/{grade}', 'CurriculumController@lessonsLevels')->name('levels');
    Route::get('stories/{grade}', 'CurriculumController@storiesLevels')->name('levels.stories');

    Route::get('stories_level/{level}', 'CurriculumController@stories')->name('stories.list');
    Route::get('stories/{id}/{key}/{grade}', 'CurriculumController@story')->name('stories.show');

    Route::get('lessons/{id}/{type}', 'CurriculumController@lessons')->name('lessons');
    Route::get('lessons_levels/{grade}/{type}', 'CurriculumController@subLevels')->name('lessons_levels');
    Route::get('lesson/{id}/{key}', 'CurriculumController@lesson')->name('lesson');
    Route::get('sub_lessons/{id}/{type}/{level}', 'CurriculumController@subLessons')->name('sub_lessons');

    //Reports
    Route::get('usage_report', 'SettingController@usageReport')->name('report.teacher_usage_report');
    Route::get('pre_usage_report', 'SettingController@preUsageReport')->name('report.teacher_pre_usage_report');

    Route::post('student_assign', [\App\Http\Controllers\Teacher\StudentController::class, 'studentAssign'])->name('student.student_assign');




});
