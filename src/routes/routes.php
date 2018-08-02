<?php

Route::group([
    'namespace' => 'Bitfumes\Multiauth\Http\Controllers',
    'middleware' => 'web',
], function () {
    Route::GET('admin/home', 'AdminController@index');

    Route::GET('/admin', 'LoginController@showLoginForm')->name('admin.login');
    Route::POST('admin', 'LoginController@login');
    Route::POST('admin/logout', 'LoginController@logout')->name('admin.logout');

    Route::POST('admin-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::GET('admin-password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::POST('admin-password/reset', 'ResetPasswordController@reset');
    Route::GET('admin-password/reset/{token}', 'ResetPasswordController@showResetForm')->name('admin.password.reset');
});
