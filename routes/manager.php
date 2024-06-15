<?php

use App\Http\Controllers\GeneralController;
use App\Models\Lesson;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\LessonController;
use App\Http\Controllers\Manager\TrainingController;
use App\Http\Controllers\Manager\AssessmentController;
use App\Http\Controllers\Manager\SettingController;
use Spatie\Permission\Models\Permission;


Route::group(['namespace' => 'Manager'], function(){

    Route::get('/home', 'SettingController@home')->name('home');
    Route::post('statistics/chart_statistics_data',  'SettingController@chartStatisticsData')->name('statistics.chart_statistics_data');

    //School
    Route::resource('school', 'SchoolController')->except(['destroy']);
    Route::delete('delete_school', 'SchoolController@destroy')->name('school.destroy');
    Route::post('school/activation', 'SchoolController@activation')->name('school.activation');
    Route::post('school/export', 'SchoolController@export')->name('school.export');
    Route::get('school/{id}/login', 'SchoolController@login')->name('school.login');


    //Settings Management
    Route::get('settings', 'SettingController@settings')->name('settings.general');
    Route::post('settings', 'SettingController@updateSettings')->name('settings.updateSettings');
    Route::get('lang/{local}', 'SettingController@lang')->name('switch-language');


    //Manager Management
    Route::resource('manager', 'ManagerController')->except(['destroy']);
    Route::delete('manager/delete', 'ManagerController@destroy')->name('manager.destroy');
    Route::get('edit-permissions/{id}', 'ManagerController@editPermissions')->name('edit-permissions');
    Route::post('update-permissions', 'ManagerController@updatePermissions')->name('update-permissions');
    Route::post('/manager/export', 'ManagerController@export')->name('manager.export');

    //Profile
    Route::get('profile/edit', 'ManagerController@editProfile')->name('edit-profile');
    Route::post('profile/update', 'ManagerController@updateProfile')->name('update-profile');
    Route::get('password/edit', 'ManagerController@editPassword')->name('edit-password');
    Route::post('password/update', 'ManagerController@updatePassword')->name('update-password');

    //Supervisors
    Route::resource('supervisor', 'SupervisorController')->except(['destroy']);
    Route::delete('supervisor/delete', 'SupervisorController@destroy')->name('supervisor.destroy');
    Route::post('supervisor/activation', 'SupervisorController@activation')->name('supervisor.activation');
    Route::get('supervisor/{id}/login', 'SupervisorController@login')->name('supervisor.login');
    Route::post('supervisor/export', 'SupervisorController@export')->name('supervisor.export');

    //User
    Route::resource('user', 'UserController')->except(['destroy']);
    Route::get('user/{id}/login', 'UserController@login')->name('user.login');
    Route::delete('user/delete', 'UserController@destroy')->name('user.destroy');
    Route::post('user/{id}/restore', 'UserController@restore')->name('user.restore');
    Route::post('user/export', 'UserController@export')->name('user.export');
    Route::get('duplicate_user', 'UserController@duplicateIndex')->name('user.duplicate_user');
    Route::delete('duplicate_user', 'UserController@destroyDuplicate')->name('user.delete_duplicate_user');
    Route::get('user_cards_and_qr', 'UserController@cards')->name('user.cards-export');
    Route::get('user/{id}/review', 'UserController@review')->name('user.review');
    Route::get('user/{id}/story-review', 'UserController@storyReview')->name('user.story-review');
    Route::get('user/{id}/report', 'UserController@report')->name('user.report');
    Route::post('user/unassigned_teacher', 'UserController@unassignedUserTeacher')->name('user.unassigned-teacher');
    Route::post('user/assigned_teacher', 'UserController@assignedUserToTeacher')->name('user.assigned-teacher');
    Route::post('user/activation', 'UserController@activation')->name('user.activation');
    Route::post('user/update_grades', 'UserController@updateGrades')->name('user.update_grades');

    //Teacher
    Route::resource('teacher', 'TeacherController')->except('destroy');
    Route::delete('teacher/delete', 'TeacherController@destroy')->name('teacher.destroy');
    Route::post('teacher/activation', 'TeacherController@activation')->name('teacher.activation');
    Route::post('teacher/export', 'TeacherController@export')->name('teacher.export');
    Route::get('teacher/{id}/login', 'TeacherController@login')->name('teacher.login');
    Route::get('teacher/users-unassign', 'TeacherController@usersUnsigned')->name('teacher.user-unsigned');

    Route::get('tracking_teachers', 'TeacherController@teachersTracking')->name('teacher.tracking');
    Route::post('tracking_teachers_export', 'TeacherController@teachersTrackingExport')->name('teacher.tracking_export');
    Route::get('tracking_teachers_report/{id}', 'TeacherController@teachersTrackingReport')->name('teacher.tracking_report');


    //Activity Log Controller
    Route::get('activity-log', 'ActivityLogController@index')->name('activity-log.index');
    Route::get('activity-log/{id}', 'ActivityLogController@show')->name('activity-log.show');
    Route::delete('activity-log', 'ActivityLogController@destroy')->name('activity-log.delete');

    //Import Controller
    Route::resource('import_files', 'ImportFileController')->except(['destroy']);
    Route::post('import_files_export_data', 'ImportFileController@exportDataAsExcel')->name('import_files.export_excel');
    Route::get('import_files/{id}/user_cards', 'ImportFileController@usersCards')->name('import_files.users_cards');
    Route::delete('delete_import_files', 'ImportFileController@destroy')->name('import_files.delete');

    //Lesson
    Route::resource('lesson','LessonController')->except('destroy');
    Route::delete('lesson/delete', 'LessonController@destroy')->name('lesson.destroy');
    Route::post('lesson/export', 'LessonController@export')->name('lesson.export');
    Route::get('lesson/{id}/learn', [LessonController::class,'lessonLearn'])->name('lesson.learn');
    Route::get('lesson/{id}/review/{step}', [LessonController::class,'lessonReview'])->name('lesson.review');
    Route::post('lesson/{id}/learn', [LessonController::class,'updateLessonLearn'])->name('lesson.update_learn');
    Route::post('lesson/{id}/remove_lesson_audio', [LessonController::class,'deleteLessonAudio'])->name('lesson.remove_lesson_audio');
    Route::post('lesson/{video_id}/remove_video_attachment', [LessonController::class,'deleteLessonVideo'])->name('lesson.remove_video_attachment');
//
    Route::get('lesson/{id}/training', [TrainingController::class,'index'])->name('lesson.training.index');
    Route::get('lesson/{id}/training/edit/{question_id?}', [TrainingController::class,'edit'])->name('lesson.training.edit');
    Route::post('lesson/{id}/training/{type}', [TrainingController::class,'update'])->name('lesson.training.update');
    Route::delete('lesson/training/delete_question', [TrainingController::class,'destroy'])->name('lesson.training.delete-question');
    Route::post('lesson/{id}/remove_t_question_attachment', [TrainingController::class,'deleteTQuestionAttachment'])->name('lesson.remove_t_question_attachment');
    Route::post('lesson/{id}/remove_t_match_image', [TrainingController::class,'deleteTMatchImage'])->name('lesson.remove_t_match_image');
    Route::post('lesson/{id}/remove_t_sort_word', [TrainingController::class,'removeTSortWord'])->name('lesson.remove_t_sort_word');
//
    Route::get('lesson/{id}/assessment', [AssessmentController::class,'index'])->name('lesson.assessment.index');
    Route::get('lesson/{id}/assessment/edit/{question_id?}', [AssessmentController::class,'edit'])->name('lesson.assessment.edit');
    Route::post('lesson/{id}/assessment/{type}/{question_id?}', [AssessmentController::class,'update'])->name('lesson.assessment.update');
    Route::delete('lesson/assessment/delete_question', [AssessmentController::class,'destroy'])->name('lesson.assessment.delete-question');
    Route::post('lesson/{id}/remove_a_question_attachment', [AssessmentController::class,'deleteAQuestionAttachment'])->name('lesson.remove_a_question_attachment');
    Route::post('lesson/{id}/remove_a_match_image', [AssessmentController::class,'deleteAMatchImage'])->name('lesson.remove_a_match_image');
    Route::post('lesson/{id}/remove_a_sort_word', [AssessmentController::class,'removeASortWord'])->name('lesson.remove_a_sort_word');
    Route::get('wrong_audio_lessons', [LessonController::class,'getLessonsMedia'])->name('lesson.wrong_audio_lessons');

    //Hidden lesson
    Route::resource('hidden_lesson', 'HiddenLessonController')->except(['destroy', 'show', 'edit', 'update']);
    Route::delete('hidden_lesson/delete', 'HiddenLessonController@destroy')->name('hidden_lesson.destroy');
    Route::post('hidden_lesson/export', 'HiddenLessonController@export')->name('hidden_lesson.export');
    //Lessons Assignments
    Route::resource('lesson_assignment', 'LessonAssignmentController')->except(['destroy']);
    Route::delete('lessons_assignments/delete', 'LessonAssignmentController@destroy')->name('lesson_assignment.destroy');
    Route::post('lessons_assignments/export', 'LessonAssignmentController@export')->name('lesson_assignment.export');



    //Story
    Route::resource('story', 'StoryController')->except('destroy');
    Route::delete('story/delete', 'StoryController@destroy')->name('story.destroy');
    Route::get('story/{id}/review', 'StoryController@review')->name('story.review');
    Route::post('story/{id}/remove_attachment/{type}', 'StoryController@removeStoryAttachment')->name('story.remove-attachment');
    Route::get('story/{id}/assessment', 'StoryController@storyAssessment')->name('story.assessment');
    Route::post('story/{id}/assessment/{step}', 'StoryController@storeAssessmentStory')->name('story.assessment.store');
    Route::post('story/{id}/assessment/update/{step}', 'StoryController@updateAssessmentStory')->name('story.assessment.update');
    Route::post('story_remove_attachment/{id}', 'StoryController@removeAttachment')->name('story.remove_attachment');
    Route::post('story_remove_sort_word/{id}', 'StoryController@removeSortWord')->name('story.remove_sort_word');
    Route::post('story_remove_match_attachment/{id}', 'StoryController@removeMatchAttachment')->name('story.remove_match_attachment');
    //Hidden story
    Route::resource('hidden_story', 'HiddenStoryController')->except(['destroy', 'show', 'edit', 'update']);
    Route::delete('delete_hidden_story', 'HiddenStoryController@destroy')->name('hidden_story.destroy');
    Route::post('export_hidden_story', 'HiddenStoryController@export')->name('hidden_story.export');
    //Stories Assignments
    Route::resource('story_assignment', 'StoryAssignmentController')->except(['destroy']);
    Route::delete('story_assignment/delete', 'StoryAssignmentController@destroy')->name('story_assignment.destroy');
    Route::post('story_assignment/export', 'StoryAssignmentController@export')->name('story_assignment.export');

    //Students Works
    Route::resource('students_works', 'UserWorksController')->except(['destroy']);
    Route::delete('students_works/delete', 'UserWorksController@destroy')->name('students_works.destroy');
    Route::post('students_works/export', 'UserWorksController@export')->name('students_works.export');

    //Students Tests
    Route::get('lessons_tests', 'StudentTestController@lessonsIndex')->name('lessons_tests.index');
    Route::get('lessons_tests/{id}', 'StudentTestController@lessonsShow')->name('lessons_tests.show');
    Route::delete('lessons_tests', 'StudentTestController@lessonsDestroy')->name('lessons_tests.destroy');
    Route::get('lessons_tests/certificate/{id}', 'StudentTestController@lessonsCertificate')->name('lessons_tests.certificate');
    Route::post('export_lessons_tests_excel', 'StudentTestController@lessonsExportStudentsTestsExcel')->name('lessons_tests.export_excel');

    Route::get('stories_tests', 'StudentTestController@storiesIndex')->name('stories_tests.index');
    Route::get('stories_tests/{id}', 'StudentTestController@storiesShow')->name('stories_tests.show');
    Route::delete('stories_tests', 'StudentTestController@storiesDestroy')->name('stories_tests.destroy');
    Route::get('stories_tests/certificate/{id}', 'StudentTestController@storiesCertificate')->name('stories_tests.certificate');
    Route::post('export_stories_tests_excel', 'StudentTestController@exportStoriesTestsExcel')->name('stories_tests.export_excel');

    Route::get('stories_records', 'StudentTestController@storiesRecordsIndex')->name('stories_records.index');
    Route::get('stories_records/{id}', 'StudentTestController@storiesRecordsShow')->name('stories_records.show');
    Route::patch('stories_records/{id}', 'StudentTestController@storiesRecordsUpdate')->name('stories_records.update');
    Route::delete('stories_records', 'StudentTestController@storiesRecordsDestroy')->name('stories_records.destroy');
    Route::post('stories_records_tests_excel', 'StudentTestController@exportStoriesRecordsExcel')->name('stories_records.export_excel');

    //Year
    Route::resource('year','YearController');
    Route::delete('year', 'YearController@destroy')->name('year.destroy');

    //Login Sessions
    Route::resource('login_sessions', 'LoginSessionController');

    //Text translation
    Route::get('text_translation', 'TextTranslationController@index')->name('text_translation.index');
    Route::post('update_translation/{lang}/{file}', 'TextTranslationController@updateTranslations')->name('text_translation.update');

    //package
    Route::resource('package', 'PackageController')->except(['destroy']);
    Route::delete('package/delete', 'PackageController@destroy')->name('package.destroy');

    //General
    Route::get('getLessonsByGrade', [GeneralController::class,'getLessonsByGrade'])->name('getLessonsByGrade');
    Route::get('getStoriesByGrade', [GeneralController::class,'getStoriesByGrade'])->name('getStoriesByGrade');
    Route::get('getTeacherBySchool/{id}', [GeneralController::class,'getTeacherBySchool'])->name('getTeacherBySchool');
    Route::get('getSectionBySchool/{id}', [GeneralController::class,'getSectionBySchool'])->name('getSectionBySchool');
    Route::get('getSectionByTeacher/{id}', [GeneralController::class,'getSectionByTeacher'])->name('getSectionByTeacher');
    Route::get('getStudentsByGrade/{id}', [GeneralController::class,'getStudentsByGrade'])->name('getStudentsByGrade');

    Route::get('seed',function (){
        Artisan::call('db:seed --class PermissionsTableSeeder');
        //Artisan::call('db:seed --class SettingsTableSeeder');

        $all_manager_permission = Permission::query()->where('guard_name','manager')->get()->pluck('name')->toArray();
        Auth::guard('manager')->user()->syncPermissions($all_manager_permission);
        return redirect()->route('manager.home');

    });




    //old






//    Route::get('getTeacherBySchool/{lid}', 'TeacherController@getTeacherBySchool')->name('getTeacherBySchool');
//    Route::get('school-login/{id}', 'SchoolController@schoolLogin')->name('school.login');

    //OLD LESSON ROUTES
//    Route::get('lesson/{id}/learn', [LessonController::class,'lessonLearn'])->name('lesson.learn');
//    Route::get('lesson/{id}/review/{step}', [LessonController::class,'lessonReview'])->name('lesson.review');
//    Route::post('lesson/{id}/learn', [LessonController::class,'updateLessonLearn'])->name('lesson.update_learn');
//    Route::post('lesson/{id}/remove_lesson_audio', [LessonController::class,'deleteLessonAudio'])->name('lesson.remove_lesson_audio');
//    Route::post('lesson/{video_id}/remove_video_attachment', [LessonController::class,'deleteLessonVideo'])->name('lesson.remove_video_attachment');
//
//    Route::get('lesson/{id}/training', [TrainingController::class,'lessonTraining'])->name('lesson.training');
//    Route::post('lesson/{id}/training/{type}', [TrainingController::class,'updateLessonTraining'])->name('lesson.update_training');
//    Route::post('lesson/{id}/remove_t_question_attachment', [TrainingController::class,'deleteTQuestionAttachment'])->name('lesson.remove_t_question_attachment');
//    Route::post('lesson/{id}/remove_t_match_image', [TrainingController::class,'deleteTMatchImage'])->name('lesson.remove_t_match_image');
//    Route::post('lesson/{id}/remove_t_sort_word', [TrainingController::class,'removeTSortWord'])->name('lesson.remove_t_sort_word');
//
//    Route::get('lesson/{id}/assessment', [AssessmentController::class,'lessonAssessment'])->name('lesson.assessment');
//    Route::post('lesson/{id}/assessment/{type}', [AssessmentController::class,'updateLessonAssessment'])->name('lesson.update_assessment');
//    Route::post('lesson/{id}/remove_a_question_attachment', [AssessmentController::class,'deleteAQuestionAttachment'])->name('lesson.remove_a_question_attachment');
//    Route::post('lesson/{id}/remove_a_match_image', [AssessmentController::class,'deleteAMatchImage'])->name('lesson.remove_a_match_image');
//    Route::post('lesson/{id}/remove_a_sort_word', [AssessmentController::class,'removeASortWord'])->name('lesson.remove_a_sort_word');
//
//    Route::get('wrong_audio_lessons', [LessonController::class,'getLessonsMedia'])->name('lesson.wrong_audio_lessons');

//
//    //import Teachers
//    Route::get('teachers_import', 'SettingController@importTeachersExcelView')->name('import.teachers_import_view');
//    Route::post('teachers_import', 'SettingController@importTeachersExcel')->name('import.teachers_import');
//


    Route::get('get_marks', function(){
        $lesson_assessment = Lesson::query()->withSum('questions', 'mark')->has('questions')->get()->where('questions_sum_mark', '>', 100)
            ->pluck('questions_sum_mark', 'id')->toArray();
        dd($lesson_assessment);
    });

//

    Route::get('correct_tests', 'LessonController@reCorlessonTest');

    Route::get('copy_lessons', 'LessonController@copyLessons');


});

