<?php


use App\Http\Controllers\GeneralController;

Route::group(['middleware' => [\App\Http\Middleware\ApprovedSupervisorMiddleware::class]], function () {
    require base_path('routes/general.php');
});

Route::group(['namespace' => 'Supervisor'], function () {

    Route::get('home', 'SettingController@home')->name('home');

    Route::group(['middleware' => [\App\Http\Middleware\ApprovedSupervisorMiddleware::class]], function () {

        //Profile
        Route::get('profile/edit', 'SettingController@editProfile')->name('edit-profile');
        Route::post('profile/update', 'SettingController@updateProfile')->name('update-profile');
        Route::get('password/edit', 'SettingController@editPassword')->name('edit-password');
        Route::post('password/update', 'SettingController@updatePassword')->middleware('throttle:10,1')->name('update-password');

        //Usage Report
        Route::get('pre_usage_report', 'SettingController@preUsageReport')->name('report.pre_usage_report');
        Route::get('usage_report', 'SettingController@usageReport')->name('report.usage_report');

        //Teacher Usage Report
        Route::get('teacher_pre_usage_report', 'SettingController@teacherPreUsageReport')->name('report.teacher_pre_usage_report');
        Route::get('teacher_usage_report', 'SettingController@teacherUsageReport')->name('report.teacher_usage_report');


    });

});
