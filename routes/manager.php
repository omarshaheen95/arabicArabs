<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\LessonController;
use App\Http\Controllers\Manager\TrainingController;
use App\Http\Controllers\Manager\AssessmentController;
use App\Http\Controllers\Manager\SettingController;

Route::get('/home', [SettingController::class, 'home'])->name('home');

Route::group(['namespace' => 'Manager'], function(){
    Route::resources([
        'lesson' => 'LessonController',
        'school' => 'SchoolController',
        'supervisor' => 'SupervisorController',
        'teacher' => 'TeacherController',
        'package' => 'PackageController',
        'user' => 'UserController',
    ]);

    //Supervisor
    Route::post('export_supervisor_excel', 'SupervisorController@exportSupervisorsExcel')->name('supervisor.export_supervisor_excel');

    //Teacher
    Route::post('approve_teacher', 'TeacherController@approveTeacher')->name('teacher.approveTeacher');
    Route::post('activate_teacher', 'TeacherController@activeTeacher')->name('teacher.activateTeacher');
    Route::post('export_teachers_excel', 'TeacherController@exportTeachersExcel')->name('teacher.export_teachers_excel');
    //Story
    Route::resource('story', 'StoryController');
    Route::get('story_assessment/{id}', 'StoryController@storyAssessment')->name('story.assessment');
    Route::post('story_assessment/{id}/{step}', 'StoryController@storeAssessmentStory')->name('story.storeAssessment');
    Route::post('story_update_assessment/{id}/{step}', 'StoryController@updateAssessmentStory')->name('story.updateAssessment');
    Route::post('story_remove_attachment/{id}', 'StoryController@removeAttachment')->name('story.remove_attachment');
    Route::post('story_remove_sort_word/{id}', 'StoryController@removeSortWord')->name('story.remove_sort_word');
    Route::post('story_remove_match_attachment/{id}', 'StoryController@removeMatchAttachment')->name('story.remove_match_attachment');




    Route::get('getTeacherBySchool/{lid}', 'TeacherController@getTeacherBySchool')->name('getTeacherBySchool');

    Route::get('lesson/{id}/learn', [LessonController::class,'lessonLearn'])->name('lesson.learn');
    Route::get('lesson/{id}/review/{step}', [LessonController::class,'lessonReview'])->name('lesson.review');
    Route::post('lesson/{id}/learn', [LessonController::class,'updateLessonLearn'])->name('lesson.update_learn');
    Route::post('lesson/{id}/remove_lesson_audio', [LessonController::class,'deleteLessonAudio'])->name('lesson.remove_lesson_audio');

    Route::get('lesson/{id}/training', [TrainingController::class,'lessonTraining'])->name('lesson.training');
    Route::post('lesson/{id}/training/{type}', [TrainingController::class,'updateLessonTraining'])->name('lesson.update_training');
    Route::post('lesson/{id}/remove_t_question_attachment', [TrainingController::class,'deleteTQuestionAttachment'])->name('lesson.remove_t_question_attachment');
    Route::post('lesson/{id}/remove_t_match_image', [TrainingController::class,'deleteTMatchImage'])->name('lesson.remove_t_match_image');
    Route::post('lesson/{id}/remove_t_sort_word', [TrainingController::class,'removeTSortWord'])->name('lesson.remove_t_sort_word');

    Route::get('lesson/{id}/assessment', [AssessmentController::class,'lessonAssessment'])->name('lesson.assessment');
    Route::post('lesson/{id}/assessment/{type}', [AssessmentController::class,'updateLessonAssessment'])->name('lesson.update_assessment');
    Route::post('lesson/{id}/remove_a_question_attachment', [AssessmentController::class,'deleteAQuestionAttachment'])->name('lesson.remove_a_question_attachment');
    Route::post('lesson/{id}/remove_a_match_image', [AssessmentController::class,'deleteAMatchImage'])->name('lesson.remove_a_match_image');
    Route::post('lesson/{id}/remove_a_sort_word', [AssessmentController::class,'removeASortWord'])->name('lesson.remove_a_sort_word');




    //User
    Route::get('duplicate_user', 'UserController@duplicateIndex')->name('user.duplicate_user');
    Route::delete('duplicate_user/{id}', 'UserController@destroyDuplicate')->name('user.delete_duplicate_user');
    Route::post('export_students_excel', 'UserController@exportStudentsExcel')->name('user.export_students_excel');
    Route::get('user/{id}/review', 'UserController@review')->name('user.review');
    Route::get('user/{id}/report', 'UserController@report')->name('user.report');
    Route::get('user/print/cards', 'UserController@cards')->name('user.cards');
    Route::get('correctTest', 'UserController@correctTest')->name('correctTest');
//    Route::get('userGrades', 'UserController@userGrades')->name('userGrades');
//    Route::get('updateUsers', 'UserController@updateUsers')->name('updateUsers');

});

