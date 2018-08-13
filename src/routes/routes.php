<?php

Route::group([
    'namespace' => 'Bitfumes\Multiauth\Http\Controllers',
    'middleware' => 'web',
// Remove this line and uncomment following line once you remove all the urls and use route names.
//    'prefix' => config('multiauth.prefix','admin')
], function () {
    Route::GET('admin/home', 'AdminController@index')->name('admin.home');
    // Login and Logout
    Route::GET('/admin', 'LoginController@showLoginForm')->name('admin.login');
    Route::POST('admin', 'LoginController@login');
    Route::POST('admin/logout', 'LoginController@logout')->name('admin.logout');

    // Password Resets
    Route::POST('admin-password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::GET('admin-password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::POST('admin-password/reset', 'ResetPasswordController@reset');
    Route::GET('admin-password/reset/{token}', 'ResetPasswordController@showResetForm')->name('admin.password.reset');

    // Register Admins
    Route::get('/admin/register', 'RegisterController@showRegistrationForm')->name('admin.register');
    Route::post('/admin/register', 'RegisterController@register');
    Route::get('/admin/{admin}/edit', 'RegisterController@edit')->name('admin.edit');
    Route::delete('/admin/{admin}', 'RegisterController@destroy')->name('admin.delete');
    Route::patch('/admin/{admin}', 'RegisterController@update')->name('admin.update');

    // Admin Lists
    Route::get('/admin/show', 'AdminController@show')->name('admin.show');

    // Admin Roles
    Route::post('/admin/{admin}/role/{role}', 'AdminRoleController@attach')->name('admin.attach-role');
    Route::delete('/admin/{admin}/role/{role}', 'AdminRoleController@detach')->name('admin.detach-role');

    // Roles
    Route::get('/admin/roles', 'RoleController@index')->name('admin.roles');
    Route::get('/admin/role/create', 'RoleController@create')->name('admin.roles.create');
    Route::post('/admin/role/store', 'RoleController@store');
    Route::delete('/admin/role/{role}', 'RoleController@destroy')->name('admin.role.delete');
    Route::get('/admin/role/{role}/edit', 'RoleController@edit')->name('admin.role.edit');
    Route::patch('/admin/role/{role}', 'RoleController@update')->name('admin.role.update');
});
