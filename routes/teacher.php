<?php

use App\Http\Controllers\GeneralController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Teacher'], function() {

    Route::get('/home', 'SettingController@home')->name('home');
    //Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    //Profile
    Route::get('profile/edit', 'SettingController@editProfile')->name('edit-profile');
    Route::post('profile/update', 'SettingController@updateProfile')->name('update-profile');
    Route::get('password/edit', 'SettingController@editPassword')->name('edit-password');
    Route::post('password/update', 'SettingController@updatePassword')->name('update-password');

    //Student
    Route::get('students_school', 'StudentController@index')->name('students_school.index');

    Route::get('my_students', 'StudentController@myStudents')->name('student.my_students');
    Route::get('my_students/{id}/edit', 'StudentController@edit')->name('student.edit');
    Route::patch('my_students/{id}', 'StudentController@update')->name('student.update');

    Route::get('students/cards', 'StudentController@cards')->name('student.student-cards-export');
    Route::post('students/export', 'StudentController@exportStudentsExcel')->name('user.export_students_excel');

    Route::post('student_assign', 'StudentController@studentAssign')->name('student.student_assign');
    Route::post('delete_student_assign', 'StudentController@deleteStudentAssign')->name('student.delete_student_assign');

    Route::post('updateLearningYears', 'StudentController@updateLearningYears')->name('student.updateLearningYears');

    Route::get('student/{id}/review', 'StudentController@review')->name('user.review');
    Route::get('student/{id}/story_review', 'StudentController@storyReview')->name('user.story-review');
    Route::get('student/{id}/report', 'StudentController@report')->name('user.report');

    //Students Tests
    Route::get('lessons_tests', 'StudentTestController@lessonsIndex')->name('lessons_tests.index');
    Route::get('lessons_tests/{id}', 'StudentTestController@lessonsShow')->name('lessons_tests.show');
    Route::delete('lessons_tests', 'StudentTestController@lessonsDestroy')->name('lessons_tests.destroy');
    Route::get('lessons_tests/{id}/certificate', 'StudentTestController@lessonsCertificate')->name('lessons_tests.certificate');
    Route::post('lessons_tests/export', 'StudentTestController@lessonsExportStudentsTestsExcel')->name('lessons_tests.export_excel');

    Route::get('stories_tests', 'StudentTestController@storiesIndex')->name('stories_tests.index');
    Route::get('stories_tests/{id}', 'StudentTestController@storiesShow')->name('stories_tests.show');
    Route::delete('stories_tests', 'StudentTestController@storiesDestroy')->name('stories_tests.destroy');
    Route::get('stories_tests/{id}/certificate', 'StudentTestController@storiesCertificate')->name('stories_tests.certificate');
    Route::post('stories_tests/export', 'StudentTestController@exportStoriesTestsExcel')->name('stories_tests.export_excel');

    Route::get('stories_records', 'StudentTestController@storiesRecordsIndex')->name('stories_records.index');
    Route::get('stories_records/{id}', 'StudentTestController@storiesRecordsShow')->name('stories_records.show');
    Route::patch('stories_records/{id}', 'StudentTestController@storiesRecordsUpdate')->name('stories_records.update');
    Route::delete('stories_records', 'StudentTestController@storiesRecordsDestroy')->name('stories_records.destroy');
    Route::post('stories_records_tests/export', 'StudentTestController@exportStoriesRecordsExcel')->name('stories_records.export_excel');


    //Students Works
    Route::get('students_works', 'StudentWorksController@index')->name('students_works.index');
    Route::get('students_works/{id}', 'StudentWorksController@show')->name('students_works.show');
    Route::post('students_works/{id}', 'StudentWorksController@update')->name('students_works.update');
    Route::delete('students_works', 'StudentWorksController@destroy')->name('students_works.destroy');
    Route::post('students_works_export', 'StudentWorksController@studentLessonExport')->name('students_works.export');

    //Lessons Assignments
    Route::resource('lesson_assignment', 'LessonAssignmentController')->except(['destroy']);
    Route::delete('lesson_assignment/delete', 'LessonAssignmentController@destroy')->name('lesson_assignment.destroy');
    Route::post('lesson_assignment/export', 'LessonAssignmentController@export')->name('lesson_assignment.export');

    //Stories Assignments
    Route::resource('story_assignment', 'StoryAssignmentController')->except(['destroy']);
    Route::delete('story_assignment/delete', 'StoryAssignmentController@destroy')->name('story_assignment.destroy');
    Route::post('story_assignment/export', 'StoryAssignmentController@export')->name('story_assignment.export');

    //MotivationalCertificate
    Route::resource('motivational_certificate', 'MotivationalCertificateController')->except(['destroy', 'edit', 'update']);
    Route::delete('motivational_certificate/delete', 'MotivationalCertificateController@destroy')->name('motivational_certificate.destroy');
    Route::post('motivational_certificate/export', 'MotivationalCertificateController@export')->name('motivational_certificate.export');


    //Curriculum
    Route::get('curriculum/{grade}', 'CurriculumController@curriculum')->name('curriculum.home');
    Route::get('levels/{grade}', 'CurriculumController@lessonsLevels')->name('levels');
    Route::get('stories/{grade}', 'CurriculumController@storiesLevels')->name('levels.stories');

    Route::get('stories_level/{level}', 'CurriculumController@stories')->name('stories.list');
    Route::get('stories/{id}/{key}/{grade}', 'CurriculumController@story')->name('stories.show');

    Route::get('lessons/{id}/{type}', 'CurriculumController@lessons')->name('lessons');
    Route::get('lesson/{id}/{key}', 'CurriculumController@lesson')->name('lesson');

    //General
    Route::get('getLessonsByGrade', [GeneralController::class,'getLessonsByGrade'])->name('getLessonsByGrade');
    Route::get('getStoriesByGrade', [GeneralController::class,'getStoriesByGrade'])->name('getStoriesByGrade');
    Route::get('getStudentsByGrade/{id}', [GeneralController::class,'getStudentsByGrade'])->name('getStudentsByGrade');

    //Reports
    Route::get('usage_report', 'SettingController@usageReport')->name('report.usage_report');
    Route::get('pre_usage_report', 'SettingController@preUsageReport')->name('report.pre_usage_report');




});
