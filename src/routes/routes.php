<?php

    // Login and Logout
    Route::POST('/login', 'AdminController@login')->name('admin.login');
    Route::POST('/logout', 'AdminController@logout')->name('admin.logout');

    // Password Resets
    Route::POST('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::POST('/password/reset', 'ResetPasswordController@reset')->name('admin.password.request');
    Route::POST('/password/change', 'AdminController@changePassword')->name('admin.password.change');

    // // Register Admins
    Route::post('/register', 'RegisterController@register')->name('admin.register');
    Route::delete('/{admin}', 'AdminController@destroy')->name('admin.delete');
    Route::patch('/{admin}', 'AdminController@update')->name('admin.update');

    // // Admin Lists
    Route::get('/all', 'AdminController@all')->name('admin.all');
    Route::get('/me', 'AdminController@me')->name('admin.me');

    // // Admin Roles
    Route::post('/{admin}/role/{role}', 'AdminRoleController@attach')->name('admin.attach.roles');
    Route::delete('/{admin}/role/{role}', 'AdminRoleController@detach');

    // // Roles
    Route::post('/role', 'RoleController@index')->name('admin.role.index');
    Route::post('/role/store', 'RoleController@store')->name('admin.role.store');
    Route::delete('/role/{role}', 'RoleController@destroy')->name('admin.role.delete');
    Route::patch('/role/{role}', 'RoleController@update')->name('admin.role.update');

    // active status
    Route::post('activation/{admin}', 'ActivationController@activate')->name('admin.activation');
    Route::delete('activation/{admin}', 'ActivationController@deactivate');

    Route::apiResource('permission', 'PermissionController');
