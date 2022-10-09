<?php
use Illuminate\Support\Facades\Route;
Route::group(['namespace' => 'Teacher'], function() {

    Route::get('/home', 'SettingController@home')->name('home');
//Set Local
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');

    Route::get('profile', 'SettingController@view_profile')->name('profile.show');
    Route::post('profile', 'SettingController@profile')->name('profile.update');
    Route::get('password', 'SettingController@view_password')->name('password.show');
    Route::post('password', 'SettingController@password')->name('password.update');

    Route::get('getStudentsByGrade/{id}', 'SettingController@getStudentsByGrade')->name('getStudentsByGrade');
    Route::get('getLevelsByGrade/{id}', 'SettingController@getLevelsByGrade')->name('getLevelsByGrade');
    Route::get('getStoriesByGrade/{id}', 'SettingController@getStoriesByGrade')->name('getStoriesByGrade');
    Route::get('getLessonsByGrade/{lid}', 'SettingController@getLessonsByGrade')->name('getLessonsByGrade');

//Teacher
    Route::resource('teacher', 'TeacherController');

//Students
    Route::resource('students_school', 'StudentController')->except(['create', 'store', 'destroy']);
    Route::get('my_students', 'StudentController@myStudents')->name('student.my_students');
    Route::post('student_assign', 'StudentController@studentAssign')->name('student.student_assign');
    Route::post('delete_student_assign', 'StudentController@deleteStudentAssign')->name('student.delete_student_assign');
    Route::post('export_students_excel', 'StudentController@exportStudentsExcel')->name('user.export_students_excel');
    Route::get('student/{id}/review', 'StudentController@review')->name('student.review');
    Route::get('student/{id}/report', 'StudentController@report')->name('user.report');
    Route::get('student/print/cards', 'StudentController@cards')->name('user.cards');
//Student Assignment
    Route::get('student_assignments', 'StudentAssignmentController@index')->name('student_assignments.index');
    Route::post('student_assignments', 'StudentAssignmentController@store')->name('student_assignments.store');
    Route::get('student_story_assignments', 'StudentAssignmentController@indexStory')->name('student_story_assignments.index');
    Route::post('student_story_assignments', 'StudentAssignmentController@storeStory')->name('student_story_assignments.store');

    Route::delete('student_assignment/{id}', 'StudentAssignmentController@deleteLessonAssignment')->name('deleteLessonAssignment');

//Student tests
    Route::get('students_tests', 'StudentTestController@index')->name('students_tests.index');
    Route::get('students_tests/{id}', 'StudentTestController@show')->name('students_tests.show');
    Route::get('students_tests/{id}/preview', 'StudentTestController@preview')->name('students_tests.preview');
    Route::post('students_tests/{id}', 'StudentTestController@correct')->name('students_tests.correct');
    Route::post('export_students_tests_excel', 'StudentTestController@exportStudentsTestsExcel')->name('students_tests.export_excel');

//Student Story
    Route::get('students_record', 'StoryController@studentsRecords')->name('students_record.index');
    Route::get('students_record/{id}', 'StoryController@showStudentsRecords')->name('students_record.show');
    Route::post('students_record/{id}', 'StoryController@updateStudentsRecords')->name('students_record.update');
    Route::delete('students_record/{id}', 'StoryController@deleteStudentsRecords')->name('students_record.delete');


    Route::get('curriculum/{grade}', 'CurriculumController@curriculum')->name('curriculum.home');
    Route::get('levels/{grade}', 'CurriculumController@lessonsLevels')->name('levels');
    Route::get('stories/{grade}', 'CurriculumController@storiesLevels')->name('levels.stories');


    Route::get('stories_level/{level}', 'CurriculumController@stories')->name('stories.list');
    Route::get('stories/{id}/{key}/{grade}', 'CurriculumController@story')->name('stories.show');


    Route::get('lessons/{id}/{type}', 'CurriculumController@lessons')->name('lessons');
    Route::get('lesson/{id}/{key}', 'CurriculumController@lesson')->name('lesson');



});
