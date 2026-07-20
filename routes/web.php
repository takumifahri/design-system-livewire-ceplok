<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::prefix('foundations')->name('foundations.')->group(function () {
    Route::view('/', 'pages.foundations.colors')->name('index');
    Route::view('/colors', 'pages.foundations.colors')->name('colors');
    Route::view('/typography', 'pages.foundations.typography')->name('typography');
});

Route::prefix('components')->name('components.')->group(function () {
    Route::view('/', 'pages.components.index')->name('index');
    Route::view('/button', 'pages.components.button')->name('button');
    Route::view('/badge', 'pages.components.badge')->name('badge');
    Route::view('/card', 'pages.components.card')->name('card');
    Route::view('/footer', 'pages.components.footer')->name('footer');
});
