<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WebController@home')->name('main');
Route::get('page/{key}', 'WebController@page')->name('page');

Route::get('/schools', 'WebController@schools')->name('schools');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'manager'], function () {
  Route::get('/login', 'ManagerAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'ManagerAuth\LoginController@login');
  Route::post('/logout', 'ManagerAuth\LoginController@logout')->name('logout');


  Route::post('/password/email', 'ManagerAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'ManagerAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'ManagerAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'ManagerAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'school'], function () {
  Route::get('/login', 'SchoolAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'SchoolAuth\LoginController@login');
  Route::post('/logout', 'SchoolAuth\LoginController@logout')->name('logout');

//  Route::get('/register', 'SchoolAuth\RegisterController@showRegistrationForm')->name('register');
//  Route::post('/register', 'SchoolAuth\RegisterController@register');

  Route::post('/password/email', 'SchoolAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'SchoolAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'SchoolAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'SchoolAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'teacher'], function () {
  Route::get('/login', 'TeacherAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'TeacherAuth\LoginController@login');
  Route::post('/logout', 'TeacherAuth\LoginController@logout')->name('logout');


  Route::post('/password/email', 'TeacherAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'TeacherAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'TeacherAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'TeacherAuth\ResetPasswordController@showResetForm');
});

Route::group(['prefix' => 'supervisor'], function () {
  Route::get('/login', 'SupervisorAuth\LoginController@showLoginForm')->name('login');
  Route::post('/login', 'SupervisorAuth\LoginController@login');
  Route::post('/logout', 'SupervisorAuth\LoginController@logout')->name('logout');


  Route::post('/password/email', 'SupervisorAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
  Route::post('/password/reset', 'SupervisorAuth\ResetPasswordController@reset')->name('password.email');
  Route::get('/password/reset', 'SupervisorAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
  Route::get('/password/reset/{token}', 'SupervisorAuth\ResetPasswordController@showResetForm');
});

Route::get('migrate', function (){
    \Illuminate\Support\Facades\Artisan::call('migrate');
});
Route::get('view', function (){
    \Illuminate\Support\Facades\Artisan::call('view:clear');
});
Route::get('command', function (){
    \Illuminate\Support\Facades\Artisan::call('schedule:work');
});
Route::get('cache', function (){
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
});


Route::group(['namespace' => 'User', 'middleware' => ['auth']], function (){
    Route::get('check_subscribe', 'UserController@checkSubscribe')->name('check_subscribe');
    Route::get('subscribe_payment', 'UserController@subscribePayment')->name('subscribe_payment');
    Route::get('confirm_subscribe_payment', 'UserController@checkSubscribePayment')->name('post_subscribe_payment');

    Route::get('package_upgrade', 'UserController@packageUpgrade')->name('package_upgrade');
    Route::post('confirm_package_upgrade', 'UserController@payPackageUpgrade')->name('post_package_upgrade');

    Route::get('/home', 'HomeController@home')->name('home');
    Route::get('levels', 'HomeController@levels')->name('levels');
    Route::get('lessons_levels/{grade}/{type}', 'HomeController@subLevels')->name('lessons_levels');
    Route::get('stories', 'HomeController@storiesLevels')->name('levels.stories');

    Route::group(['middleware' => 'activeAccount'], function (){
        Route::get('stories/{id}', 'HomeController@stories')->name('stories.list');
        Route::get('stories/{id}/{key}', 'HomeController@story')->name('stories.show');
        Route::post('stories/{id}/record', 'HomeController@recordStory')->name('stories.record');

        Route::post('story_test/{id}', 'StoryController@storyTest')->name('story_test');
        Route::get('story_test/{id}/result', 'StoryController@storyTestResult')->name('story_test_result');

        Route::get('lessons/{id}/{type}', 'HomeController@lessons')->name('lessons');
        Route::get('sub_lessons/{id}/{type}/{level}', 'HomeController@subLessons')->name('sub_lessons');
        Route::get('lesson/{id}/{key}', 'HomeController@lesson')->name('lesson');

        Route::post('lesson_test/{id}', 'LessonController@lessonTest')->name('lesson_test');
        Route::post('lesson_writing_test/{id}', 'LessonController@lessonWritingTest')->name('lesson_writing_test');
        Route::post('lesson_speaking_test/{id}', 'LessonController@lessonSpeakingTest')->name('lesson_speaking_test');
        Route::get('lesson_test/{id}/result', 'LessonController@lessonTestResult')->name('lesson_test_result');

        Route::get('certificates', 'HomeController@certificates')->name('certificates');
        Route::get('certificate/{id}', 'HomeController@certificate')->name('certificate');
        Route::get('new_certificate/{id}', 'HomeController@newCertificate')->name('newCertificate');
        Route::get('certificate/{id}/answers', 'HomeController@certificateAnswers')->name('certificate.answers');

        Route::get('story_certificates', 'StoryController@certificates')->name('story.certificates');
        Route::get('story_certificate/{id}', 'StoryController@certificate')->name('story.certificate');
        Route::get('story_certificate/{id}/answers', 'StoryController@certificateAnswers')->name('story.certificate.answers');

        Route::get('assignments', 'HomeController@assignments')->name('assignments');
        Route::get('stories_assignments', 'HomeController@storiesAssignments')->name('stories_assignments');

        Route::post('track_lesson/{id}/{type}', 'UserController@trackLesson')->name('track_lesson');
        Route::post('user_lesson/{id}', 'UserController@userLesson')->name('user_lesson');
    });

    Route::get('profile', 'UserController@profile')->name('profile');
    Route::post('profile_update', 'UserController@profileUpdate')->name('profile_update');
    Route::get('update_password', 'UserController@updatePasswordView')->name('update_password_view');
    Route::post('update_password', 'UserController@updatePassword')->name('update_password');



});
Route::get('update_speaking', function (){
    $speakings = \App\Models\SpeakingResult::query()->get();
    foreach ($speakings as $speaking)
    {
        $speaking->update([
            'attachment' => str_replace('speaking_result', 'record_results', $speaking->getOriginal('attachment'))
        ]);
    }
});

Route::get('update_users', function () {
    $users = \App\Models\User::query()->where('school_id', 1575)->update([
        'active_to' => \Carbon\Carbon::parse('2023-07-31'),
    ]);
//    foreach ($users as $key => $user) {
//        $user->update([
//            'active_to' => \Carbon\Carbon::parse('2023-07-31'),
//        ]);
//    }
    dd('done');


});
