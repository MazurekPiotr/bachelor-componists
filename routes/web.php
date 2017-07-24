<?php

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

Route::get('/', 'ProjectsController@home');

Auth::routes();

// log.activity middleware logs the time of user activity that any inclusive routes are hit
Route::group(['middleware' => ['log.activity']], function() {

    // use profile
    Route::get('/user/profile/@{user}', 'ProfileController@index')->name('user.profile.index');

    // auth routing
    Route::group(['middleware' => ['auth']], function () {

        // general auth routing
        Route::get('/home', 'HomeController@index')->name('home.index');

        Route::group(['prefix' => 'componists'], function() {
            Route::get('/projects/create', 'ProjectsController@showCreateForm')->name('componists.projects.create.form');
            Route::post('/projects/create', 'ProjectsController@create')->name('componists.projects.create.submit');

            Route::get('/projects/{project}/subscription/status', 'SubscriptionsController@getSubscriptionStatus')->name('componists.projects.project.subscription.status');
            Route::post('/projects/{project}/subscription', 'SubscriptionsController@handleSubscription')->name('componists.projects.project.subscription.submit');

            Route::post('/projects/{project}/fragments/create', 'FragmentsController@create')->name('componists.projects.fragments.create.submit');
            Route::get('/projects/{project}/fragments/{fragment}/edit', 'FragmentsController@edit')->name('componists.projects.project.fragments.fragment.edit');
            Route::post('/projects/{project}/fragments/{fragment}/update', 'FragmentsController@update')->name('componists.projects.project.fragments.post.update');
            Route::delete('/projects/{project}/fragments/{fragment}/delete', 'FragmentsController@destroy')->name('componists.projects.project.fragments.post.delete');

            Route::post('/projects/{project}/report', 'ProjectsReportController@report')->name('componists.projects.project.report.report');
            Route::post('/projects/{project}/fragments/{fragment}/report', 'FragmentsReportController@report')->name('componists.projects.project.fragments.post.report.report');
            Route::post('/fragments/{fragment}/setVolume/{volume}', 'FragmentsController@setVolume');


        });

        // user routing
        Route::group(['prefix' => 'user'], function() {

            Route::group(['prefix' => 'chat/threads'], function() {
                // user messaging
                Route::get('/', 'MessagesThreadController@index')->name('user.chat.threads.index');
                Route::post('/create', 'MessagesThreadController@create')->name('user.chat.threads.create');

                Route::get('/@{user}/messages', 'MessagesController@index')->name('user.chat.threads.thread.messages.index');
                Route::get('/@{user}/messages/fetch', 'MessagesController@fetchMessages')->name('user.chat.threads.thread.messages.fetch');
                Route::post('/@{user}/messages', 'MessagesController@create')->name('user.chat.threads.thread.messages.create');
            });

            Route::group(['prefix' => 'profile'], function() {
                // user profile
                Route::get('/@{user}/settings', 'ProfileSettingsController@index')->name('user.profile.settings.index');
                Route::post('/@{user}/settings/update/', 'ProfileSettingsController@update')->name('user.profile.settings.update');
            });

        });

        // admin routing
        Route::group(['prefix' => 'admin', 'middleware' => ['auth.admin']], function () {
            // admin dashboard
            Route::get('/dashboard', 'AdministratorDashboardController@index')->name('admin.dashboard.index');
            Route::post('/dashboard/update', 'AdministratorDashboardController@update')->name('admin.dashboard.update');
            Route::post('/dashboard/invite', 'AdministratorDashboardController@invite')->name('admin.dashboard.invite');
            Route::delete('/projects/{project}', 'ProjectsController@destroy')->name('componists.projects.project.delete');

            Route::delete('/dashboard/users/{user}', 'AdministratorDashboardController@destroy')->name('admin.dashboard.user.destroy');
        });

        // moderator dashboard, also accessible by admin (auth.elevated)
        Route::group(['prefix' => 'moderator', 'middleware' => ['auth.elevated']], function() {
            Route::get('/dashboard', 'ModeratorDashboardController@index')->name('moderator.dashboard.index');
            Route::delete('/dashboard/reports/{report}', 'ModeratorDashboardController@destroy')->name('moderator.dashboard.reports.report.destroy');
        });

    });

    Route::group(['prefix' => 'projects'], function() {
        Route::get('/', 'ProjectsController@index')->name('componists.projects.index');
        Route::get('/{project}', 'ProjectsController@show')->name('componists.projects.project.show');

        Route::get('/{project}/report/status', 'ProjectsReportController@status')->name('componists.projects.project.report.status');
        Route::get('/{project}/fragments/{fragment}/report/status', 'FragmentsReportController@status')->name('componists.projects.project.fragments.post.report.status');
        Route::get('/fragments/{fragment}/getVolume', 'FragmentsController@getVolume');

    });

});
