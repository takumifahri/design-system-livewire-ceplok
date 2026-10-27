<?php

use Illuminate\Support\Facades\Route;

// Urutan dan nama rute mengikuti App\Docs\Navigation (rail, panel samping, pencarian).

Route::view('/', 'pages.home')->name('home');

Route::prefix('components')->name('components.')->group(function () {
    Route::view('/', 'pages.components.index')->name('index');
    Route::view('/container', 'pages.components.container')->name('container');
    Route::view('/button', 'pages.components.button')->name('button');
    Route::view('/badge', 'pages.components.badge')->name('badge');
    Route::view('/avatar', 'pages.components.avatar')->name('avatar');
    Route::view('/spinner', 'pages.components.spinner')->name('spinner');
    Route::view('/popover', 'pages.components.popover')->name('popover');
    Route::view('/dropdown', 'pages.components.dropdown')->name('dropdown');
    Route::view('/drawer', 'pages.components.drawer')->name('drawer');
    Route::view('/modal', 'pages.components.modal')->name('modal');
    Route::view('/alert', 'pages.components.alert')->name('alert');
    Route::view('/toast', 'pages.components.toast')->name('toast');
    Route::view('/card', 'pages.components.card')->name('card');
    Route::view('/list-view', 'pages.components.list-view')->name('list-view');
    Route::view('/hero', 'pages.components.hero')->name('hero');
    Route::view('/footer', 'pages.components.footer')->name('footer');
    Route::view('/breadcrumb', 'pages.components.breadcrumb')->name('breadcrumb');
    Route::view('/pagination', 'pages.components.pagination')->name('pagination');
    Route::view('/sidebar', 'pages.components.sidebar')->name('sidebar');
    Route::view('/table', 'pages.components.table')->name('table');
    Route::view('/timeline', 'pages.components.timeline')->name('timeline');
});

Route::prefix('form')->name('form.')->group(function () {
    Route::view('/', 'pages.form.index')->name('index');
    Route::view('/input-field', 'pages.form.input-field.index')->name('input-field');
    Route::view('/input-field/input', 'pages.form.input-field.input')->name('input-field.input');
    Route::view('/input-field/floating-label', 'pages.form.input-field.floating-label')->name('input-field.floating-label');
    Route::view('/input-field/text-area', 'pages.form.input-field.text-area')->name('input-field.text-area');
    Route::view('/select', 'pages.form.select')->name('select');
    Route::view('/search', 'pages.form.search')->name('search');
    Route::view('/datepicker', 'pages.form.datepicker')->name('datepicker');
    Route::view('/upload', 'pages.form.upload')->name('upload');
    Route::view('/radio', 'pages.form.radio')->name('radio');
    Route::view('/toggle', 'pages.form.toggle')->name('toggle');
    Route::view('/checkbox', 'pages.form.checkbox')->name('checkbox');
});

Route::prefix('foundations')->name('foundations.')->group(function () {
    Route::view('/', 'pages.foundations.index')->name('index');
    Route::view('/colors', 'pages.foundations.colors')->name('colors');
    Route::view('/typography', 'pages.foundations.typography')->name('typography');
    Route::view('/spacing', 'pages.foundations.spacing')->name('spacing');
    Route::view('/border', 'pages.foundations.border')->name('border');
    Route::view('/elevation', 'pages.foundations.elevation')->name('elevation');
    Route::view('/icons', 'pages.foundations.icons')->name('icons');
});
