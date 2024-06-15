<?php

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;
Route::group(['namespace' => 'School'], function() {

    Route::get('/home', 'SettingController@home')->name('home');
    Route::post('statistics/chart_statistics_data',  'SettingController@chartStatisticsData')->name('statistics.chart_statistics_data');

    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Profile
    Route::get('profile/edit', 'SettingController@editProfile')->name('edit-profile');
    Route::post('profile/update', 'SettingController@updateProfile')->name('update-profile');
    Route::get('password/edit', 'SettingController@editPassword')->name('edit-password');
    Route::post('password/update', 'SettingController@updatePassword')->name('update-password');


    //Supervisor
    Route::resource('supervisor', 'SupervisorController')->except(['destroy']);
    Route::delete('supervisor/delete', 'SupervisorController@destroy')->name('supervisor.destroy');
    Route::post('supervisor/export', 'SupervisorController@export')->name('supervisor.export');

    //Teacher
    Route::resource('teacher', 'TeacherController')->except(['destroy','show']);
    Route::delete('teacher/delete', 'TeacherController@destroy')->name('teacher.destroy');
    Route::post('teacher/export', 'TeacherController@export')->name('teacher.export');
    Route::get('teacher/{id}/login', 'TeacherController@login')->name('teacher.login');
    Route::get('teacher/statistics', 'TeacherController@teachersStatistics')->name('teacher.statistics');
    Route::post('teacher/statistics/export', 'TeacherController@teachersStatisticsExport')->name('teacher.statistics_export');
    Route::get('teacher/{id}/statistics/report', 'TeacherController@teachersStatisticsReport')->name('teacher.statistics_report');

    //Students
    Route::resource('student', 'StudentController')->except(['create','show', 'store', 'destroy']);
    Route::post('student/export', 'StudentController@exportStudentsExcel')->name('user.export_students_excel');
    Route::delete('student/delete', 'StudentController@destroy')->name('student.destroy');
    Route::get('student/{id}/review', 'StudentController@review')->name('user.review');
    Route::get('student/review-analytics', 'StudentController@reviewAnalytics')->name('student.review-analytics');
    Route::get('student/{id}/story_review', 'StudentController@storyReview')->name('user.story-review');
    Route::get('student/story-review-analytics', 'StudentController@storyReviewAnalytics')->name('student.story-review-analytics');
    Route::get('student/{id}/report', 'StudentController@report')->name('user.report');
    Route::get('student/cards', 'StudentController@cards')->name('user.cards-export');


    //Students Tests
    Route::get('lessons_tests', 'StudentTestController@lessonsIndex')->name('lessons_tests.index');
    Route::get('lessons_tests/{id}', 'StudentTestController@lessonsShow')->name('lessons_tests.show');
    Route::get('lessons_tests/certificate/{id}', 'StudentTestController@lessonsCertificate')->name('lessons_tests.certificate');
    Route::post('lessons_tests/export', 'StudentTestController@lessonsExportStudentsTestsExcel')->name('lessons_tests.export_excel');

    Route::get('stories_tests', 'StudentTestController@storiesIndex')->name('stories_tests.index');
    Route::get('stories_tests/{id}', 'StudentTestController@storiesShow')->name('stories_tests.show');
    Route::get('stories_tests/certificate/{id}', 'StudentTestController@storiesCertificate')->name('stories_tests.certificate');
    Route::post('stories_test/export', 'StudentTestController@exportStoriesTestsExcel')->name('stories_tests.export_excel');
    Route::get('stories_records', 'StudentTestController@storiesRecordsIndex')->name('stories_records.index');
    Route::post('stories_records/export', 'StudentTestController@exportStoriesRecordsExcel')->name('stories_records.export_excel');
    Route::get('students_works', 'StudentTestController@studentLessonIndex')->name('students_works.index');
    Route::post('students_works/export', 'StudentTestController@studentLessonExport')->name('students_works.export');

    //Lessons Assignments
    Route::get('lessons_assignments', 'AssignmentController@lessonsIndex')->name('lessons_assignments.index');
    Route::post('lessons_assignments/export', 'AssignmentController@lessonsExport')->name('lessons_assignments.export');
    //Stories Assignments
    Route::get('stories_assignments', 'AssignmentController@storiesIndex')->name('stories_assignments.index');
    Route::post('stories_assignments/export', 'AssignmentController@storiesExport')->name('stories_assignments.export');


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

    //General
    Route::get('getLessonsByGrade', [GeneralController::class,'getLessonsByGrade'])->name('getLessonsByGrade');
    Route::get('getStoriesByGrade', [GeneralController::class,'getStoriesByGrade'])->name('getStoriesByGrade');
    Route::get('getSectionByTeacher/{id}', [GeneralController::class,'getSectionByTeacher'])->name('getSectionByTeacher');

});
