<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\LessonController;
use App\Http\Controllers\Manager\TrainingController;
use App\Http\Controllers\Manager\AssessmentController;
Route::get('/home', function () {
    $users[] = Auth::user();
    $users[] = Auth::guard()->user();
    $users[] = Auth::guard('manager')->user();

    //dd($users);

    return view('manager.home');
})->name('home');

Route::group(['namespace' => 'Manager'], function(){
    Route::resources([
        'lesson' => 'LessonController',
    ]);

    Route::get('lesson/{id}/learn', [LessonController::class,'lessonLearn'])->name('lesson.learn');
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




});

