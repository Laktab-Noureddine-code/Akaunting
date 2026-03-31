<?php
use Illuminate\Support\Facades\Route;
Route::group(['as' => 'api.', 'namespace' => 'Auth'], function () {
    Route::post('login', 'Login@login')->name('login');
});
