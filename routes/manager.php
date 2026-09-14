<?php

use App\Http\Controllers\GeneralController;
use App\Models\Lesson;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\LessonController;
use App\Http\Controllers\Manager\TrainingController;
use App\Http\Controllers\Manager\AssessmentController;
use App\Http\Controllers\Manager\SettingController;
use Spatie\Permission\Models\Permission;

require base_path('routes/general.php');

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
    Route::post('/manager/export', 'ManagerController@export')->name('manager.export');

    //Profile
    Route::get('profile/edit', 'ManagerController@editProfile')->name('edit-profile');
    Route::post('profile/update', 'ManagerController@updateProfile')->name('update-profile');
    Route::get('password/edit', 'ManagerController@editPassword')->name('edit-password');
    Route::post('password/update', 'ManagerController@updatePassword')->name('update-password');

    //Activity Log Controller
    Route::get('activity-log', 'ActivityLogController@index')->name('activity-log.index');
    Route::get('activity-log/{id}', 'ActivityLogController@show')->name('activity-log.show');
    Route::delete('activity-log', 'ActivityLogController@destroy')->name('activity-log.delete');

    //Import Controller
    Route::resource('import_files', 'ImportFileController')->except(['destroy']);
    Route::post('import_files_export_data', 'ImportFileController@exportDataAsExcel')->name('import_files.export_excel');
    Route::get('import_files/{id}/user_cards', 'ImportFileController@usersCards')->name('import_files.users_cards');
    Route::delete('delete_import_files', 'ImportFileController@destroy')->name('import_files.delete');
    //Logs
    Route::get('import_files/{id}/show_logs', 'ImportFileController@showFromErrors')->name('import_files.show_logs');
    Route::delete('import_files/delete_student_file_logs', 'ImportFileController@deleteLogs')->name('import_files.delete_logs');
    Route::post('import_files/save_student_data_logs', 'ImportFileController@saveLogs')->name('import_files.save_logs');

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

    //Story
    Route::resource('story', 'StoryController')->except('destroy');
    Route::delete('story/delete', 'StoryController@destroy')->name('story.destroy');
    Route::get('story/{id}/review/{type}', 'StoryController@review')->name('story.review');
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

    //Year
    Route::resource('year','YearController')->except('destroy');
    Route::delete('year', 'YearController@destroy')->name('year.destroy');

    //Login Sessions

    Route::post('login_sessions/export', [\App\Http\Controllers\Manager\LoginSessionController::class, 'export'])->name('login_sessions.export');
    Route::resource('login_sessions', 'LoginSessionController');

    //Text translation
    Route::get('text_translation', 'TextTranslationController@index')->name('text_translation.index');
    Route::post('update_translation/{lang}/{file}', 'TextTranslationController@updateTranslations')->name('text_translation.update');

    //package
    Route::resource('package', 'PackageController')->except(['destroy']);
    Route::delete('package/delete', 'PackageController@destroy')->name('package.destroy');

    //Achievement Levels
    Route::resource('achievement_levels', 'AchievementLevelController')->except(['destroy']);
    Route::delete('achievement_levels/delete', 'AchievementLevelController@destroy')->name('achievement_levels.destroy');

    Route::get('seed',function (){
        if (!defined('STDIN')) {
            define('STDIN', fopen('php://stdin', 'r'));
        }

        Artisan::call('db:seed --class SettingsTableSeeder');
//
//        $all_manager_permission = Permission::query()->where('guard_name','manager')->get()->pluck('name')->toArray();
//        Auth::guard('manager')->user()->syncPermissions($all_manager_permission);
        return redirect()->route('manager.home');

    });
    Route::get('give_roles', function () {

//            foreach (\App\Models\Manager::all() as $manager){
//                $manager->syncRoles('Manager');
//            }
        foreach (\App\Models\School::all() as $school) {
            $school->syncRoles('School');
        }
        foreach (\App\Models\Teacher::all() as $teacher) {
            $teacher->syncRoles('Teacher');
        }
        foreach (\App\Models\Supervisor::all() as $supervisor) {
            $supervisor->syncRoles('Supervisor');
        }
        return redirect()->route('manager.home')->with('message', 'The roles assigned successfully');

    });

    Route::get('give_all_permissions/{guard}', function ($guard) {
        //Artisan::call('db:seed --class PermissionsTableSeeder');
        //Artisan::call('db:seed --class SettingsTableSeeder');
        if ($guard && in_array($guard, ['manager', 'school', 'teacher', 'supervisor'])) {

            $all_permission = Permission::query()->where('guard_name', $guard)->get()->pluck('name')->toArray();
            $users = [];
            switch ($guard) {
                case 'manager':
                    $users = \App\Models\Manager::get();
                    break;
                case 'teacher':
                    $users = \App\Models\Teacher::get();
                    break;
                case 'school':
                    $users = \App\Models\School::get();
                    break;
                case 'supervisor':
                    $users = \App\Models\Supervisor::get();
                    break;

            }

            foreach ($users as $user) {
                $user->syncPermissions($all_permission);
            }
            return redirect()->route('manager.home')->with('message', 'The ' . $guard . 's takes all permissions');

        }
        return redirect()->route('manager.home')->with('message', 'The (' . $guard . ') guard not found');

    });

    //old
    Route::get('get_marks', function(){
        $lesson_assessment = Lesson::query()->withSum('questions', 'mark')->has('questions')->get()->where('questions_sum_mark', '>', 100)
            ->pluck('questions_sum_mark', 'id')->toArray();
        dd($lesson_assessment);
    });


    Route::get('correct_tests', 'LessonController@reCorlessonTest');

    Route::get('copy_lessons', 'LessonController@copyLessons');

    Route::get('copy-teacher-data', 'SettingController@copyTeacherData');

    Route::get('remove_space', function (){
        $users = \App\Models\User::query()
            ->where('email', 'like', '% %')
            ->orWhere('email', 'like', '% %')
            ->get();
        //remove space and white space and   from email.
//        dd($users->count());

        foreach ($users as $user){
            $user->email = str_replace([' ', ' '], '', $user->email);
            $user->save();
        }

        return 'done';
    });


});
