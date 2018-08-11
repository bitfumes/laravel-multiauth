<?php

Route::group([
    'namespace' => 'Bitfumes\Multiauth\Http\Controllers',
    'middleware' => 'web',
], function () {
    Route::GET('admin/home', 'AdminController@index');
    Route::GET('/login', function () {
        return 'for test only';
    })->name('login');

    // Login and Logout
    Route::GET('/admin', 'LoginController@showLoginForm')->name('admin.login');
    Route::POST('admin', 'LoginController@login');
    Route::POST('admin/logout', 'LoginController@logout')->name('admin.logout');

    // Password Resets
    Route::POST('admin-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::GET('admin-password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::POST('admin-password/reset', 'ResetPasswordController@reset');
    Route::GET('admin-password/reset/{token}', 'ResetPasswordController@showResetForm')->name('admin.password.reset');

    // Register Users
    Route::get('/admin/register', 'RegisterController@showRegistrationForm');
    Route::post('/admin/register', 'RegisterController@register');

    // Admin Lists
    Route::get('/admin/show-all', 'AdminController@show');

    // Admin Roles
    Route::post('/admin/{admin}/role/{role}', 'AdminRoleController@attach');
    Route::delete('/admin/{admin}/role/{role}', 'AdminRoleController@detach');

    // Roles
    Route::get('/admin/role', 'RoleController@index');
    Route::get('/admin/role/create', 'RoleController@create');
    Route::post('/admin/role/store', 'RoleController@store');
    Route::delete('/admin/role/{role}', 'RoleController@destroy');
    Route::get('/admin/role/{role}/edit', 'RoleController@edit');
    Route::patch('/admin/role/{role}', 'RoleController@update');
});
