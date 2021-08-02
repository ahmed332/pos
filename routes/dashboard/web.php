<?php

// Route::group(['prefix' => LaravelLocalization::setLocale(),
//  'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
//     function () {

//         Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

//              Route::get('/', 'WelcomeController@index')->name('welcome');


//             Route::resource('users', 'UserController')->except(['show']);

//         });//end of dashboard routes
//     });

    Route::group(
        [
            'prefix' => LaravelLocalization::setLocale(),
            'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
        ], function(){
            Route::group(['prefix'=>'dashboard','as'=>'dashboard.','middleware'=>'auth'],function(){
                    Route::get('/', 'DashboardController@index')->name('index');

                Route::resource('users', 'UserController')->except(['show']);
                Route::resource('categories', 'categoryController')->except(['show']);
                Route::resource('products', 'productController')->except(['show']);
                Route::resource('clients', 'ClientController')->except(['show']);
                Route::resource('clients.orders', 'client\orderController')->except(['show']);


            });
        });


