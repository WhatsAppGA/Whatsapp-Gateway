<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale()], function() {
	
	Route::get('storage-link', function (){
	return Artisan::call('storage:link');
	})->name('storage-link');

	Route::get('/schedule-run', function () {
	return Artisan::call('schedule:run');
	})->name('schedule-run');

	Route::get('/blast-start', function () {
	return Artisan::call('start:blast');
	})->name('blast-start');
	
	Route::get('/subscription-check', function () {
	return Artisan::call('subscription:check');
	})->name('subscription-check');
	
	Route::get('/schedule-cron', function () {
	return Artisan::call('schedule:cron');
	})->name('schedule-cron');

	Route::get('/migrate', function () {
	return Artisan::call('migrate');
	})->name('migrate');

	Route::get('/view-clear', function () {
	return Artisan::call('view:clear');
	})->name('view-clear');

	Route::get('/clear-cache',function(){
	 return Artisan::call('optimize:clear');
	})->name("cache.clear");

	Route::get('/translatable-export/{lang}', function ($lang) {
	return Artisan::call('translatable:export', ['lang' => $lang]);
	})->name('translatable-export');

});
?>