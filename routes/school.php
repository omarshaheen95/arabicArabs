<?php
use Illuminate\Support\Facades\Route;
Route::group(['namespace' => 'School'], function() {

    Route::get('/home', 'SettingController@home')->name('home');
    Route::get('profile', 'SettingController@view_profile')->name('profile.show');
    Route::post('profile', 'SettingController@profile')->name('profile.update');
    Route::get('password', 'SettingController@view_password')->name('password.show');
    Route::post('password', 'SettingController@password')->name('password.update');

    Route::get('getLessonsByLevel/{lid}', 'SettingController@getLessonsByLevel')->name('getLessonsByLevel');
    Route::get('getLevelsByGrade/{id}', 'SettingController@getLevelsByGrade')->name('getLevelsByGrade');

    Route::get('lesson', 'LessonController@index')->name('lesson.index');
    Route::post('lesson', 'LessonController@store')->name('lesson.store');
    Route::delete('lesson/{id}', 'LessonController@destroy')->name('lesson.destroy');

//Teacher
    Route::resource('teacher', 'TeacherController');
    Route::get('teacher_export', 'TeacherController@teacherExport')->name('teacher.export');
    Route::get('teacher_statistics', 'TeacherController@teachersStatistics')->name('teacher.statistics');
    Route::get('teacher_statistics_export', 'TeacherController@teachersStatisticsExport')->name('teacher.statistics_export');
    Route::get('teacher_statistics_report/{id}', 'TeacherController@teachersStatisticsReport')->name('teacher.statistics_report');

//Students
    Route::resource('student', 'StudentController')->except(['create', 'store', 'destroy']);
    Route::post('export_students_excel', 'StudentController@exportStudentsExcel')->name('user.export_students_excel');
    Route::delete('student/{id}', 'StudentController@destroy')->name('student.destroy');
    Route::get('student/{id}/review', 'StudentController@review')->name('student.review');
    Route::get('student/{id}/report', 'StudentController@report')->name('user.report');
    Route::get('student/print/cards', 'StudentController@cards')->name('user.cards');
    Route::get('student/print/cards_qr', 'StudentController@cardsQR')->name('user.cardsQR');

//Students Works
    Route::get('students_works', 'StudentWorksController@index')->name('students_works.index');
    Route::get('students_works/{id}', 'StudentWorksController@show')->name('students_works.show');
    Route::post('students_works/{id}', 'StudentWorksController@update')->name('students_works.update');
    Route::delete('students_works/{id}', 'StudentWorksController@destroy')->name('students_works.destroy');


//Notification Route
    Route::get('notification', 'NotificationController@index')->name('notification.index');
    Route::get('notification/{id}', 'NotificationController@show')->name('notification.show');
    Route::get('notifications/read_all', 'NotificationController@readAll')->name('notification.read_all');
    Route::delete('notification/{id}', 'NotificationController@destroy')->name('notification.destroy');

    Route::resource('supervisor', 'SupervisorController');

});
