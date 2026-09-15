<?php

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

require base_path('routes/general.php');

Route::group(['namespace' => 'School'], function() {

    Route::get('/home', 'SettingController@home')->name('home');
    Route::post('statistics/chart_statistics_data',  'SettingController@chartStatisticsData')->name('statistics.chart_statistics_data');

    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Profile
    Route::get('profile/edit', 'SettingController@editProfile')->name('edit-profile');
    Route::post('profile/update', 'SettingController@updateProfile')->name('update-profile');
    Route::get('password/edit', 'SettingController@editPassword')->name('edit-password');
    Route::post('password/update', 'SettingController@updatePassword')->middleware('throttle:10,1')->name('update-password');


    //Lesson And Story (Hidden Control)
    Route::get('lessons', 'HiddenController@indexLessons')->name('lessons.index');
    Route::post('hide_lessons', 'HiddenController@hideLessons')->name('lessons.hide');
    Route::post('show_lessons', 'HiddenController@showLessons')->name('lessons.show');
    Route::get('stories', 'HiddenController@indexStories')->name('stories.index');
    Route::post('hide_stories', 'HiddenController@hideStories')->name('stories.hide');
    Route::post('show_stories', 'HiddenController@showStories')->name('stories.show');

    //Usage Report
    Route::get('pre_usage_report', 'SettingController@preUsageReport')->name('report.pre_usage_report');
    Route::get('usage_report', 'SettingController@usageReport')->name('report.usage_report');

    //Teacher Report
    Route::get('pre_teacher_report', 'SettingController@preTeacherReport')->name('report.pre_teacher_report');
    Route::get('teacher_report', 'SettingController@teacherReport')->name('report.teacher_report');




});
