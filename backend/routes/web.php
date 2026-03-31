<?php
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
 Livewire::setScriptRoute(function ($handle) {
    $base = request()->getBasePath();
    return Route::get($base . '/vendor/livewire/livewire/dist/livewire.min.js', $handle);
});
