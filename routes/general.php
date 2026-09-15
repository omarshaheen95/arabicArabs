<?php
Route::group(['namespace' => 'General'], function () {

   //General
    Route::get('getLessonsByGrade', 'GeneralController@getLessonsByGrade')->name('getLessonsByGrade');
    Route::get('getStoriesByGrade', 'GeneralController@getStoriesByGrade')->name('getStoriesByGrade');
    Route::get('getTeacherBySchool/{id}', 'GeneralController@getTeacherBySchool')->name('getTeacherBySchool');
    Route::get('getSectionBySchool/{id}', 'GeneralController@getSectionBySchool')->name('getSectionBySchool');
    Route::get('getSectionByTeacher/{id}', 'GeneralController@getSectionByTeacher')->name('getSectionByTeacher');
    Route::get('getStudentsByGrade/{id}', 'GeneralController@getStudentsByGrade')->name('getStudentsByGrade');


    //Supervisors
    Route::resource('supervisor', 'SupervisorController')->except(['destroy','show']);
    Route::delete('supervisor/destroy', 'SupervisorController@destroy')->name('supervisor.destroy');
    Route::post('supervisor/export', 'SupervisorController@export')->name('supervisor.export');
    Route::post('supervisor/activation', 'SupervisorController@activation')->name('supervisor.activation');
    Route::get('supervisor/{id}/login', 'SupervisorController@login')->name('supervisor.login');
    Route::post('supervisor/reset-passwords', 'SupervisorController@resetPasswords')->name('supervisor.reset-passwords');
    Route::post('supervisor/force-password-change', 'SupervisorController@forcePasswordChange')->name('supervisor.force-password-change');


    //Roles And Permission
    Route::resource('role','RoleAndPermission\RoleController')->except(['destroy']);
    Route::delete('role', 'RoleAndPermission\RoleController@destroy')->name('role.destroy');
    Route::resource('permission','RoleAndPermission\PermissionController')->except(['destroy']);
    Route::delete('permission','RoleAndPermission\PermissionController@destroy')->name('permission.destroy');
//    Route::get('user_role_and_permission','RoleAndPermission\UserRoleAndPermissionController@index')->name('user_role_and_permission.index');
    Route::get('user_role_and_permission/{user_guard}/{id}/edit','RoleAndPermission\UserRoleAndPermissionController@edit')->name('user_role_and_permission.edit');
    Route::post('user_role_and_permission/{id}/update','RoleAndPermission\UserRoleAndPermissionController@update')->name('user_role_and_permission.update');


    //Teacher
    Route::resource('teacher', 'TeacherController')->except(['destroy','show']);
    Route::delete('teacher/destroy', 'TeacherController@destroy')->name('teacher.destroy');
//    Route::get('teacher/update_statistics', 'SettingController@updateTeacherStatistics')->name('teacher.updateTeacherStatistics');
    Route::post('teacher/activation', 'TeacherController@activation')->name('teacher.activation');
    Route::post('teacher/delete_students', 'TeacherController@deleteStudents')->name('teacher.delete_students');
    Route::post('teacher/export', 'TeacherController@exportTeachersExcel')->name('teacher.export_teachers_excel');
    Route::get('teacher/{id}/login', 'TeacherController@login')->name('teacher.login');
    Route::get('tracking_teachers', 'TeacherController@teachersTracking')->name('teacher.tracking');
    Route::post('tracking_teachers_export', 'TeacherController@teachersTrackingExport')->name('teacher.tracking_export');
    Route::get('tracking_teachers_report/{id}', 'TeacherController@teachersTrackingReport')->name('teacher.tracking_report');
    Route::post('teacher/reset-passwords', 'TeacherController@resetPasswords')->name('teacher.reset-passwords');
    Route::post('teacher/force-password-change', 'TeacherController@forcePasswordChange')->name('teacher.force-password-change');


    //User
    Route::resource('user', 'UserController')->except(['destroy','show']);
//    Route::get('duplicate_user', 'UserController@duplicateIndex')->name('user.duplicate_user');
//    Route::delete('duplicate_user/{id}', 'UserController@destroyDuplicate')->name('user.delete_duplicate_user');
    Route::post('user/export', 'UserController@export')->name('user.export');
    Route::post('user/reset-passwords', 'UserController@resetPasswords')->name('user.reset-passwords');
    Route::get('user/{id}/card', 'UserController@userCard')->name('user.card');
    Route::get('user/cards_and_qr', 'UserController@cards')->name('user.cards-export');
    Route::get('user/cards_and_qr_file', 'UserController@studentsImportFileCards')->name('user.studentsImportFileCards');
    Route::post('user/cards_by_section', 'UserController@studentsCardsBySection')->name('user.cards-by-section');
    Route::get('user/lesson_review/{id}', 'UserController@lessonReview')->name('user.review');
    Route::get('user/story_review/{id}', 'UserController@storyReview')->name('user.story_review');
    Route::get('user/report/{id}', 'UserController@report')->name('user.report');
    Route::get('user/{id}/login', 'UserController@login')->name('user.login');
    Route::delete('user/destroy', 'UserController@destroy')->name('user.destroy');
    Route::post('user/activation', 'UserController@userActivation')->name('user.activation');
    Route::post('user/update_grades', 'UserController@updateGrades')->name('user.update_grades');
    Route::post('user/update_learning_years', 'UserController@updateLearningYears')->name('user.update_learning_years');
    Route::post('assigned_to_teacher', 'UserController@assignedToTeacher')->name('user.assigned_to_teacher');
    Route::post('unassigned_user_teacher', 'UserController@unassignedUserTeacher')->name('user.unassigned_user_teacher');
    Route::post('user/restore', 'UserController@restoreUser')->name('user.restore');
    Route::get('my_students', 'UserController@myStudents')->name('my-students');
    Route::post('/pdfReports', 'UserController@pdfReports')->name('reports.pdfReports');
//
//
    //Lessons Test
    Route::resource('lessons_tests', 'LessonTestController')->except(['destroy','show']);
    Route::delete('lessons_tests/destroy', 'LessonTestController@destroy')->name('lessons_tests.destroy');
    Route::get('lessons_tests/preview_answers/{id}', 'LessonTestController@preview')->name('lessons_tests.preview_answers');
    //For [writing,speaking]
    Route::get('lessons_tests/correcting_feedback/{id}', 'LessonTestController@correctingAndFeedbackView')->name('lessons_tests.correcting_feedback_view');
    Route::post('lessons_tests/correcting_feedback/{id}', 'LessonTestController@correctingAndFeedback')->name('lessons_tests.correcting_feedback');
    Route::get('lessons_tests/correcting/{id}', 'LessonTestController@correctingUserTestView')->name('lessons_tests.correcting_view');
    Route::post('lessons_tests/correcting/{id}', 'LessonTestController@correctingUserTest')->name('lessons_tests.correcting');
    Route::post('lessons_tests/auto_correcting', 'LessonTestController@autoCorrectingUsersTests')->name('lessons_tests.auto_correcting');
    Route::get('lessons_tests/certificate/{id}', 'LessonTestController@certificate')->name('lessons_tests.certificate');
    Route::post('lessons_tests/export', 'LessonTestController@export')->name('lessons_tests.export');

    //Story Test
    Route::resource('stories_tests', 'StoryTestController')->except(['destroy','show']);
    Route::get('stories_tests/correcting/{id}', 'StoryTestController@correctingView')->name('stories_tests.correcting_view');
    Route::post('stories_tests/correcting/{id}', 'StoryTestController@correcting')->name('stories_tests.correcting');
    Route::post('stories_tests/auto_correcting', 'StoryTestController@autoCorrectingTests')->name('stories_tests.auto_correcting');
    Route::delete('stories_tests/destroy', 'StoryTestController@destroy')->name('stories_tests.destroy');
    Route::get('stories_tests/{id}/certificate', 'StoryTestController@certificate')->name('stories_tests.certificate');
    Route::post('stories_tests/export', 'StoryTestController@export')->name('stories_tests.export');

    //Story Record
    Route::resource('stories_records', 'StoryRecordController')->except(['destroy']);
    Route::delete('stories_records/destroy', 'StoryRecordController@destroy')->name('stories_records.destroy');
    Route::post('stories_records/export', 'StoryRecordController@export')->name('stories_records.export');

    //Lesson Assignment
    Route::resource('lesson_assignment','LessonAssignmentController')->except(['destroy']);
    Route::delete('lesson_assignment/destroy', 'LessonAssignmentController@destroy')->name('lesson_assignment.destroy');
    Route::post('lesson_assignment/export', 'LessonAssignmentController@export')->name('lesson_assignment.export');

    //User Lessons Assignments
    Route::resource('user_lesson_assignment', 'UserLessonAssignmentController')->only(['index']);
    Route::delete('user_lesson_assignment/destroy', 'UserLessonAssignmentController@destroy')->name('user_lesson_assignment.destroy');
    Route::post('user_lesson_assignment/export', 'UserLessonAssignmentController@export')->name('user_lesson_assignment.export');

    //Story Assignment
    Route::resource('story_assignment','StoryAssignmentController')->except(['destroy']);
    Route::delete('story_assignment/destroy', 'StoryAssignmentController@destroy')->name('story_assignment.destroy');
    Route::post('story_assignment/export', 'StoryAssignmentController@export')->name('story_assignment.export');

    //User Stories Assignments
    Route::resource('user_story_assignment', 'UserStoryAssignmentController')->only(['index']);
    Route::delete('user_story_assignment/destroy', 'UserStoryAssignmentController@destroy')->name('user_story_assignment.destroy');
    Route::post('user_story_assignment/export', 'UserStoryAssignmentController@export')->name('user_story_assignment.export');
//
    //Motivational Certificate
    Route::resource('motivational_certificates', 'MotivationalCertificateController')->except(['destroy', 'edit', 'update']);
    Route::delete('motivational_certificates/destroy', 'MotivationalCertificateController@destroy')->name('motivational_certificates.destroy');
    Route::post('motivational_certificates/export', 'MotivationalCertificateController@export')->name('motivational_certificates.export');

    //Roles And Permission
    Route::resource('role','RoleAndPermission\RoleController')->except(['destroy']);
    Route::delete('role', 'RoleAndPermission\RoleController@destroy')->name('role.destroy');
    Route::resource('permission','RoleAndPermission\PermissionController')->except(['destroy']);
    Route::delete('permission','RoleAndPermission\PermissionController@destroy')->name('permission.destroy');
//    Route::get('user_role_and_permission','RoleAndPermission\UserRoleAndPermissionController@index')->name('user_role_and_permission.index');
    Route::get('user_role_and_permission/{user_guard}/{id}/edit','RoleAndPermission\UserRoleAndPermissionController@edit')->name('user_role_and_permission.edit');
    Route::post('user_role_and_permission/{id}/update','RoleAndPermission\UserRoleAndPermissionController@update')->name('user_role_and_permission.update');


    //Reports
    //Usage Report
//    Route::get('pre_usage_report', 'ReportController@preUsageReport')->name('report.pre_usage_report');
//    Route::get('usage_report', 'ReportController@usageReport')->name('report.usage_report');
//    Route::post('usage_report_excel', 'ReportController@exportUsageReport')->name('report.usage_report_excel');

});


