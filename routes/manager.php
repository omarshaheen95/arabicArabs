<?php

use App\Models\Lesson;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\LessonController;
use App\Http\Controllers\Manager\TrainingController;
use App\Http\Controllers\Manager\AssessmentController;
use App\Http\Controllers\Manager\SettingController;

Route::get('/home', [SettingController::class, 'home'])->name('home');

Route::group(['namespace' => 'Manager'], function () {
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
    Route::get('supervisor-login/{id}', 'SupervisorController@supervisorLogin')->name('supervisor.login');
    //Teacher
    Route::post('approve_teacher', 'TeacherController@approveTeacher')->name('teacher.approveTeacher');
    Route::post('activate_teacher', 'TeacherController@activeTeacher')->name('teacher.activateTeacher');
    Route::post('export_teachers_excel', 'TeacherController@exportTeachersExcel')->name('teacher.export_teachers_excel');
    Route::get('teacher-login/{id}', 'TeacherController@teacherLogin')->name('teacher.login');
    //Story
    Route::resource('story', 'StoryController');
    Route::get('story_assessment/{id}', 'StoryController@storyAssessment')->name('story.assessment');
    Route::post('story_assessment/{id}/{step}', 'StoryController@storeAssessmentStory')->name('story.storeAssessment');
    Route::post('story_update_assessment/{id}/{step}', 'StoryController@updateAssessmentStory')->name('story.updateAssessment');
    Route::post('story_remove_attachment/{id}', 'StoryController@removeAttachment')->name('story.remove_attachment');
    Route::post('story_remove_sort_word/{id}', 'StoryController@removeSortWord')->name('story.remove_sort_word');
    Route::post('story_remove_match_attachment/{id}', 'StoryController@removeMatchAttachment')->name('story.remove_match_attachment');


    Route::get('getTeacherBySchool/{lid}', 'TeacherController@getTeacherBySchool')->name('getTeacherBySchool');
    Route::get('school-login/{id}', 'SchoolController@schoolLogin')->name('school.login');

    Route::get('lesson/{id}/learn', [LessonController::class, 'lessonLearn'])->name('lesson.learn');
    Route::get('lesson/{id}/review/{step}', [LessonController::class, 'lessonReview'])->name('lesson.review');
    Route::post('lesson/{id}/learn', [LessonController::class, 'updateLessonLearn'])->name('lesson.update_learn');
    Route::post('lesson/{id}/remove_lesson_audio', [LessonController::class, 'deleteLessonAudio'])->name('lesson.remove_lesson_audio');
    Route::post('lesson/{video_id}/remove_video_attachment', [LessonController::class, 'deleteLessonVideo'])->name('lesson.remove_video_attachment');

    Route::get('lesson/{id}/training', [TrainingController::class, 'lessonTraining'])->name('lesson.training');
    Route::post('lesson/{id}/training/{type}', [TrainingController::class, 'updateLessonTraining'])->name('lesson.update_training');
    Route::post('lesson/{id}/remove_t_question_attachment', [TrainingController::class, 'deleteTQuestionAttachment'])->name('lesson.remove_t_question_attachment');
    Route::post('lesson/{id}/remove_t_match_image', [TrainingController::class, 'deleteTMatchImage'])->name('lesson.remove_t_match_image');
    Route::post('lesson/{id}/remove_t_sort_word', [TrainingController::class, 'removeTSortWord'])->name('lesson.remove_t_sort_word');

    Route::get('lesson/{id}/assessment', [AssessmentController::class, 'lessonAssessment'])->name('lesson.assessment');
    Route::post('lesson/{id}/assessment/{type}', [AssessmentController::class, 'updateLessonAssessment'])->name('lesson.update_assessment');
    Route::post('lesson/{id}/remove_a_question_attachment', [AssessmentController::class, 'deleteAQuestionAttachment'])->name('lesson.remove_a_question_attachment');
    Route::post('lesson/{id}/remove_a_match_image', [AssessmentController::class, 'deleteAMatchImage'])->name('lesson.remove_a_match_image');
    Route::post('lesson/{id}/remove_a_sort_word', [AssessmentController::class, 'removeASortWord'])->name('lesson.remove_a_sort_word');

    Route::get('wrong_audio_lessons', [LessonController::class, 'getLessonsMedia'])->name('lesson.wrong_audio_lessons');


    //User
    Route::get('user-login/{id}', 'UserController@userLogin')->name('user.login');
    Route::get('duplicate_user', 'UserController@duplicateIndex')->name('user.duplicate_user');
    Route::delete('duplicate_user/{id}', 'UserController@destroyDuplicate')->name('user.delete_duplicate_user');
    Route::post('export_students_excel', 'UserController@exportStudentsExcel')->name('user.export_students_excel');
    Route::get('user/{id}/review', 'UserController@review')->name('user.review');
    Route::get('user/{id}/report', 'UserController@report')->name('user.report');
    Route::get('user/print/cards', 'UserController@cards')->name('user.cards');
    Route::get('user/print/cards_qr', 'UserController@cardsQR')->name('user.cardsQR');
    Route::get('correctTest', 'UserController@correctTest')->name('correctTest');
    Route::get('userGrades', 'UserController@userGrades')->name('userGrades');
//    Route::get('updateUsers', 'UserController@updateUsers')->name('updateUsers');

    //import Users
    Route::get('users_import', 'SettingController@importUserExcelView')->name('import.users_import_view');
    Route::post('users_import', 'SettingController@importUserExcel')->name('import.users_import');
//    Route::get('updateUsers', 'UserController@updateUsers')->name('updateUsers');
    //import Teachers
    Route::get('teachers_import', 'SettingController@importTeachersExcelView')->name('import.teachers_import_view');
    Route::post('teachers_import', 'SettingController@importTeachersExcel')->name('import.teachers_import');

    Route::get('update_assignments', function () {
        $users = \App\Models\UserAssignment::query()
            ->whereRelation('user', 'school_id', 2)
            ->where('created_at', '<=', '2022-10-08')
//            ->with(['user_grades'])
//            ->where('grade_id', '>', 1)
            ->get();
        dd($users->count());
        foreach ($users as $user) {
            $user->delete();
//            $user->update([
//               'grade_id' => $user->grade_id - 1,
//            ]);
        }

        return "تم تحديث بيانات الطلاب بنجاح";
    });

    Route::get('get_marks', function () {
//        $lessons = Lesson::query()
//            ->where('lesson_type', 'grammar')
//            ->where('level', '>', 3)
//            ->with(['questions'])->get();
//        foreach ($lessons as $lesson) {
//            $i = 1;
//            foreach ($lesson->questions->where('type', 1) as $question) {
//                if ($lesson->level <= 3) {
//                    if ($i <= 3) {
//                        $mark = 6;
//                    } else {
//                        $mark = 5;
//
//                    }
//                } else {
//                    if ($i <= 1) {
//                        $mark = 6;
//                    } else {
//                        $mark = 5;
//                    }
//                }
//                $question->update([
//                    'mark' => $mark
//                ]);
//                $i++;
//            }
//            $i = 1;
//            foreach ($lesson->questions->where('type', 2) as $question) {
//                if ($lesson->level <= 3) {
//                    $mark = 6;
//                } else {
//                    $mark = 8;
//                }
//                $question->update([
//                    'mark' => $mark
//                ]);
//                $i++;
//            }
//        }
//        dd($lessons->pluck('id')->toArray());
        $lesson_assessment = Lesson::query()
            ->whereIn('lesson_type', ['reading', 'listening', 'grammar', 'dictation', 'rhetoric'])
            ->withSum('questions', 'mark')
            ->has('questions')

            ->get()
            ->where('questions_sum_mark', '>', 100)
            ->pluck('questions_sum_mark', 'id')->toArray();
        dd($lesson_assessment);
    });

//

    Route::get('correct_tests', 'LessonController@reCorlessonTest');
    Route::resource('import_users_files', ImportController::class);

    Route::get('copy_lessons', 'LessonController@copyLessons');


});

