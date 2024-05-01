<?php



Route::group(['namespace' => 'Supervisor'], function() {

    Route::get('home', 'SettingController@home')->name('home');

    Route::group(['middleware' => [\App\Http\Middleware\ApprovedSupervisorMiddleware::class]], function () {
        Route::get('profile', 'SettingController@view_profile')->name('profile.show');
        Route::post('profile', 'SettingController@profile')->name('profile.update');
        Route::get('password', 'SettingController@view_password')->name('password.show');
        Route::post('password', 'SettingController@password')->name('password.update');

        Route::get('getStoriesByGrade/{id}', 'SettingController@getStoriesByGrade')->name('getStoriesByGrade');
        Route::get('getLessonsByGrade/{lid}', 'SettingController@getLessonsByGrade')->name('getLessonsByGrade');


        Route::get('teacher', 'TeacherController@index')->name('teacher.index');
        Route::get('teacher_export', 'TeacherController@teacherExport')->name('teacher.export');
        Route::get('teacher_statistics', 'TeacherController@teachersStatistics')->name('teacher.statistics');
        Route::get('teacher_statistics_export', 'TeacherController@teachersStatisticsExport')->name('teacher.statistics_export');
        Route::get('teacher_statistics_report/{id}', 'TeacherController@teachersStatisticsReport')->name('teacher.statistics_report');

        Route::get('student', 'StudentController@index')->name('student.index');
        Route::post('export_students_excel', 'StudentController@exportStudentsExcel')->name('user.export_students_excel');

        Route::get('student-lesson-test', 'StudentController@studentLessonTest')->name('student.studentLessonTest');
        Route::get('student-lesson-test/{id}', 'StudentController@studentLessonTestShow')->name('student.studentLessonTestShow');
        Route::post('student-lesson-test-export', 'StudentController@studentLessonTestExport')->name('student.studentLessonTestExport');
        Route::get('student-story-test', 'StudentController@studentStoryTest')->name('student.studentStoryTest');
        Route::get('student-story-test/{id}', 'StudentController@studentStoryTestShow')->name('student.studentStoryTestShow');
        Route::post('student-story-test-export', 'StudentController@studentStoryTestExport')->name('student.studentStoryTestExport');

        Route::get('student-lesson-assignment', 'StudentController@studentLessonAssignment')->name('student.studentLessonAssignment');
        Route::get('student-story-assignment', 'StudentController@studentStoryAssignment')->name('student.studentStoryAssignment');
    });

});
