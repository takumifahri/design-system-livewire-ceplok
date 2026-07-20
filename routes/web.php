<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home')->name('home');

Route::prefix('foundations')->name('foundations.')->group(function () {
    Route::view('/', 'pages.foundations.colors')->name('index');
    Route::view('/colors', 'pages.foundations.colors')->name('colors');
    Route::view('/typography', 'pages.foundations.typography')->name('typography');
    Route::view('/spacing', 'pages.foundations.spacing')->name('spacing');
    Route::view('/border', 'pages.foundations.border')->name('border');
    Route::view('/elevation', 'pages.foundations.elevation')->name('elevation');
    Route::view('/icons', 'pages.foundations.icons')->name('icons');
});

Route::prefix('components')->name('components.')->group(function () {
    Route::view('/', 'pages.components.index')->name('index');
    Route::view('/button', 'pages.components.button')->name('button');
    Route::view('/badge', 'pages.components.badge')->name('badge');
});
