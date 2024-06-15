<?php


use App\Http\Controllers\GeneralController;

Route::group(['namespace' => 'Supervisor'], function() {

    Route::get('home', 'SettingController@home')->name('home');

    Route::group(['middleware' => [\App\Http\Middleware\ApprovedSupervisorMiddleware::class]], function () {
        //Profile
        Route::get('profile/edit', 'SettingController@editProfile')->name('edit-profile');
        Route::post('profile/update', 'SettingController@updateProfile')->name('update-profile');
        Route::get('password/edit', 'SettingController@editPassword')->name('edit-password');
        Route::post('password/update', 'SettingController@updatePassword')->name('update-password');

        //Teacher
        Route::get('teacher', 'TeacherController@index')->name('teacher.index');
        Route::post('teacher/export', 'TeacherController@exportTeachersExcel')->name('teacher.export');
        Route::get('tracking_teachers', 'TeacherController@teachersTracking')->name('teacher.tracking');
        Route::post('tracking_teachers_export', 'TeacherController@teachersTrackingExport')->name('teacher.tracking_export');
        Route::get('tracking_teachers_report/{id}', 'TeacherController@teachersTrackingReport')->name('teacher.tracking_report');

        //Students
        Route::get('student', 'StudentController@index')->name('student.index');
        Route::get('student/cards', 'StudentController@studentsCards')->name('user.student-cards-export');
        Route::post('student/export', 'StudentController@exportStudentsExcel')->name('user.export_students_excel');
        Route::get('student/{id}/review', 'StudentController@review')->name('user.review');
        Route::get('student/{id}/story_review', 'StudentController@storyReview')->name('user.story-review');
        Route::get('student/{id}/report', 'StudentController@report')->name('user.report');

        //Lessons tests
        Route::get('lessons_tests', 'StudentTestController@lessonsIndex')->name('lessons_tests.index');
        Route::get('lessons_tests/certificate/{id}', 'StudentTestController@lessonsCertificate')->name('lessons_tests.certificate');
        Route::post('lessons_tests/export', 'StudentTestController@lessonsExportStudentsTestsExcel')->name('lessons_tests.export_excel');

        //Stories tests
        Route::get('stories_tests', 'StudentTestController@storiesIndex')->name('stories_tests.index');
        Route::get('stories_tests/certificate/{id}', 'StudentTestController@storiesCertificate')->name('stories_tests.certificate');
        Route::post('stories_tests/export', 'StudentTestController@exportStoriesTestsExcel')->name('stories_tests.export_excel');

        //Lessons Assignments
        Route::get('lessons_assignments', 'StudentAssignmentController@indexLessonAssignment')->name('lessons_assignments.index');
        Route::post('lessons_assignments/export', 'StudentAssignmentController@exportLessonAssignment')->name('lessons_assignments.export');

        //Stories Assignments
        Route::get('stories_assignments', 'StudentAssignmentController@indexStoryAssignment')->name('stories_assignments.index');
        Route::post('stories_assignments/export', 'StudentAssignmentController@exportStoryAssignment')->name('stories_assignments.export');

        //Marking
        //Stories Records
        Route::get('stories_records', 'StudentTestController@storiesRecordsIndex')->name('stories_records.index');
        Route::post('stories_records/excel', 'StudentTestController@exportStoriesRecordsExcel')->name('stories_records.export_excel');
        //Students Works
        Route::get('students_works', 'StudentWorksController@index')->name('students_works.index');
        Route::post('students_works/export', 'StudentWorksController@studentLessonExport')->name('students_works.export');

        //Usage Report
        Route::get('pre_usage_report', 'SettingController@preUsageReport')->name('report.pre_usage_report');
        Route::get('usage_report', 'SettingController@usageReport')->name('report.usage_report');


        //General
        Route::get('getLessonsByGrade', [GeneralController::class,'getLessonsByGrade'])->name('getLessonsByGrade');
        Route::get('getStoriesByGrade', [GeneralController::class,'getStoriesByGrade'])->name('getStoriesByGrade');
        Route::get('getTeacherBySchool/{id}', [GeneralController::class,'getTeacherBySchool'])->name('getTeacherBySchool');
        Route::get('getSectionBySchool/{id}', [GeneralController::class,'getSectionBySchool'])->name('getSectionBySchool');
        Route::get('getSectionByTeacher/{id}', [GeneralController::class,'getSectionByTeacher'])->name('getSectionByTeacher');
        Route::get('getStudentsByGrade/{id}', [GeneralController::class,'getStudentsByGrade'])->name('getStudentsByGrade');



    });

});
