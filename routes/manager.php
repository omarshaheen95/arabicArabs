<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\LessonController;
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

    Route::get('lesson/{id}/training', [LessonController::class,'lessonTraining'])->name('lesson.training');
    Route::post('lesson/{id}/training/{type}', [LessonController::class,'updateLessonTraining'])->name('lesson.update_training');
    Route::post('lesson/{id}/remove_t_question_attachment', [LessonController::class,'deleteTQuestionAttachment'])->name('lesson.remove_t_question_attachment');
    Route::post('lesson/{id}/remove_t_match_image', [LessonController::class,'deleteTMatchImage'])->name('lesson.remove_t_match_image');
    Route::post('lesson/{id}/remove_t_sort_word', [LessonController::class,'removeTSortWord'])->name('lesson.remove_t_sort_word');




    Route::get('lesson/{id}/assessment', [LessonController::class,'lessonAssessment'])->name('lesson.assessment');
    Route::post('lesson/{id}/assessment/{type}', [LessonController::class,'lessonAssessment'])->name('lesson.update_assessment');
});

